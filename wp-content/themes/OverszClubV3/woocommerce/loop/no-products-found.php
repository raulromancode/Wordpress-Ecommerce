<?php
/**
 * Estado vacio de productos.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="overszclub-empty-state overszclub-empty-state--shop">
	<p class="eyebrow"><?php esc_html_e( 'Sin resultados', 'overszclub' ); ?></p>
	<h2><?php esc_html_e( 'No hay productos para esta seleccion', 'overszclub' ); ?></h2>
	<p><?php esc_html_e( 'Prueba con otra categoria, ajusta el rango de precio o vuelve a la coleccion completa.', 'overszclub' ); ?></p>
	<a class="button button--secondary" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' ) ); ?>">
		<?php esc_html_e( 'Volver a la tienda', 'overszclub' ); ?>
	</a>
</section>
