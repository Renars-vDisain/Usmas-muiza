<?php
/**
 * Component: Blog Card
 *
 * @package headofsales
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title     = get_the_title();
$permalink = get_permalink();
$excerpt   = get_the_excerpt();
$thumbnail = get_the_post_thumbnail( get_the_ID(), 'large' );
$date      = get_the_date();

$out = '';
$out .= '<article class="blogcard">';
	$out .= '<a href="' . esc_url( $permalink ) . '" class="blogcard__link">';
		if ( $thumbnail ) {
			$out .= '<div class="blogcard__image">' . $thumbnail . '</div>';
		}
		$out .= '<div class="blogcard__content">';
			$out .= '<time class="blogcard__date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( $date ) . '</time>';
			$out .= '<h2 class="blogcard__title">' . esc_html( $title ) . '</h2>';
			if ( $excerpt ) {
				$out .= '<p class="blogcard__excerpt">' . esc_html( $excerpt ) . '</p>';
			}
		$out .= '</div>';
	$out .= '</a>';
$out .= '</article>';

echo $out;
