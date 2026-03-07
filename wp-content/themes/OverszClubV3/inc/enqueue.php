<?php
/**
 * Registro de assets.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_enqueue_assets() {
	$version = overszclub_get_theme_version();

	wp_enqueue_style(
		'overszclub-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'overszclub-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'overszclub-fonts' ),
		$version
	);

	wp_enqueue_style(
		'overszclub-style',
		get_stylesheet_uri(),
		array( 'overszclub-main' ),
		$version
	);

	$inline_css = ':root{--color-accent:' . esc_attr( overszclub_get_theme_mod( 'theme_accent_color' ) ) . ';--color-bg:' . esc_attr( overszclub_get_theme_mod( 'theme_background_color' ) ) . ';--color-surface:' . esc_attr( overszclub_get_theme_mod( 'theme_surface_color' ) ) . ';--color-text:' . esc_attr( overszclub_get_theme_mod( 'theme_text_color' ) ) . ';--overszclub-shop-columns:' . absint( overszclub_get_catalog_columns() ) . ';}';
	wp_add_inline_style( 'overszclub-main', $inline_css );

	$scripts = array( 'animations', 'cart', 'marquee', 'filters', 'quickview' );

	foreach ( $scripts as $script ) {
		wp_enqueue_script(
			'overszclub-' . $script,
			get_template_directory_uri() . '/assets/js/' . $script . '.js',
			array(),
			$version,
			true
		);
	}

	$data = array(
		'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
		'nonce'       => wp_create_nonce( 'overszclub_nonce' ),
		'cartUrl'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/carrito/' ),
		'checkoutUrl' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/finalizar-compra/' ),
		'shopUrl'     => overszclub_has_woocommerce() ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/tienda/' ),
		'i18n'        => array(
			'added'   => esc_html__( 'Producto anadido al carrito.', 'overszclub' ),
			'error'   => esc_html__( 'No ha sido posible completar la accion.', 'overszclub' ),
			'loading' => esc_html__( 'Cargando...', 'overszclub' ),
			'selectSize' => esc_html__( 'Selecciona una talla.', 'overszclub' ),
		),
	);

	foreach ( array( 'overszclub-cart', 'overszclub-filters', 'overszclub-quickview', 'overszclub-animations' ) as $handle ) {
		wp_localize_script( $handle, 'OverszClubData', $data );
	}
}
add_action( 'wp_enqueue_scripts', 'overszclub_enqueue_assets' );
