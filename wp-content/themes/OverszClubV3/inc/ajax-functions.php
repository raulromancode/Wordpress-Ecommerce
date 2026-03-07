<?php
/**
 * Endpoints AJAX.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_verify_ajax_nonce() {
	check_ajax_referer( 'overszclub_nonce', 'nonce' );
}

function overszclub_ajax_add_to_cart() {
	overszclub_verify_ajax_nonce();

	if ( ! overszclub_has_woocommerce() || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => esc_html__( 'El carrito no esta disponible.', 'overszclub' ) ), 400 );
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$quantity   = isset( $_POST['quantity'] ) ? max( 1, absint( wp_unslash( $_POST['quantity'] ) ) ) : 1;
	$size       = isset( $_POST['size'] ) ? sanitize_text_field( wp_unslash( $_POST['size'] ) ) : '';
	$product    = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Producto no valido.', 'overszclub' ) ), 404 );
	}

	$variation_id = overszclub_find_matching_variation_id( $product, $size );
	$variations   = overszclub_get_variation_attributes_by_size( $product, $size );

	if ( overszclub_product_requires_size( $product ) && ! $size ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Selecciona una talla.', 'overszclub' ) ), 400 );
	}

	if ( $product->is_type( 'variable' ) && ! $variation_id ) {
		wp_send_json_error( array( 'message' => esc_html__( 'La talla seleccionada no esta disponible.', 'overszclub' ) ), 400 );
	}

	$added = WC()->cart->add_to_cart(
		$product_id,
		$quantity,
		$variation_id,
		$variations,
		array(
			'overszclub_size' => $size,
		)
	);

	if ( ! $added ) {
		wp_send_json_error( array( 'message' => esc_html__( 'No se ha podido anadir al carrito.', 'overszclub' ) ), 400 );
	}

	wp_send_json_success(
		array(
			'message'    => esc_html__( 'Producto anadido al carrito.', 'overszclub' ),
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'cart_html'  => overszclub_get_cart_drawer_markup(),
		)
	);
}
add_action( 'wp_ajax_overszclub_add_to_cart', 'overszclub_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_overszclub_add_to_cart', 'overszclub_ajax_add_to_cart' );

function overszclub_ajax_remove_from_cart() {
	overszclub_verify_ajax_nonce();

	if ( ! overszclub_has_woocommerce() || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => esc_html__( 'El carrito no esta disponible.', 'overszclub' ) ), 400 );
	}

	$cart_key = isset( $_POST['cart_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_key'] ) ) : '';

	if ( $cart_key ) {
		WC()->cart->remove_cart_item( $cart_key );
	}

	wp_send_json_success(
		array(
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'cart_html'  => overszclub_get_cart_drawer_markup(),
		)
	);
}
add_action( 'wp_ajax_overszclub_remove_from_cart', 'overszclub_ajax_remove_from_cart' );
add_action( 'wp_ajax_nopriv_overszclub_remove_from_cart', 'overszclub_ajax_remove_from_cart' );

function overszclub_ajax_quickview() {
	overszclub_verify_ajax_nonce();

	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$product    = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Producto no encontrado.', 'overszclub' ) ), 404 );
	}

	ob_start();
	get_template_part( 'template-parts/quickview-modal', null, array( 'product_id' => $product_id ) );
	$html = (string) ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_overszclub_quickview', 'overszclub_ajax_quickview' );
add_action( 'wp_ajax_nopriv_overszclub_quickview', 'overszclub_ajax_quickview' );

function overszclub_ajax_filter_products() {
	overszclub_verify_ajax_nonce();

	$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
	$size     = isset( $_POST['size'] ) ? sanitize_text_field( wp_unslash( $_POST['size'] ) ) : '';
	$min      = isset( $_POST['min_price'] ) ? floatval( wp_unslash( $_POST['min_price'] ) ) : 0;
	$max      = isset( $_POST['max_price'] ) ? floatval( wp_unslash( $_POST['max_price'] ) ) : 0;

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'tax_query'      => array(),
		'meta_query'     => array(),
	);

	if ( $category ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	if ( $size ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'pa_size',
			'field'    => 'slug',
			'terms'    => sanitize_title( $size ),
		);
	}

	if ( $min || $max ) {
		$args['meta_query'][] = array(
			'key'     => '_price',
			'value'   => array( $min, $max ? $max : 999999 ),
			'compare' => 'BETWEEN',
			'type'    => 'NUMERIC',
		);
	}

	if ( count( $args['tax_query'] ) > 1 ) {
		$args['tax_query']['relation'] = 'AND';
	}

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		echo '<div class="products-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/product-card', null, array( 'product_id' => get_the_ID() ) );
		}

		echo '</div>';
	} else {
		echo '<p class="overszclub-empty-state">' . esc_html__( 'No hay resultados con esos filtros.', 'overszclub' ) . '</p>';
	}

	$html = (string) ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'  => $html,
			'count' => absint( $query->found_posts ),
		)
	);
}
add_action( 'wp_ajax_overszclub_filter_products', 'overszclub_ajax_filter_products' );
add_action( 'wp_ajax_nopriv_overszclub_filter_products', 'overszclub_ajax_filter_products' );
