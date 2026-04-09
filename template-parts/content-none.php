<?php
/**
 * Template part for displaying a message when no posts are found
 *
 * @package headofsales
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$out = '';
$out .= '<section class="no-results">';
	$out .= '<div class="container">';
		$out .= '<h1>' . esc_html__( 'Nekas nav atrasts', 'headofsales' ) . '</h1>';
		$out .= '<p>' . esc_html__( 'Diemžēl nekas neatbilst jūsu meklēšanas kritērijiem.', 'headofsales' ) . '</p>';
	$out .= '</div>';
$out .= '</section>';

echo $out;
