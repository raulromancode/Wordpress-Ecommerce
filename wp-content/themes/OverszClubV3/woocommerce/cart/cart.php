<?php
/**
 * Carrito personalizado.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>
<div class="overszclub-cart-page">
	<form class="woocommerce-cart-form overszclub-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Eliminar', 'overszclub' ); ?></span></th>
					<th class="product-thumbnail"><?php esc_html_e( 'Producto', 'overszclub' ); ?></th>
					<th class="product-name"><?php esc_html_e( 'Detalles', 'overszclub' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Precio', 'overszclub' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Cantidad', 'overszclub' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'overszclub' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
					<?php
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || 0 >= $cart_item['quantity'] || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						continue;
					}

					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-remove">
							<?php
							echo wp_kses(
								apply_filters(
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Eliminar este producto', 'overszclub' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								),
								array(
									'a' => array(
										'href'            => array(),
										'class'           => array(),
										'aria-label'      => array(),
										'data-product_id' => array(),
										'data-product_sku'=> array(),
									),
								)
							);
							?>
						</td>
						<td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</td>
						<td class="product-name" data-title="<?php esc_attr_e( 'Producto', 'overszclub' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( $_product->get_name() );
							} else {
								echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
							}

							if ( ! empty( $cart_item['overszclub_size'] ) ) {
								echo '<p class="overszclub-cart-meta">' . esc_html( sprintf( __( 'Talla: %s', 'overszclub' ), $cart_item['overszclub_size'] ) ) . '</p>';
							}

							echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</td>
						<td class="product-price" data-title="<?php esc_attr_e( 'Precio', 'overszclub' ); ?>">
							<?php echo wp_kses_post( WC()->cart->get_product_price( $_product ) ); ?>
						</td>
						<td class="product-quantity" data-title="<?php esc_attr_e( 'Cantidad', 'overszclub' ); ?>">
							<?php
							if ( $_product->is_sold_individually() ) {
								$min_quantity = 1;
								$max_quantity = 1;
							} else {
								$min_quantity = 0;
								$max_quantity = $_product->get_max_purchase_quantity();
							}

							echo woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $max_quantity,
									'min_value'    => $min_quantity,
									'product_name' => $_product->get_name(),
								),
								$_product,
								false
							);
							?>
						</td>
						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'overszclub' ); ?>">
							<?php echo wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php do_action( 'woocommerce_cart_contents' ); ?>
				<tr>
					<td colspan="6" class="actions">
						<?php if ( wc_coupons_enabled() ) : ?>
							<div class="coupon">
								<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Codigo de cupon', 'overszclub' ); ?></label>
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Codigo de cupon', 'overszclub' ); ?>">
								<button type="submit" class="button button--secondary" name="apply_coupon" value="<?php esc_attr_e( 'Aplicar cupon', 'overszclub' ); ?>"><?php esc_html_e( 'Aplicar cupon', 'overszclub' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php endif; ?>

						<button type="submit" class="button button--secondary" name="update_cart" value="<?php esc_attr_e( 'Actualizar carrito', 'overszclub' ); ?>">
							<?php esc_html_e( 'Actualizar carrito', 'overszclub' ); ?>
						</button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<div class="cart-collaterals overszclub-cart-collaterals">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
	</div>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>

