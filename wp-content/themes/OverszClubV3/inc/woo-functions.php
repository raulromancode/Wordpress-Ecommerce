<?php
/**
 * Funciones WooCommerce.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_get_woocommerce_catalog_setting( $key, $default = '' ) {
	$theme_mod = get_theme_mod( $key, null );

	if ( null !== $theme_mod && '' !== $theme_mod ) {
		return $theme_mod;
	}

	$option = get_option( $key, null );

	if ( null !== $option && '' !== $option ) {
		return $option;
	}

	return $default;
}

function overszclub_get_catalog_columns() {
	$columns = absint( overszclub_get_woocommerce_catalog_setting( 'woocommerce_catalog_columns', 3 ) );

	if ( $columns < 1 ) {
		$columns = 3;
	}

	return min( 6, $columns );
}

function overszclub_get_shop_display_mode() {
	$default = is_product_taxonomy() ? 'products' : 'subcategories';
	$key     = is_product_taxonomy() ? 'woocommerce_category_archive_display' : 'woocommerce_shop_page_display';
	$mode    = (string) overszclub_get_woocommerce_catalog_setting( $key, $default );

	if ( 'both' === $mode || 'subcategories' === $mode || 'products' === $mode ) {
		return $mode;
	}

	return $default;
}

function overszclub_get_product_sizes( $product ) {
	$sizes = array( 'S', 'M', 'L', 'XL' );

	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return $sizes;
	}

	if ( $product->is_type( 'variable' ) ) {
		$variation_attributes = $product->get_variation_attributes();

		if ( ! empty( $variation_attributes['attribute_pa_size'] ) ) {
			return array_values( array_filter( array_map( 'wc_clean', $variation_attributes['attribute_pa_size'] ) ) );
		}
	}

	$terms = wc_get_product_terms( $product->get_id(), 'pa_size', array( 'fields' => 'names' ) );

	return ! empty( $terms ) ? array_values( $terms ) : $sizes;
}

function overszclub_product_requires_size( $product ) {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return false;
	}

	if ( $product->is_type( 'variable' ) ) {
		$variation_attributes = $product->get_variation_attributes();
		return ! empty( $variation_attributes['attribute_pa_size'] );
	}

	$terms = wc_get_product_terms( $product->get_id(), 'pa_size', array( 'fields' => 'ids' ) );

	return ! empty( $terms );
}

function overszclub_get_variation_attributes_by_size( $product, $size ) {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product || ! $product->is_type( 'variable' ) ) {
		return array();
	}

	foreach ( $product->get_available_variations() as $variation ) {
		if ( empty( $variation['attributes'] ) ) {
			continue;
		}

		foreach ( $variation['attributes'] as $attribute_key => $attribute_value ) {
			if ( false !== strpos( $attribute_key, 'size' ) && sanitize_title( $size ) === sanitize_title( $attribute_value ) ) {
				return $variation['attributes'];
			}
		}
	}

	return array();
}

function overszclub_find_matching_variation_id( $product, $size ) {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product || ! $product->is_type( 'variable' ) ) {
		return 0;
	}

	foreach ( $product->get_available_variations() as $variation ) {
		if ( empty( $variation['attributes'] ) ) {
			continue;
		}

		foreach ( $variation['attributes'] as $attribute_key => $attribute_value ) {
			if ( false !== strpos( $attribute_key, 'size' ) && sanitize_title( $size ) === sanitize_title( $attribute_value ) ) {
				return absint( $variation['variation_id'] );
			}
		}
	}

	return 0;
}

function overszclub_render_products_grid( $args = array() ) {
	if ( ! overszclub_has_woocommerce() ) {
		return '<p class="overszclub-empty-state">' . esc_html__( 'WooCommerce no esta activo.', 'overszclub' ) . '</p>';
	}

	$defaults = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
	);

	$query = new WP_Query( wp_parse_args( $args, $defaults ) );

	ob_start();

	if ( $query->have_posts() ) {
		echo '<div class="products-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/product-card', null, array( 'product_id' => get_the_ID() ) );
		}

		echo '</div>';
	} else {
		echo '<p class="overszclub-empty-state">' . esc_html__( 'No se han encontrado productos.', 'overszclub' ) . '</p>';
	}

	wp_reset_postdata();

	return (string) ob_get_clean();
}

function overszclub_render_featured_products() {
	return overszclub_render_products_grid(
		array(
			'posts_per_page' => 6,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'featured' ),
				),
			),
		)
	);
}

function overszclub_get_home_featured_product_ids() {
	$product_ids = array();

	for ( $i = 1; $i <= 6; $i++ ) {
		$product_id = absint( get_theme_mod( 'featured_product_' . $i, 0 ) );

		if ( $product_id ) {
			$product_ids[] = $product_id;
		}
	}

	$product_ids = array_values( array_unique( array_filter( $product_ids ) ) );

	if ( ! empty( $product_ids ) ) {
		return $product_ids;
	}

	$fallback_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'featured' ),
				),
			),
			'fields'         => 'ids',
		)
	);

	$fallback_ids = $fallback_query->posts;
	wp_reset_postdata();

	return array_values( array_unique( array_filter( array_map( 'absint', $fallback_ids ) ) ) );
}

function overszclub_get_shop_category_ids() {
	$mode         = overszclub_get_theme_mod( 'shop_categories_mode' );
	$limit        = absint( overszclub_get_theme_mod( 'shop_categories_limit' ) );
	$limit        = $limit > 0 ? $limit : 6;
	$category_ids = array();

	if ( 'curated' === $mode ) {
		for ( $i = 1; $i <= 6; $i++ ) {
			$category_id = absint( get_theme_mod( 'shop_category_' . $i, 0 ) );

			if ( $category_id ) {
				$category_ids[] = $category_id;
			}
		}

		$category_ids = array_values( array_unique( array_filter( $category_ids ) ) );

		if ( ! empty( $category_ids ) ) {
			return $category_ids;
		}
	}

	$fallback_terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
			'number'     => $limit,
			'fields'     => 'ids',
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $fallback_terms ) ) {
		return array();
	}

	return array_values( array_unique( array_filter( array_map( 'absint', $fallback_terms ) ) ) );
}

function overszclub_get_product_primary_category( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}

	return $terms[0]->name;
}

function overszclub_get_product_badges( $product ) {
	$badges = array();

	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return $badges;
	}

	if ( $product->is_on_sale() ) {
		$badges[] = __( 'Oferta', 'overszclub' );
	}

	if ( $product->is_featured() ) {
		$badges[] = __( 'Destacado', 'overszclub' );
	}

	if ( ! $product->is_in_stock() ) {
		$badges[] = __( 'Agotado', 'overszclub' );
	}

	return array_unique( $badges );
}

function overszclub_get_product_price_text( $product ) {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return '';
	}

	if ( $product->is_type( 'variable' ) ) {
		$prices = $product->get_variation_prices( true );

		if ( ! empty( $prices['price'] ) ) {
			$min_price = current( $prices['price'] );
			return sprintf( __( 'Desde %s', 'overszclub' ), wc_price( $min_price ) );
		}
	}

	return $product->get_price_html();
}

function overszclub_get_lookbook_product_data( $product_id ) {
	$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;

	if ( ! $product ) {
		return array();
	}

	return array(
		'id'       => $product->get_id(),
		'name'     => $product->get_name(),
		'price'    => wp_strip_all_tags( overszclub_get_product_price_text( $product ) ),
		'category' => overszclub_get_product_primary_category( $product->get_id() ),
		'url'      => get_permalink( $product->get_id() ),
		'image'    => wp_get_attachment_image_url( $product->get_image_id(), 'medium_large' ),
	);
}

function overszclub_get_lookbook_product_ids() {
	$product_ids = array();

	for ( $i = 1; $i <= 4; $i++ ) {
		foreach ( overszclub_get_lookbook_hotspots( $i ) as $hotspot ) {
			if ( ! empty( $hotspot['product_id'] ) ) {
				$product_ids[] = absint( $hotspot['product_id'] );
			}
		}
	}

	return array_values( array_unique( array_filter( $product_ids ) ) );
}

function overszclub_customize_woocommerce_hooks() {
	if ( ! overszclub_has_woocommerce() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'wp', 'overszclub_customize_woocommerce_hooks' );

function overszclub_loop_shop_columns() {
	return overszclub_get_catalog_columns();
}
add_filter( 'loop_shop_columns', 'overszclub_loop_shop_columns' );

function overszclub_woocommerce_breadcrumb() {
	if ( ! function_exists( 'woocommerce_breadcrumb' ) || is_front_page() || is_cart() || is_checkout() || is_account_page() ) {
		return;
	}
	?>
	<div class="overszclub-breadcrumb container">
		<?php woocommerce_breadcrumb(); ?>
	</div>
	<?php
}
add_action( 'woocommerce_before_main_content', 'overszclub_woocommerce_breadcrumb', 5 );

function overszclub_woocommerce_empty_cart_message() {
	return __( 'Tu carrito esta vacio. Explora la coleccion y anade tus piezas favoritas.', 'overszclub' );
}
add_filter( 'wc_empty_cart_message', 'overszclub_woocommerce_empty_cart_message' );

function overszclub_account_menu_items( $items ) {
	$labels = array(
		'dashboard'       => __( 'Resumen', 'overszclub' ),
		'orders'          => __( 'Pedidos', 'overszclub' ),
		'downloads'       => __( 'Descargas', 'overszclub' ),
		'edit-address'    => __( 'Direcciones', 'overszclub' ),
		'payment-methods' => __( 'Pagos', 'overszclub' ),
		'edit-account'    => __( 'Perfil', 'overszclub' ),
		'customer-logout' => __( 'Cerrar sesion', 'overszclub' ),
	);

	foreach ( $items as $key => $label ) {
		if ( isset( $labels[ $key ] ) ) {
			$items[ $key ] = $labels[ $key ];
		}
	}

	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'overszclub_account_menu_items' );

function overszclub_add_cart_item_size_data( $cart_item_data, $product_id, $variation_id ) {
	$posted_size = '';

	if ( isset( $_POST['overszclub_size'] ) ) {
		$posted_size = sanitize_text_field( wp_unslash( $_POST['overszclub_size'] ) );
	} elseif ( isset( $_POST['size'] ) ) {
		$posted_size = sanitize_text_field( wp_unslash( $_POST['size'] ) );
	}

	if ( $posted_size ) {
		$cart_item_data['overszclub_size'] = $posted_size;
		$cart_item_data['unique_key']      = md5( wp_json_encode( $cart_item_data ) . microtime() );
	}

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'overszclub_add_cart_item_size_data', 10, 3 );

function overszclub_display_cart_item_size( $item_data, $cart_item ) {
	if ( ! empty( $cart_item['overszclub_size'] ) ) {
		$item_data[] = array(
			'key'   => esc_html__( 'Talla', 'overszclub' ),
			'value' => wc_clean( $cart_item['overszclub_size'] ),
		);
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'overszclub_display_cart_item_size', 10, 2 );

function overszclub_validate_add_to_cart( $passed, $product_id, $quantity, $variation_id = 0 ) {
	$product = wc_get_product( $variation_id ? $variation_id : $product_id );

	if ( ! $product ) {
		return $passed;
	}

	$parent_product = $variation_id ? wc_get_product( $product_id ) : $product;
	$requires_size  = overszclub_product_requires_size( $parent_product );
	$posted_size    = isset( $_REQUEST['size'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['size'] ) ) : ( isset( $_REQUEST['overszclub_size'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['overszclub_size'] ) ) : '' );

	if ( $requires_size && ! $posted_size ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'Selecciona una talla antes de anadir el producto.', 'overszclub' ), 'error' );
		}
		return false;
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'overszclub_validate_add_to_cart', 10, 4 );
