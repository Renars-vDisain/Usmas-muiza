<?php
/**
 * Template part for displaying posts (generic fallback)
 *
 * @package headofsales
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$out = '';
$out .= '<article id="post-' . esc_attr( get_the_ID() ) . '" class="' . esc_attr( implode( ' ', get_post_class() ) ) . '">';
	$out .= '<header class="entry-header">';
		$out .= '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
	$out .= '</header>';
	$out .= '<div class="entry-content">';
		$out .= wp_kses_post( get_the_excerpt() );
	$out .= '</div>';
$out .= '</article>';

echo $out;
