<?php
/**
 * Productos asociados al lookbook.
 *
 * @package OverszClub
 */

$lookbook_product_ids = overszclub_get_lookbook_product_ids();

if ( empty( $lookbook_product_ids ) ) {
	return;
}
?>
<section class="lookbook-products container" data-reveal>
	<div class="section-heading section-heading--compact">
		<p class="eyebrow"><?php echo esc_html__( 'Shop the look', 'overszclub' ); ?></p>
		<h2><?php echo esc_html__( 'Productos del lookbook', 'overszclub' ); ?></h2>
	</div>
	<?php
	echo overszclub_render_products_grid(
		array(
			'posts_per_page' => count( $lookbook_product_ids ),
			'post__in'       => $lookbook_product_ids,
			'orderby'        => 'post__in',
		)
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</section>
