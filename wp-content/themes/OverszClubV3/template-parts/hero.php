<?php
/**
 * Hero de portada.
 *
 * @package OverszClub
 */

$hero_image_id = absint( get_theme_mod( 'hero_image' ) );
$hero_image    = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
?>
<section class="hero" style="<?php echo $hero_image ? esc_attr( '--hero-image:url(' . esc_url_raw( $hero_image ) . ');' ) : ''; ?>">
	<div class="hero__overlay"></div>
	<div class="hero__content container" data-reveal>
		<p class="eyebrow"><?php echo esc_html__( 'Nueva temporada', 'overszclub' ); ?></p>
		<h1><?php echo esc_html( overszclub_get_theme_mod( 'hero_title' ) ); ?></h1>
		<a class="button button--primary" href="<?php echo esc_url( overszclub_get_theme_mod( 'hero_button_url' ) ); ?>">
			<?php echo esc_html( overszclub_get_theme_mod( 'hero_button_text' ) ); ?>
		</a>
	</div>
</section>
