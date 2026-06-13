<?php
/**
* WP hooks
*
* @package usmasmuiza
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('ACF_INCLUDE_LEGACY_ICON_CHOICES', true);

// Note: DISALLOW_FILE_EDIT (and optionally DISALLOW_FILE_MODS) now live in
// wp-config.php — the canonical place, loaded before the theme. Keep them in
// sync on the live server's wp-config.php.

// Enable excerpts for pages
add_post_type_support( 'page', 'excerpt' );

/**
 * Keep wp-login.php / wp-admin free of WPML's language prefix.
 *
 * When browsing the English side, WPML prepends the language code to the login
 * URL, producing /en/wp-login.php — which does not exist and 404s. This strips
 * the 2-letter language segment so the login/admin URL stays at the site root.
 * Query args (redirect_to, action, _wpnonce) are preserved.
 */
function usmasmuiza_strip_lang_from_login_url( $url ) {
	if ( ! is_string( $url ) ) {
		return $url;
	}
	return preg_replace( '#/[a-z]{2}(/wp-(?:login\.php|admin))#', '$1', $url );
}
add_filter( 'login_url', 'usmasmuiza_strip_lang_from_login_url', 999 );
add_filter( 'logout_url', 'usmasmuiza_strip_lang_from_login_url', 999 );

/**
 * Front-end Content-Security-Policy, shipped in **Report-Only** mode.
 *
 * Report-Only never blocks anything — the browser only reports what a real
 * policy *would* have blocked (visible in DevTools console / report-uri). This
 * lets us watch for missed sources before enforcing. Covers the known sources:
 * Sirvoy (booking iframe), Google Maps (map iframe), Google Fonts, and Google
 * analytics (Site Kit). 'unsafe-inline'/'unsafe-eval' are kept because WordPress
 * core, Gravity Forms and analytics emit inline scripts/styles.
 *
 * To ENFORCE later: review reports, tighten sources, then change the header
 * name from 'Content-Security-Policy-Report-Only' to 'Content-Security-Policy'.
 *
 * @return string
 */
function usmasmuiza_csp_policy() {
	$directives = array(
		"default-src 'self'",
		"base-uri 'self'",
		"object-src 'none'",
		"frame-ancestors 'self'",
		"form-action 'self'",
		"img-src 'self' data: https:",
		"font-src 'self' data: https://fonts.gstatic.com",
		"style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
		"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://*.sirvoy.com",
		"connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://*.sirvoy.com",
		"frame-src 'self' https://*.sirvoy.com https://www.google.com https://maps.google.com",
	);
	return implode( '; ', $directives );
}

/**
 * Baseline security response headers (front end).
 *
 * Conservative, low-risk hardening that won't break the booking iframe, forms
 * or analytics. CSP ships Report-Only (see usmasmuiza_csp_policy) so it can be
 * tuned from real reports before being enforced.
 */
function usmasmuiza_security_headers( $headers ) {
	if ( is_admin() ) {
		return $headers;
	}
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['X-Frame-Options']        = 'SAMEORIGIN';
	$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']     = 'geolocation=(), camera=(), microphone=(), interest-cohort=()';
	$headers['Content-Security-Policy-Report-Only'] = usmasmuiza_csp_policy();
	// HSTS only over HTTPS, so a local/HTTP environment is never pinned to TLS.
	if ( is_ssl() ) {
		$headers['Strict-Transport-Security'] = 'max-age=15552000; includeSubDomains';
	}
	return $headers;
}
add_filter( 'wp_headers', 'usmasmuiza_security_headers' );

// Disable XML-RPC — a common brute-force / pingback amplification surface that
// this site does not use. Remove this line if a service needs XML-RPC.
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Block anonymous username enumeration via the REST API (/wp-json/wp/v2/users).
 * Logged-in requests are unaffected, so the editor and plugins keep working.
 */
function usmasmuiza_restrict_rest_user_endpoints( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	return $endpoints;
}
add_filter( 'rest_endpoints', 'usmasmuiza_restrict_rest_user_endpoints' );

/**
 * Block ?author=N username enumeration on the front end (the classic scanner
 * that maps user IDs to login slugs via the author archive redirect). Logged-in
 * users and admin are unaffected. The site has no author archives, so anonymous
 * author queries simply go home.
 */
function usmasmuiza_block_author_enumeration() {
	if ( is_admin() || is_user_logged_in() ) {
		return;
	}
	if ( isset( $_GET['author'] ) || is_author() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'usmasmuiza_block_author_enumeration' );

/**
 * Remove default Posts post type from admin
 */
// Remove "Posts" from admin menu
function usmasmuiza_remove_posts_menu() {
    remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'usmasmuiza_remove_posts_menu' );

// Remove "Posts" from admin bar "+ New" dropdown
function usmasmuiza_remove_posts_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node( 'new-post' );
}
add_action( 'wp_before_admin_bar_render', 'usmasmuiza_remove_posts_admin_bar' );

// Remove Posts-related dashboard widgets
function usmasmuiza_remove_posts_dashboard_widgets() {
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'usmasmuiza_remove_posts_dashboard_widgets' );

// Add text to login
function add_h1_to_login_form() {
    echo '<h1>'.__('Login', 'usmasmuiza').':</h1>';
}
add_action('woocommerce_login_form_start', 'add_h1_to_login_form');

// Login screen logo
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/screenshot.png?v=<?php echo filemtime( get_stylesheet_directory() . '/screenshot.png' ); ?>);
            height:240px;
            width:320px;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// Login screen link
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );


/**
 * ACF JSON Sync
 * Bidirectional sync between ACF dashboard and JSON files
 */

// Save ACF JSON files to theme folder when saving in dashboard
function usmasmuiza_acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'usmasmuiza_acf_json_save_point' );

