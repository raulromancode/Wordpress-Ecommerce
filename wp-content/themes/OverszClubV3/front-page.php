<?php
/**
 * Portada.
 *
 * @package OverszClub
 */

get_header();
?>
<main class="front-page">
	<?php get_template_part( 'template-parts/hero' ); ?>
	<?php get_template_part( 'template-parts/marquee' ); ?>
	<?php get_template_part( 'template-parts/featured-products' ); ?>
	<section class="section section--lookbook container" data-reveal>
		<div class="section-heading">
			<p class="eyebrow"><?php echo esc_html__( 'Editorial', 'overszclub' ); ?></p>
			<h2><?php echo esc_html( overszclub_get_theme_mod( 'lookbook_heading' ) ); ?></h2>
			<p><?php echo esc_html( overszclub_get_theme_mod( 'lookbook_intro' ) ); ?></p>
		</div>
		<?php get_template_part( 'template-parts/lookbook-grid' ); ?>
	</section>
	<?php get_template_part( 'template-parts/lookbook-products' ); ?>
	<section class="section section--about container" data-reveal>
		<div class="section-heading">
			<p class="eyebrow"><?php echo esc_html__( 'Manifiesto', 'overszclub' ); ?></p>
			<h2><?php echo esc_html__( 'Diseño sobrio, actitud radical.', 'overszclub' ); ?></h2>
		</div>
		<div class="about-panel">
			<p><?php echo esc_html( overszclub_get_theme_mod( 'about_content' ) ); ?></p>
			<a class="button button--secondary" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
				<?php echo esc_html__( 'Conocer la marca', 'overszclub' ); ?>
			</a>
		</div>
	</section>
</main>
<?php
get_footer();
