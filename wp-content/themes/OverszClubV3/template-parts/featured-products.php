<?php
/**
 * Productos curados para la portada.
 *
 * @package OverszClub
 */

$featured_product_ids = overszclub_get_home_featured_product_ids();

if ( empty( $featured_product_ids ) ) {
	return;
}
?>
<section class="section section--featured-curated container" data-reveal>
	<div class="section-heading">
		<p class="eyebrow"><?php echo esc_html__( 'Destacados', 'overszclub' ); ?></p>
		<h2><?php echo esc_html__( 'Piezas clave de la temporada', 'overszclub' ); ?></h2>
	</div>
	<?php
	echo overszclub_render_products_grid(
		array(
			'posts_per_page' => count( $featured_product_ids ),
			'post__in'       => $featured_product_ids,
			'orderby'        => 'post__in',
		)
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</section>
