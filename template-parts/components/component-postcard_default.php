<?php
/**
 * Component: Project Postcard Default
 *
 * @package headofsales
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = $args['post_id'] ?? get_the_ID();
$title     = get_the_title( $post_id );
$permalink = get_permalink( $post_id );
$thumbnail = get_the_post_thumbnail( $post_id, 'large' );
$tags      = get_the_tags( $post_id );

$out = '';
$out .= '<a href="' . esc_url( $permalink ) . '" class="postcard-default">';
	if ( $thumbnail ) {
		$out .= '<div class="postcard-default__image">' . $thumbnail . '</div>';
	}
	$out .= '<div class="postcard-default__content">';
		$out .= '<h3 class="postcard-default__title">' . esc_html( $title ) . '</h3>';
		if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
			$out .= '<div class="postcard-default__tags">';
			foreach ( $tags as $tag ) {
				$out .= '<span class="postcard-default__tag">' . esc_html( $tag->name ) . '</span>';
			}
			$out .= '</div>';
		}
	$out .= '</div>';
$out .= '</a>';

echo $out;
