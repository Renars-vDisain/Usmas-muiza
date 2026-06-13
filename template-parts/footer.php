<?php
/**
 * The template for displaying the site footer.
 *
 * Content from Site Settings (ACF options): site_logo_white, footer_columns
 * (title + links), footer_newsletter_title/form_id, footer_copyright,
 * footer_privacy_link, footer_vector.
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$logo       = get_field( 'site_logo_white', 'option' );
$news_form  = get_field( 'footer_newsletter_form_id', 'option' );
$copyright  = get_field( 'footer_copyright', 'option' );
$terms      = get_field( 'footer_terms_link', 'option' );
$privacy    = get_field( 'footer_privacy_link', 'option' );

$out = '';

$out .= '<div id="contact-us"></div>';
$out .= '<footer id="siteFooter">';

	$out .= '<div class="container footer-inner">';

		// Brand
		$out .= '<a href="' . esc_url( home_url( '/' ) ) . '" class="footer-brand">';
			if ( is_array( $logo ) && ! empty( $logo['url'] ) ) {
				$out .= '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			}
		$out .= '</a>';

		// Link columns
		if ( have_rows( 'footer_columns', 'option' ) ) {
			while ( have_rows( 'footer_columns', 'option' ) ) {
				the_row();
				$col_title = get_sub_field( 'title' );

				$out .= '<div class="footer-col">';
					if ( $col_title ) {
						$out .= '<h4 class="footer-col__title">' . esc_html( $col_title ) . '</h4>';
					}
					if ( have_rows( 'links' ) ) {
						$out .= '<ul class="footer-links">';
						while ( have_rows( 'links' ) ) {
							the_row();
							$link = get_sub_field( 'link' );
							if ( ! is_array( $link ) || empty( $link['title'] ) ) {
								continue;
							}
							if ( get_sub_field( 'is_subtitle' ) ) {
								// Bold sub-heading within the column (e.g. "Spa procedūru speciālists").
								$out .= '<li class="footer-links__subtitle">' . esc_html( $link['title'] ) . '</li>';
							} elseif ( ! empty( $link['url'] ) ) {
								$target = ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : '';
								$out   .= '<li><a href="' . esc_url( $link['url'] ) . '"' . $target . '>' . esc_html( $link['title'] ) . '</a></li>';
							} else {
								$out .= '<li><span>' . esc_html( $link['title'] ) . '</span></li>';
							}
						}
						$out .= '</ul>';
					}
				$out .= '</div>';
			}
		}

		// Newsletter
		$socials = usmasmuiza_social_icons();

		if ( $news_form || $socials ) {
			$out .= '<div class="footer-col footer-newsletter">';

				if ( $news_form ) {
					// Gravity Forms supplies its own title (the field label).
					$out .= do_shortcode( '[gravityform id="' . absint( $news_form ) . '" title="false" description="false" ajax="true"]' );
				}

				if ( $socials ) {
					$out .= '<div class="footer-socials">' . $socials . '</div>';
				}

			$out .= '</div>';
		}

	$out .= '</div>';

	// Bottom bar — copyright + legal links (terms, privacy).
	$legal = '';
	foreach ( array( $terms, $privacy ) as $legal_link ) {
		if ( is_array( $legal_link ) && ! empty( $legal_link['url'] ) ) {
			$target = ! empty( $legal_link['target'] ) ? ' target="' . esc_attr( $legal_link['target'] ) . '"' : '';
			$legal .= '<a href="' . esc_url( $legal_link['url'] ) . '"' . $target . '>' . esc_html( $legal_link['title'] ) . '</a>';
		}
	}

	if ( $copyright || $legal ) {
		$out .= '<div class="container footer-bottom">';
			if ( $copyright ) {
				$out .= '<p class="footer-copyright">' . esc_html( $copyright ) . '</p>';
			}
			if ( $legal ) {
				$out .= '<div class="footer-legal">' . $legal . '</div>';
			}
		$out .= '</div>';
	}

$out .= '</footer>';

echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above.
