<?php
/**
 * Section: Main Hero (homepage)
 *
 * Flexible content layout "main_hero" (field group: Page - Homepage).
 * Full-bleed background image with a dark overlay, a centered logo and a button.
 * Rendered inside the have_rows( 'sections' ) loop in templates/homepage.php.
 *
 * @package usmasmuiza
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$background = get_sub_field( 'background_image' );
$logo       = get_sub_field( 'logo' );
$button     = get_sub_field( 'button' );

$has_bg = is_array( $background ) && ! empty( $background['url'] );
$style  = $has_bg ? ' style="background-image:url(' . esc_url( $background['url'] ) . ');"' : '';
?>
<section class="section-main_hero<?php echo $has_bg ? ' has-bg' : ''; ?>"<?php echo $style; ?>>

	<div class="hero-inner">

		<?php if ( is_array( $logo ) && ! empty( $logo['url'] ) ) : ?>
			<img class="hero-logo" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $logo['alt'] ) ? $logo['alt'] : get_bloginfo( 'name' ) ); ?>" loading="eager" data-aos="fade-up">
		<?php endif; ?>

		<?php if ( $button && ! empty( $button['url'] ) ) : ?>
			<div class="hero-cta" data-aos="fade-up" data-aos-delay="100">
				<a class="btn" href="<?php echo esc_url( $button['url'] ); ?>"<?php echo ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '"' : ''; ?>>
					<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : __( 'Lasīt vairāk', 'usmasmuiza' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>

</section>
