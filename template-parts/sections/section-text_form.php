<?php
/**
 * Section: Text + Form (inner pages)
 *
 * Flexible content layout "text_form" — a heading + subtitle + text block on the
 * left and a Gravity Form on the right (e.g. the gift card section).
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$anchor_rows = get_sub_field( 'anchor' );
$anchor      = ( is_array( $anchor_rows ) && ! empty( $anchor_rows[0]['id'] ) ) ? sanitize_title( $anchor_rows[0]['id'] ) : '';
$heading     = get_sub_field( 'heading' );
$subtitle    = get_sub_field( 'subtitle' );
$text        = get_sub_field( 'text' );
$form_id     = trim( (string) get_sub_field( 'form_id' ) );

$id_attr = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';
?>
<section class="section-text-form"<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container tf-inner">

		<div class="tf-text" data-aos="fade-up">
			<?php if ( $heading ) : ?>
				<h2 class="tf-title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ) : ?>
				<p class="tf-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<div class="tf-body"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( $form_id ) : ?>
			<div class="tf-form gf-styled" data-aos="fade-up" data-aos-delay="100">
				<?php echo do_shortcode( '[gravityform id="' . absint( $form_id ) . '" title="false" description="false" ajax="true"]' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
