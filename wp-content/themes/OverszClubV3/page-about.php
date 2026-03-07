<?php
/**
 * Página sobre la marca.
 *
 * @package OverszClub
 */

get_header();
?>
<main class="about-page container">
	<section class="section-heading section-heading--page">
		<p class="eyebrow"><?php echo esc_html__( 'Sobre OverszClub', 'overszclub' ); ?></p>
		<h1><?php echo esc_html__( 'Streetwear de direccion editorial.', 'overszclub' ); ?></h1>
	</section>
	<div class="about-panel about-panel--page">
		<p><?php echo esc_html( overszclub_get_theme_mod( 'about_content' ) ); ?></p>
	</div>
</main>
<?php
get_footer();
