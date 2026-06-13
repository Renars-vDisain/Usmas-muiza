<?php
/**
 * Section: Map (inner pages)
 *
 * Flexible content layout "map" — a heading + an embedded Google Map (iframe).
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$anchor_rows = get_sub_field( 'anchor' );
$anchor      = ( is_array( $anchor_rows ) && ! empty( $anchor_rows[0]['id'] ) ) ? sanitize_title( $anchor_rows[0]['id'] ) : '';
$heading     = get_sub_field( 'heading' );
$embed       = get_sub_field( 'embed' );

if ( ! $heading && ! $embed ) {
	return;
}

$id_attr = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

// Allow just the <iframe> from the pasted Google Maps embed.
$allowed_iframe = array(
	'iframe' => array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'style'           => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
		'loading'         => true,
		'referrerpolicy'  => true,
		'title'           => true,
	),
);

// Only render the iframe when its src points at Google Maps. This stops an
// editor (or a compromised account) from embedding an arbitrary third-party
// iframe through this field; anything else is dropped.
$embed_safe = '';
if ( $embed && preg_match( '/<iframe[^>]+src=["\']([^"\']+)["\']/i', $embed, $src_match ) ) {
	$host = strtolower( (string) wp_parse_url( $src_match[1], PHP_URL_HOST ) );
	if ( in_array( $host, array( 'www.google.com', 'google.com', 'maps.google.com' ), true ) ) {
		$embed_safe = wp_kses( $embed, $allowed_iframe );
	}
}
?>
<section class="section-map"<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if ( $heading ) : ?>
		<h2 class="map-title" data-aos="fade-up"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $embed_safe ) : ?>
		<div class="container map-embed" data-aos="fade-up" data-aos-delay="100">
			<?php echo $embed_safe; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized via wp_kses + host allow-list above ?>
		</div>
	<?php endif; ?>

</section>
