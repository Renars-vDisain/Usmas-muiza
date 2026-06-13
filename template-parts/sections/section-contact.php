<?php
/**
 * Section: Contact + Form (inner pages)
 *
 * Flexible content layout "contact" — contact details on the left and a
 * Gravity Form on the right.
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$anchor_rows = get_sub_field( 'anchor' );
$anchor      = ( is_array( $anchor_rows ) && ! empty( $anchor_rows[0]['id'] ) ) ? sanitize_title( $anchor_rows[0]['id'] ) : '';
$heading     = get_sub_field( 'heading' );
$lead        = get_sub_field( 'lead_details' );
$blocks      = get_sub_field( 'blocks' );
$form_id     = trim( (string) get_sub_field( 'form_id' ) );

$id_attr = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';
?>
<section class="section-contact"<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container contact-inner">

		<div class="contact-info" data-aos="fade-up">
			<?php if ( $heading || $lead ) : ?>
				<div class="contact-block">
					<?php if ( $heading ) : ?>
						<h2 class="contact-title"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $lead ) : ?>
						<div class="contact-details"><?php echo wp_kses_post( $lead ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			if ( is_array( $blocks ) ) :
				foreach ( $blocks as $block ) :
					if ( empty( $block['title'] ) && empty( $block['details'] ) ) {
						continue;
					}
					?>
					<div class="contact-block">
						<?php if ( ! empty( $block['title'] ) ) : ?>
							<p class="contact-subtitle"><?php echo esc_html( $block['title'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $block['details'] ) ) : ?>
							<div class="contact-details"><?php echo wp_kses_post( $block['details'] ); ?></div>
						<?php endif; ?>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>

		<?php if ( $form_id ) : ?>
			<div class="contact-form gf-styled" data-aos="fade-up" data-aos-delay="100">
				<?php echo do_shortcode( '[gravityform id="' . absint( $form_id ) . '" title="false" description="false" ajax="true"]' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
