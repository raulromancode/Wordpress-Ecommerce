<?php defined('ABSPATH') || exit; get_header(); ?>

<div class="container mt-nav">
    <div class="woocommerce-checkout-page">

        <h1 class="page-title">Finalizar compra</h1>

        <?php wc_print_notices(); ?>
        <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="checkout-grid">

                <!-- Datos de facturación -->
                <div>
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    <div id="customer_details">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>
                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>

                <!-- Resumen del pedido -->
                <div>
                    <div class="woocommerce-order-review">
                        <h3>Tu pedido</h3>
                        <?php do_action('woocommerce_checkout_before_order_review'); ?>
                        <table class="shop_table" style="margin-bottom:0;">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th style="text-align:right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (WC()->cart->get_cart() as $key => $item) :
                                    $p = $item['data']; ?>
                                    <tr>
                                        <td><?php echo esc_html($p->get_name()); ?> &times; <?php echo $item['quantity']; ?></td>
                                        <td style="text-align:right;color:var(--c-accent);">
                                            <?php echo wc_price($item['line_total']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="font-size:11px;letter-spacing:2px;text-transform:uppercase;">Subtotal</th>
                                    <td style="text-align:right;"><?php echo WC()->cart->get_cart_subtotal(); ?></td>
                                </tr>
                                <tr>
                                    <th style="font-size:11px;letter-spacing:2px;text-transform:uppercase;">Total</th>
                                    <td style="text-align:right;font-size:18px;color:var(--c-accent);"><?php echo WC()->cart->get_cart_total(); ?></td>
                                </tr>
                            </tfoot>
                        </table>

                        <?php do_action('woocommerce_review_order_before_payment'); ?>
                        <?php woocommerce_checkout_payment(); ?>
                        <?php do_action('woocommerce_review_order_after_payment'); ?>
                    </div>
                </div>

            </div><!-- /checkout-grid -->

        </form>

        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
    </div>
</div>

<?php get_footer(); ?>
