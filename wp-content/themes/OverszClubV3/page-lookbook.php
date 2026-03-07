<?php
/**
 * Página lookbook.
 *
 * @package OverszClub
 */

get_header();
?>
<main class="lookbook-page container">
	<header class="section-heading section-heading--page">
		<p class="eyebrow"><?php echo esc_html__( 'Lookbook', 'overszclub' ); ?></p>
		<h1><?php echo esc_html( overszclub_get_theme_mod( 'lookbook_heading' ) ); ?></h1>
		<p><?php echo esc_html( overszclub_get_theme_mod( 'lookbook_intro' ) ); ?></p>
	</header>
	<?php get_template_part( 'template-parts/lookbook-grid' ); ?>
</main>
<?php
get_footer();
