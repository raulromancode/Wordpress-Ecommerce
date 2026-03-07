<?php
/**
 * Checkout personalizado.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'Debes iniciar sesion para finalizar la compra.', 'overszclub' ) ) );
	return;
}
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout overszclub-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Finalizar compra', 'overszclub' ); ?>">
	<div class="overszclub-checkout__grid">
		<section class="overszclub-checkout__customer">
			<header class="overszclub-panel-heading">
				<p class="eyebrow"><?php esc_html_e( 'Datos del cliente', 'overszclub' ); ?></p>
				<h2><?php esc_html_e( 'Informacion de envio y facturacion', 'overszclub' ); ?></h2>
			</header>
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="col2-set" id="customer_details">
					<div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>
					<div class="col-2">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<?php endif; ?>
		</section>

		<aside class="overszclub-checkout__review">
			<header class="overszclub-panel-heading">
				<p class="eyebrow"><?php esc_html_e( 'Resumen', 'overszclub' ); ?></p>
				<h2><?php esc_html_e( 'Tu pedido', 'overszclub' ); ?></h2>
			</header>
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
		</aside>
	</div>
</form>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

