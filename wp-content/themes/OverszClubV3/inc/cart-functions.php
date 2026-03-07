<?php
/**
 * Funciones del carrito.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_get_cart_drawer_markup() {
	ob_start();

	if ( ! overszclub_has_woocommerce() || ! WC()->cart || WC()->cart->is_empty() ) {
		?>
		<div class="cart-drawer__empty">
			<p><?php echo esc_html__( 'Tu carrito esta vacio.', 'overszclub' ); ?></p>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	echo '<div class="cart-drawer__items">';

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'];

		if ( ! $product || ! $product->exists() ) {
			continue;
		}
		?>
		<article class="cart-item">
			<div class="cart-item__image">
				<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
			</div>
			<div class="cart-item__content">
				<h3><?php echo esc_html( $product->get_name() ); ?></h3>
				<?php if ( ! empty( $cart_item['overszclub_size'] ) ) : ?>
					<p><?php echo esc_html( sprintf( __( 'Talla: %s', 'overszclub' ), $cart_item['overszclub_size'] ) ); ?></p>
				<?php endif; ?>
				<p><?php echo esc_html( sprintf( __( 'Cantidad: %d', 'overszclub' ), (int) $cart_item['quantity'] ) ); ?></p>
				<p class="cart-item__price"><?php echo wp_kses_post( WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ) ); ?></p>
			</div>
			<button class="cart-item__remove" type="button" data-cart-remove="<?php echo esc_attr( $cart_item_key ); ?>">
				<?php echo esc_html__( 'Eliminar', 'overszclub' ); ?>
			</button>
		</article>
		<?php
	}

	echo '</div>';
	?>
	<div class="cart-drawer__footer">
		<div class="cart-drawer__totals">
			<span><?php echo esc_html__( 'Subtotal', 'overszclub' ); ?></span>
			<strong><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></strong>
		</div>
		<a class="button button--primary" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<?php echo esc_html__( 'FINALIZAR COMPRA', 'overszclub' ); ?>
		</a>
	</div>
	<?php

	return (string) ob_get_clean();
}

function overszclub_cart_fragments( $fragments ) {
	if ( ! overszclub_has_woocommerce() || ! WC()->cart ) {
		return $fragments;
	}

	$fragments['[data-cart-count]']   = '<span class="cart-count" data-cart-count>' . absint( WC()->cart->get_cart_contents_count() ) . '</span>';
	$fragments['[data-cart-content]'] = '<div class="cart-drawer__content" data-cart-content>' . overszclub_get_cart_drawer_markup() . '</div>';

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'overszclub_cart_fragments' );

function overszclub_render_global_overlays() {
	?>
	<div class="site-preloader" data-preloader>
		<div class="site-preloader__mark">OVERSZCLUB</div>
	</div>

	<div class="cart-drawer" data-cart-drawer aria-hidden="true">
		<div class="cart-drawer__overlay" data-cart-close></div>
		<aside class="cart-drawer__panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Carrito lateral', 'overszclub' ); ?>" tabindex="-1">
			<div class="cart-drawer__header">
				<div>
					<p class="eyebrow"><?php echo esc_html__( 'Carrito', 'overszclub' ); ?></p>
					<h2><?php echo esc_html__( 'Tu seleccion', 'overszclub' ); ?></h2>
				</div>
				<button class="cart-drawer__close" type="button" data-cart-close aria-label="<?php echo esc_attr__( 'Cerrar', 'overszclub' ); ?>">
					<span></span>
					<span></span>
				</button>
			</div>
			<div class="cart-drawer__content" data-cart-content>
				<?php echo overszclub_get_cart_drawer_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</aside>
	</div>

	<div class="quickview-shell" data-quickview-shell aria-hidden="true"></div>
	<div class="screen-reader-response" aria-live="polite" data-live-region></div>
	<?php
}
add_action( 'wp_footer', 'overszclub_render_global_overlays' );
