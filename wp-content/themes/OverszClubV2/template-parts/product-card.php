<?php
/**
 * Template Part: product-card.php
 * Compatible con WooCommerce. Add to cart 100% funcional.
 */

if (!isset($product) || !$product) return;

global $product;
$pid      = $product->get_id();
$name     = $product->get_name();
$price    = $product->get_price_html();
$img      = get_the_post_thumbnail_url($pid, 'oc-card') ?: wc_placeholder_img_src('oc-card');
$link     = get_permalink($pid);
$cats     = wp_get_post_terms($pid, 'product_cat', ['fields'=>'names']);
$cat      = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';
$on_sale  = $product->is_on_sale();
$featured = $product->is_featured();

// Tallas
$sizes = [];
if ($product->is_type('variable')) {
    $attrs = $product->get_variation_attributes();
    foreach ($attrs as $attr_name => $vals) {
        if (stripos($attr_name,'talla')!==false || stripos($attr_name,'size')!==false) {
            $sizes = array_map('strtoupper', $vals); break;
        }
    }
    if (empty($sizes) && !empty($attrs)) {
        $first = reset($attrs); $sizes = array_map('strtoupper', $first);
    }
}
if (empty($sizes)) $sizes = ['S','M','L','XL'];
?>

<div class="product-card reveal"
     data-product-id="<?php echo esc_attr($pid); ?>"
     data-is-variable="<?php echo $product->is_type('variable') ? '1' : '0'; ?>">

    <!-- Imagen + overlay -->
    <div class="product-card__thumb">
        <a href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
            <img src="<?php echo esc_url($img); ?>"
                 alt="<?php echo esc_attr($name); ?>"
                 loading="lazy">
        </a>

        <?php if ($on_sale) : ?>
            <span class="product-card__badge badge-sale">Oferta</span>
        <?php elseif ($featured) : ?>
            <span class="product-card__badge badge-new">Nuevo</span>
        <?php endif; ?>

        <!-- Hover overlay -->
        <div class="product-card__overlay">
            <!-- Selector de talla -->
            <div class="card-sizes" role="group" aria-label="Talla">
                <?php foreach ($sizes as $size) : ?>
                    <button type="button"
                            class="card-size-btn"
                            data-size="<?php echo esc_attr(strtolower($size)); ?>"
                            aria-label="Talla <?php echo esc_attr($size); ?>">
                        <?php echo esc_html($size); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Botón añadir -->
            <button type="button"
                    class="card-atc-btn"
                    data-product-id="<?php echo esc_attr($pid); ?>"
                    data-is-variable="<?php echo $product->is_type('variable') ? '1' : '0'; ?>"
                    data-product-url="<?php echo esc_url($link); ?>"
                    aria-label="<?php echo esc_attr__('Añadir al carrito','overszclubv2'); ?>">
                Añadir al carrito
            </button>
        </div>
    </div>

    <!-- Info -->
    <div class="product-card__info">
        <?php if ($cat) : ?>
            <p class="product-card__cat"><?php echo esc_html(strtoupper($cat)); ?></p>
        <?php endif; ?>
        <a href="<?php echo esc_url($link); ?>">
            <p class="product-card__name"><?php echo esc_html($name); ?></p>
        </a>
        <div class="product-card__price"><?php echo $price; ?></div>
    </div>

</div>
