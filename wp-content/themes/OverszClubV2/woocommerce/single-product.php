<?php
/**
 * WooCommerce — single-product.php
 * Carrito 100% nativo + AJAX sin recargar.
 */
get_header();

while (have_posts()) : the_post();
global $product;

$pid        = $product->get_id();
$name       = $product->get_name();
$price_html = $product->get_price_html();
$desc_short = $product->get_short_description();
$desc_full  = $product->get_description();
$sku        = $product->get_sku();
$in_stock   = $product->is_in_stock();
$is_var     = $product->is_type('variable');

// Imagen
$main_id  = $product->get_image_id();
$main_img = $main_id ? wp_get_attachment_image_url($main_id,'woocommerce_single') : wc_placeholder_img_src();
$gallery  = $product->get_gallery_image_ids();

// Categoría
$cats = wp_get_post_terms($pid,'product_cat',['fields'=>'names']);
$cat  = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';

// Tallas
$sizes = [];
if ($is_var) {
    $attrs = $product->get_variation_attributes();
    foreach ($attrs as $name_a => $vals) {
        if (stripos($name_a,'talla')!==false || stripos($name_a,'size')!==false) {
            $sizes = array_map('strtoupper',$vals); break;
        }
    }
    if (empty($sizes) && !empty($attrs)) {
        $first = reset($attrs); $sizes = array_map('strtoupper',$first);
    }
}
if (empty($sizes)) $sizes = ['S','M','L','XL'];
?>

