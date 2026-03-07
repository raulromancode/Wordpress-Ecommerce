<?php
/**
 * Resumen de pedido personalizado.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Producto', 'overszclub' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Total', 'overszclub' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( ! $_product || ! $_product->exists() || 0 >= $cart_item['quantity'] ) {
				continue;
			}
			?>
			<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<td class="product-name">
					<div class="overszclub-review-item">
						<div class="overszclub-review-item__thumb">
							<?php echo wp_kses_post( $_product->get_image( 'woocommerce_thumbnail' ) ); ?>
						</div>
						<div class="overszclub-review-item__content">
							<strong><?php echo esc_html( $_product->get_name() ); ?></strong>
							<span><?php echo esc_html( sprintf( __( 'Cantidad: %d', 'overszclub' ), (int) $cart_item['quantity'] ) ); ?></span>
							<?php if ( ! empty( $cart_item['overszclub_size'] ) ) : ?>
								<span><?php echo esc_html( sprintf( __( 'Talla: %s', 'overszclub' ), $cart_item['overszclub_size'] ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</td>
				<td class="product-total">
					<?php echo wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
				</td>
			</tr>
			<?php
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'overszclub' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'overszclub' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</tfoot>
</table>

<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'No hay metodos de pago disponibles para tu zona.', 'overszclub' ) : esc_html__( 'Introduce tus datos para ver los metodos de pago disponibles.', 'overszclub' ) ), 'notice' );
				echo '</li>';
			}
			?>
		</ul>
	<?php endif; ?>

	<div class="form-row place-order">
		<noscript>
			<?php
			printf(
				esc_html__( 'Tu navegador no soporta JavaScript o esta desactivado. Pulsa %s para recalcular totales antes de finalizar.', 'overszclub' ),
				'<em>' . esc_html__( 'Actualizar totales', 'overszclub' ) . '</em>'
			);
			?>
			<br><button type="submit" class="button button--secondary" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Actualizar totales', 'overszclub' ); ?>"><?php esc_html_e( 'Actualizar totales', 'overszclub' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button button--primary alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>

