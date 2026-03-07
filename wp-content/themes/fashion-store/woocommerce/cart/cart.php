<?php defined('ABSPATH') || exit; get_header(); ?>

<div class="container mt-nav">
    <div class="woocommerce-cart-page">

        <h1 class="page-title">Carrito</h1>

        <?php wc_print_notices(); ?>
        <?php do_action('woocommerce_before_cart'); ?>

        <form class="woocommerce-cart-form"
              action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <table class="shop_table" cellspacing="0">
                <thead>
                    <tr>
                        <th class="product-remove"></th>
                        <th class="product-thumbnail"></th>
                        <th class="product-name">Producto</th>
                        <th class="product-price">Precio</th>
                        <th class="product-quantity">Cantidad</th>
                        <th class="product-subtotal">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action('woocommerce_before_cart_contents'); ?>

                    <?php foreach (WC()->cart->get_cart() as $key => $item) :
                        $_product   = apply_filters('woocommerce_cart_item_product', $item['data'], $item, $key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $item['product_id'], $item, $key);
                        if ($_product && $_product->exists() && $item['quantity'] > 0) :
                            $permalink = apply_filters('woocommerce_cart_item_permalink',
                                $_product->is_visible() ? $_product->get_permalink($item) : '', $item, $key);
                    ?>
                        <tr class="woocommerce-cart-form__cart-item">
                            <td class="product-remove">
                                <?php echo apply_filters('woocommerce_cart_item_remove_link',
                                    sprintf('<a href="%s" class="remove" aria-label="%s" data-product_id="%s">&times;</a>',
                                        esc_url(wc_get_cart_remove_url($key)),
                                        esc_attr__('Eliminar artículo','fashion-store'),
                                        esc_attr($product_id)
                                    ), $key); ?>
                            </td>
                            <td class="product-thumbnail">
                                <?php $thumb = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $item, $key);
                                echo $permalink ? '<a href="'.esc_url($permalink).'">'.$thumb.'</a>' : $thumb; ?>
                            </td>
                            <td class="product-name" data-title="Producto">
                                <?php echo $permalink
                                    ? '<a href="'.esc_url($permalink).'">'.get_the_title($product_id).'</a>'
                                    : get_the_title($product_id);
                                echo wc_get_formatted_cart_item_data($item); ?>
                            </td>
                            <td class="product-price" data-title="Precio">
                                <?php echo apply_filters('woocommerce_cart_item_price',
                                    WC()->cart->get_product_price($_product), $item, $key); ?>
                            </td>
                            <td class="product-quantity" data-title="Cantidad">
                                <?php if ($_product->is_sold_individually()) {
                                    echo '1 <input type="hidden" name="cart['.$key.'][qty]" value="1">';
                                } else {
                                    woocommerce_quantity_input([
                                        'input_name'  => "cart[{$key}][qty]",
                                        'input_value' => $item['quantity'],
                                        'max_value'   => $_product->get_max_purchase_quantity(),
                                        'min_value'   => '0',
                                    ]);
                                } ?>
                            </td>
                            <td class="product-subtotal" data-title="Subtotal">
                                <?php echo apply_filters('woocommerce_cart_item_subtotal',
                                    WC()->cart->get_product_subtotal($_product, $item['quantity']), $item, $key); ?>
                            </td>
                        </tr>
                    <?php endif; endforeach; ?>

                    <?php do_action('woocommerce_cart_contents'); ?>

                    <tr>
                        <td colspan="6" class="actions" style="padding-top:24px;">
                            <?php if (wc_coupons_enabled()) : ?>
                                <div style="display:flex;gap:8px;margin-bottom:12px;">
                                    <input type="text" name="coupon_code" id="coupon_code" value=""
                                           placeholder="Código de descuento"
                                           style="flex:1;background:var(--c-surface2);border:1px solid var(--c-border);color:var(--c-text);padding:10px 14px;font-size:13px;">
                                    <button type="submit" class="btn btn-outline" name="apply_coupon" value="Aplicar">
                                        Aplicar
                                    </button>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-ghost" name="update_cart" value="Actualizar">
                                Actualizar carrito
                            </button>
                            <?php wp_nonce_field('woocommerce-cart','woocommerce-cart-nonce'); ?>
                        </td>
                    </tr>

                    <?php do_action('woocommerce_after_cart_contents'); ?>
                </tbody>
            </table>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <div class="cart-collaterals">
            <?php do_action('woocommerce_cart_collaterals'); ?>
        </div>

        <?php do_action('woocommerce_after_cart'); ?>

    </div>
</div>

<?php get_footer(); ?>
