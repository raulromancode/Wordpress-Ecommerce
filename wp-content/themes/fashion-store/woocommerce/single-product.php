<?php get_header(); ?>

<?php while (have_posts()) : the_post();
    global $product;
    $cats  = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'names']);
    $cat_label = $cats ? $cats[0] : '';
    $badge = '';
    if ($product->is_on_sale())      $badge = '<span class="product-card__badge badge-sale" style="position:static;display:inline-block;margin-bottom:14px;">Sale</span>';
    elseif ($product->is_featured()) $badge = '<span class="product-card__badge badge-new"  style="position:static;display:inline-block;margin-bottom:14px;">New</span>';

    // Galería de imágenes
    $main_img = get_the_post_thumbnail_url(get_the_ID(), 'large');
    $gallery_ids = $product->get_gallery_image_ids();
?>

<div class="container mt-nav">
    <div class="single-product">

        <!-- Volver -->
        <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn-ghost" style="display:inline-flex;align-items:center;gap:6px;margin-bottom:36px;">
            ← Volver a la tienda
        </a>

        <div class="single-product__layout">

            <!-- Galería -->
            <div class="single-product__gallery">
                <?php if ($main_img) : ?>
                    <img id="main-product-img" class="single-product__main-img"
                         src="<?php echo esc_url($main_img); ?>"
                         alt="<?php the_title_attribute(); ?>">
                <?php else : ?>
                    <?php echo woocommerce_placeholder_img('large'); ?>
                <?php endif; ?>

                <?php if (!empty($gallery_ids)) : ?>
                    <div class="single-product__thumbs">
                        <?php if ($main_img) : ?>
                            <img class="single-product__thumb active"
                                 src="<?php echo esc_url($main_img); ?>" alt="Imagen principal">
                        <?php endif; ?>
                        <?php foreach ($gallery_ids as $gid) : ?>
                            <img class="single-product__thumb"
                                 src="<?php echo esc_url(wp_get_attachment_image_url($gid,'medium')); ?>" alt="">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Info del producto -->
            <div class="single-product__info">
                <?php echo $badge; ?>
                <p class="single-product__cat"><?php echo esc_html($cat_label); ?></p>
                <h1 class="single-product__name"><?php the_title(); ?></h1>
                <div class="single-product__price"><?php echo $product->get_price_html(); ?></div>

                <?php if ($product->get_short_description()) : ?>
                    <div class="single-product__desc"><?php echo wp_kses_post($product->get_short_description()); ?></div>
                <?php elseif ($product->get_description()) : ?>
                    <div class="single-product__desc"><?php echo wp_kses_post(wp_trim_words($product->get_description(), 40)); ?></div>
                <?php else : ?>
                    <p class="single-product__desc">Prenda de calidad con materiales de primera selección. Colección actual.</p>
                <?php endif; ?>

                <!-- Formulario de WooCommerce (variaciones/tallas/qty/add to cart) -->
                <?php woocommerce_template_single_add_to_cart(); ?>

                <!-- Badges de confianza -->
                <div class="trust-badges">
                    <div>
                        <div class="trust-badge__icon">📦</div>
                        <p class="trust-badge__name">Envío gratis</p>
                        <p class="trust-badge__desc">En pedidos desde 60 €</p>
                    </div>
                    <div>
                        <div class="trust-badge__icon">↩</div>
                        <p class="trust-badge__name">Devolución fácil</p>
                        <p class="trust-badge__desc">30 días sin preguntas</p>
                    </div>
                    <div>
                        <div class="trust-badge__icon">✓</div>
                        <p class="trust-badge__name">Calidad garantizada</p>
                        <p class="trust-badge__desc">Materiales certificados</p>
                    </div>
                    <div>
                        <div class="trust-badge__icon">🔒</div>
                        <p class="trust-badge__name">Pago seguro</p>
                        <p class="trust-badge__desc">SSL &amp; cifrado completo</p>
                    </div>
                </div>
            </div><!-- /info -->
        </div><!-- /layout -->

        <!-- Productos relacionados -->
        <?php
        $related_ids = wc_get_related_products(get_the_ID(), 4);
        if (!empty($related_ids)) : ?>
            <div class="section" style="padding-left:0;padding-right:0;margin-top:48px;">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">— Completa tu look</p>
                        <h2 class="section-title">También te puede gustar</h2>
                    </div>
                </div>
                <div class="products-grid products-grid--4">
                    <?php foreach ($related_ids as $rid) :
                        $rp  = wc_get_product($rid);
                        if (!$rp) continue;
                        $rcs = wp_get_post_terms($rid,'product_cat',['fields'=>'names']);
                    ?>
                        <div class="product-card">
                            <a href="<?php echo get_permalink($rid); ?>" class="product-card__thumb">
                                <img class="product-card__img"
                                     src="<?php echo esc_url(get_the_post_thumbnail_url($rid,'large')); ?>"
                                     alt="<?php echo esc_attr($rp->get_name()); ?>">
                            </a>
                            <div class="product-card__info">
                                <div class="product-card__row">
                                    <div>
                                        <p class="product-card__name"><?php echo esc_html($rp->get_name()); ?></p>
                                        <p class="product-card__cat"><?php echo $rcs ? esc_html($rcs[0]) : ''; ?></p>
                                    </div>
                                    <p class="product-card__price"><?php echo $rp->get_price_html(); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>