// Load ACF JSON files from theme folder
function usmasmuiza_acf_json_load_point( $paths ) {
    // Remove the original path
    unset( $paths[0] );

    // Add our custom path
    $paths[] = get_stylesheet_directory() . '/acf-json';

    return $paths;
}
add_filter( 'acf/settings/load_json', 'usmasmuiza_acf_json_load_point' );

/**
 * Disable Gutenberg editor globally for all post types
 */
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'use_block_editor_for_post_type', '__return_false' );

/**
 * Ensure content revisions are available for editorial recovery.
 *
 * Runs at init priority 20 (after the custom post types register) so
 * add_post_type_support takes effect for them. Revisions let editors roll back
 * accidental or malicious content changes.
 */
function usmasmuiza_enable_revisions() {
    foreach ( array( 'page', 'room', 'offer', 'jaunums' ) as $post_type ) {
        add_post_type_support( $post_type, 'revisions' );
    }
}
add_action( 'init', 'usmasmuiza_enable_revisions', 20 );

/**
 * Disable comments and discussions globally
 */
// Disable comments support for all post types
function usmasmuiza_disable_comments_support() {
    foreach ( get_post_types( array(), 'names' ) as $post_type ) {
        remove_post_type_support( $post_type, 'comments' );
        remove_post_type_support( $post_type, 'trackbacks' );
    }
}
add_action( 'init', 'usmasmuiza_disable_comments_support' );

// Close comments on frontend
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// Hide existing comments
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

// Remove comments from admin menu
function usmasmuiza_remove_comments_admin_menu() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'usmasmuiza_remove_comments_admin_menu' );

// Remove comments from admin bar
function usmasmuiza_remove_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'usmasmuiza_remove_comments_admin_bar' );

// Redirect comments page to dashboard
function usmasmuiza_redirect_comments_page() {
    global $pagenow;
    if ( $pagenow === 'edit-comments.php' ) {
        wp_safe_redirect( admin_url() );
        exit;
    }
}
add_action( 'admin_init', 'usmasmuiza_redirect_comments_page' );

// Remove comments metabox from dashboard
function usmasmuiza_remove_dashboard_comments_metabox() {
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'usmasmuiza_remove_dashboard_comments_metabox' );

/**
 * ACF Field for Category - Hide from archive filter
 */
function usmasmuiza_acf_category_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key' => 'group_category_settings',
        'title' => 'Category Settings',
        'fields' => array(
            array(
                'key' => 'field_hide_from_archive_filter',
                'label' => 'Hide from archive filter',
                'name' => 'hide_from_archive_filter',
                'type' => 'true_false',
                'instructions' => 'Check this to hide this category from the projects archive filter dropdown.',
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'category',
                ),
            ),
        ),
    ) );
}
add_action( 'acf/init', 'usmasmuiza_acf_category_fields' );

/**
 * Gravity Forms - Replace submit input with button element and add info text
 */
function usmasmuiza_gform_submit_button( $button, $form ) {
	// Get the submit button text from the form settings or use default
	$button_text = ! empty( $form['button']['text'] ) ? $form['button']['text'] : __( 'Send', 'usmasmuiza' );

	// Create custom button with arrow
	$custom_button = '<button type="submit" class="gform_button button" id="gform_submit_button_' . $form['id'] . '">';
		$custom_button .= '<span>' . esc_html( $button_text ) . '</span>';
		$custom_button .= '<i aria-hidden="true"></i>';
	$custom_button .= '</button>';

	// Add info text after the button
	$info = '<div class="gform-info">';
		$info .= '<i></i>';
		$info .= '<p>' . __( 'This helps us prepare for the conversation.', 'usmasmuiza' ) . '</p>';
	$info .= '</div>';

	return $custom_button . $info;
}
add_filter( 'gform_submit_button', 'usmasmuiza_gform_submit_button', 10, 2 );

/**
 * Expose each nav link's text as a data-text attribute, so CSS can reserve
 * the bold-weight width (a hidden bold "ghost") and avoid layout shift when
 * the link goes bold on hover.
 */
function usmasmuiza_nav_link_data_text( $atts, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary-menu' === $args->theme_location ) {
		$atts['data-text'] = wp_strip_all_tags( $item->title );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'usmasmuiza_nav_link_data_text', 10, 4 );

/**
 * Compact admin styling for the optional "Anchor" repeater (wrapper class
 * .acf-anchor-compact). Collapses it to a small "+ Anchor ID" button tucked
 * into the top-right corner so it doesn't take a full field block.
 */
function usmasmuiza_acf_anchor_admin_css() {
	?>
	<style>
		.acf-field.acf-anchor-compact { position: relative; padding: 8px 12px; }
		.acf-field.acf-anchor-compact > .acf-label { display: inline-block; margin: 0; }
		.acf-field.acf-anchor-compact > .acf-label label { font-size: 12px; font-weight: 500; color: #787c82; }
		/* Hide the empty repeater table; show the add button as a small corner pill. */
		.acf-field.acf-anchor-compact .acf-repeater.-empty > .acf-table { display: none; }
		.acf-field.acf-anchor-compact .acf-actions { position: absolute; top: 6px; right: 12px; margin: 0; padding: 0; text-align: right; }
		.acf-field.acf-anchor-compact .acf-actions .acf-button { min-height: 0; height: auto; padding: 2px 10px; font-size: 12px; line-height: 1.7; }
		/* When a row is added, keep it tidy (no drag handle / order column). */
		.acf-field.acf-anchor-compact .acf-row-handle.order { display: none; }
	</style>
	<?php
}
add_action( 'acf/input/admin_head', 'usmasmuiza_acf_anchor_admin_css' );
