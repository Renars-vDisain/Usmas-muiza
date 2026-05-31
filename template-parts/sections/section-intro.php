<?php
/**
 * Section: Intro (homepage)
 *
 * Flexible content layout "intro" (field group: Page - Homepage).
 * Heading + body copy, with an optional "read more" button that reveals
 * additional text within the same section (no page reload / navigation).
 * Rendered inside the have_rows( 'sections' ) loop in templates/homepage.php.
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$title       = get_sub_field( 'title' );
$text        = get_sub_field( 'text' );
$text_more   = get_sub_field( 'text_more' );
$label_open  = get_sub_field( 'button_label' );
$label_close = get_sub_field( 'button_label_close' );

$has_more    = '' !== trim( wp_strip_all_tags( (string) $text_more ) );
$label_open  = $label_open  ? $label_open  : __( 'Lasīt vairāk', 'usmasmuiza' );
$label_close = $label_close ? $label_close : __( 'Aizvērt', 'usmasmuiza' );
$more_id     = wp_unique_id( 'introMore-' );
?>
<section class="section-intro">
	<div class="container intro-inner">

		<?php if ( $title ) : ?>
			<h2 class="intro-title" data-aos="fade-up"><?php echo wp_kses_post( nl2br( $title ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<div class="intro-text" data-aos="fade-up" data-aos-delay="100"><?php echo wp_kses_post( $text ); ?></div>
		<?php endif; ?>

		<?php if ( $has_more ) : ?>
			<div class="intro-more" id="<?php echo esc_attr( $more_id ); ?>">
				<div class="intro-more__inner intro-text"><?php echo wp_kses_post( $text_more ); ?></div>
			</div>

			<div class="intro-cta" data-aos="fade-up" data-aos-delay="200">
				<button class="btn btn--green intro-toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $more_id ); ?>" data-label-open="<?php echo esc_attr( $label_open ); ?>" data-label-close="<?php echo esc_attr( $label_close ); ?>">
					<?php echo esc_html( $label_open ); ?>
				</button>
			</div>
		<?php endif; ?>

	</div>
</section>