<div class="sp-wrap">

    <!-- BREADCRUMB -->
    <nav class="sp-bread" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url()); ?>">Inicio</a>
        <span class="sep">—</span>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Tienda</a>
        <span class="sep">—</span>
        <span><?php echo esc_html($product->get_name()); ?></span>
    </nav>

    <div class="sp-layout">

        <!-- GALERÍA -->
        <div class="sp-gallery">
            <div class="sp-gallery__main-wrap">
                <img id="sp-main-img"
                     class="sp-gallery__main"
                     src="<?php echo esc_url($main_img); ?>"
                     alt="<?php echo esc_attr($product->get_name()); ?>"
                     loading="eager">
                <?php if ($product->is_on_sale()) : ?>
                    <span class="sp-badge badge-sale">Oferta</span>
                <?php elseif ($product->is_featured()) : ?>
                    <span class="sp-badge badge-new">Nuevo</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($gallery)) : ?>
            <div class="sp-gallery__thumbs">
                <button class="sp-thumb active" data-full="<?php echo esc_url($main_img); ?>">
                    <img src="<?php echo esc_url($main_id ? wp_get_attachment_image_url($main_id,'thumbnail') : $main_img); ?>" alt="">
                </button>
                <?php foreach ($gallery as $gid) :
                    $th = wp_get_attachment_image_url($gid,'thumbnail');
                    $fl = wp_get_attachment_image_url($gid,'woocommerce_single');
                ?>
                    <button class="sp-thumb" data-full="<?php echo esc_url($fl); ?>">
                        <img src="<?php echo esc_url($th); ?>" alt="">
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="sp-info">
            <?php if ($cat) : ?>
                <p class="sp-info__cat"><?php echo esc_html(strtoupper($cat)); ?></p>
            <?php endif; ?>

            <h1 class="sp-info__name"><?php echo esc_html($product->get_name()); ?></h1>
            <div class="sp-info__price"><?php echo $price_html; ?></div>

            <?php if ($desc_short) : ?>
                <div class="sp-info__desc"><?php echo wp_kses_post($desc_short); ?></div>
            <?php endif; ?>

            <div class="sp-divider"></div>

            <?php if ($in_stock) : ?>

            <!-- FORMULARIO NATIVO WC + interceptado por JS para AJAX -->
            <form id="sp-form"
                  class="cart"
                  action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action',$product->get_permalink())); ?>"
                  method="post"
                  enctype="multipart/form-data">

                <!-- Selector de tallas -->
                <div class="sp-size-label">
                    <span>Selecciona tu talla</span>
                    <a href="#" onclick="return false;">Guía de tallas</a>
                </div>
                <div class="sp-size-grid" id="sp-size-grid" role="group" aria-label="Talla">
                    <?php foreach ($sizes as $s) : ?>
                        <button type="button"
                                class="sp-size-opt"
                                data-size="<?php echo esc_attr(strtolower($s)); ?>"
                                aria-label="Talla <?php echo esc_attr($s); ?>">
                            <?php echo esc_html($s); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <p class="sp-size-error" id="sp-size-err">← Selecciona una talla</p>

                <!-- Cantidad + botón -->
                <div class="sp-atc-row">
                    <div class="sp-qty">
                        <button type="button" class="sp-qty-btn" id="sp-minus" aria-label="Reducir">−</button>
                        <input type="number"
                               name="quantity"
                               id="sp-qty"
                               class="sp-qty-input qty"
                               value="1" min="1"
                               max="<?php echo esc_attr($product->get_max_purchase_quantity() ?: 10); ?>"
                               aria-label="Cantidad">
                        <button type="button" class="sp-qty-btn" id="sp-plus" aria-label="Aumentar">+</button>
                    </div>

                    <button type="submit"
                            name="add-to-cart"
                            value="<?php echo esc_attr($pid); ?>"
                            class="sp-atc-btn"
                            id="sp-atc-btn"
                            data-product-id="<?php echo esc_attr($pid); ?>"
                            data-is-variable="<?php echo $is_var ? '1':'0'; ?>">
                        <span class="sp-atc-btn__text">Añadir al carrito</span>
                        <span class="sp-atc-btn__spinner" aria-hidden="true">
                            <svg class="spinner-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Campos ocultos variación -->
                <input type="hidden" name="variation_id" id="sp-var-id" value="">
                <?php if ($is_var) :
                    foreach ($product->get_variation_attributes() as $aname => $avals) : ?>
                        <input type="hidden"
                               name="attribute_<?php echo esc_attr(sanitize_title($aname)); ?>"
                               id="sp-attr-<?php echo esc_attr(sanitize_title($aname)); ?>"
                               value="">
                    <?php endforeach;
                endif; ?>

                <?php wp_nonce_field('woocommerce-add_to_cart','_wpnonce'); ?>

            </form>

            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="sp-view-cart">
                Ver carrito →
            </a>

            <?php else : ?>
                <div class="sp-outofstock">Agotado</div>
            <?php endif; ?>

            <div class="sp-divider"></div>

            <!-- Features -->
            <ul class="sp-features">
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <div class="sp-feature-text">
                        <strong>Envío gratis</strong>
                        <span>Pedidos superiores a 80€</span>
                    </div>
                </li>
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <polyline points="23 4 23 10 17 10"/>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    <div class="sp-feature-text">
                        <strong>Devolución 30 días</strong>
                        <span>Sin preguntas</span>
                    </div>
                </li>
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <div class="sp-feature-text">
                        <strong>Pago seguro</strong>
                        <span>SSL — Tarjeta y PayPal</span>
                    </div>
                </li>
            </ul>

            <?php if ($sku || $cat) : ?>
            <div class="sp-meta">
                <?php if ($sku) : ?><span>REF: <?php echo esc_html($sku); ?></span><?php endif; ?>
                <?php if ($cat) : ?><span>Cat: <?php echo esc_html($cat); ?></span><?php endif; ?>
            </div>
            <?php endif; ?>

        </div><!-- /sp-info -->
    </div><!-- /sp-layout -->

    <!-- Descripción completa -->
    <?php if ($desc_full) : ?>
    <div class="sp-accordion-wrap">
        <button class="sp-accordion-btn" id="sp-acc-btn" type="button" aria-expanded="false">
            <span>Descripción completa</span>
            <svg class="sp-accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>
        <div class="sp-accordion-body" id="sp-acc-body" aria-hidden="true">
            <?php echo wp_kses_post($desc_full); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Relacionados -->
    <?php
    $related = wc_get_related_products($pid, 4);
    if (!empty($related)) : ?>
    <div class="sp-related">
        <h2>También te puede gustar</h2>
        <div class="products-grid">
            <?php foreach ($related as $rid) :
                $rp = wc_get_product($rid);
                if (!$rp) continue;
                global $post, $product;
                $post    = get_post($rid);
                $product = $rp;
                setup_postdata($post);
                get_template_part('template-parts/product-card');
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php endwhile; get_footer(); ?>
