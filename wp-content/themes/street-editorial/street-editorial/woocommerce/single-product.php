<?php
/**
 * WooCommerce — single-product.php
 * Página de detalle de producto con galería, selector de tallas y AJAX add to cart.
 */
get_header();

while ( have_posts() ) :
    the_post();
    global $product;

    $pid        = $product->get_id();
    $name       = $product->get_name();
    $price_html = $product->get_price_html();
    $desc_short = $product->get_short_description();
    $desc_full  = $product->get_description();

    // Imagen principal
    $main_img = get_the_post_thumbnail_url( $pid, 'large' ) ?: wc_placeholder_img_src( 'large' );

    // Galería extra
    $gallery_ids = $product->get_gallery_image_ids();

    // Categoría
    $cats      = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'names' ] );
    $cat_label = ! is_wp_error( $cats ) && ! empty( $cats ) ? $cats[0] : '';

    // Badge
    $badge = '';
    if ( $product->is_on_sale() ) {
        $badge = '<span class="product-card__badge badge-sale" style="position:static;display:inline-block;margin-bottom:16px;">Sale</span>';
    } elseif ( $product->is_featured() ) {
        $badge = '<span class="product-card__badge badge-new" style="position:static;display:inline-block;margin-bottom:16px;">New</span>';
    }

    // Tallas disponibles
    $sizes = [];
    if ( $product->is_type( 'variable' ) ) {
        $variations = $product->get_available_variations();
        foreach ( $variations as $v ) {
            $s = $v['attributes']['attribute_pa_talla'] ?? '';
            if ( $s ) $sizes[] = strtoupper( $s );
        }
        $sizes = array_unique( $sizes );
    }
    if ( empty( $sizes ) ) {
        $sizes = [ 'XS', 'S', 'M', 'L', 'XL', 'XXL' ];
    }
?>

<div class="wrap mt-nav">
    <div class="single-product">

        <!-- Breadcrumb mínimo -->
        <nav aria-label="Breadcrumb" style="margin-bottom:40px;display:flex;gap:8px;font-size:11px;color:var(--dim);letter-spacing:2px;text-transform:uppercase;">
            <a href="<?php echo esc_url( home_url() ); ?>" style="color:var(--dim);transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--dim)'">Home</a>
            <span>/</span>
            <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" style="color:var(--dim);transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--dim)'">Shop</a>
            <span>/</span>
            <span style="color:var(--muted);"><?php echo esc_html( $name ); ?></span>
        </nav>

        <div class="single-product__layout">

            <!-- ── Galería ─────────────────────────────────────── -->
            <div class="single-gallery">
                <img id="single-main-img"
                     class="single-gallery__main"
                     src="<?php echo esc_url( $main_img ); ?>"
                     alt="<?php echo esc_attr( $name ); ?>"
                     loading="eager">

                <?php if ( ! empty( $gallery_ids ) ) : ?>
                    <div class="single-gallery__thumbs">
                        <!-- Primera miniatura = imagen principal -->
                        <img class="single-gallery__thumb active"
                             src="<?php echo esc_url( $main_img ); ?>"
                             data-full="<?php echo esc_url( $main_img ); ?>"
                             alt="<?php echo esc_attr( $name ); ?>">

                        <?php foreach ( $gallery_ids as $gid ) :
                            $thumb_url = wp_get_attachment_image_url( $gid, 'medium' );
                            $full_url  = wp_get_attachment_image_url( $gid, 'large' );
                        ?>
                            <img class="single-gallery__thumb"
                                 src="<?php echo esc_url( $thumb_url ); ?>"
                                 data-full="<?php echo esc_url( $full_url ); ?>"
                                 alt="">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── Info ───────────────────────────────────────── -->
            <div class="single-info">

                <?php echo $badge; ?>

                <p class="single-info__cat"><?php echo esc_html( $cat_label ); ?></p>
                <h1 class="single-info__name"><?php echo esc_html( $name ); ?></h1>
                <div class="single-info__price"><?php echo $price_html; ?></div>

                <?php if ( $desc_short ) : ?>
                    <div class="single-info__desc"><?php echo wp_kses_post( $desc_short ); ?></div>
                <?php elseif ( $desc_full ) : ?>
                    <div class="single-info__desc"><?php echo wp_kses_post( wp_trim_words( $desc_full, 40 ) ); ?></div>
                <?php else : ?>
                    <p class="single-info__desc">
                        Pieza de edición limitada. Materiales de primera calidad, silueta relaxed fit.
                        Fabricado en 100% algodón orgánico de alto gramaje.
                    </p>
                <?php endif; ?>

                <!-- Selector de tallas -->
                <div class="size-label">
                    Select Size
                    <a href="#" style="float:right;font-size:10px;color:var(--muted);border-bottom:1px solid var(--border2);">Size Guide</a>
                </div>
                <div class="size-grid" role="group" aria-label="Talla">
                    <?php foreach ( $sizes as $size ) : ?>
                        <button class="size-option"
                                data-size="<?php echo esc_attr( strtolower($size) ); ?>"
                                aria-label="Talla <?php echo esc_attr($size); ?>">
                            <?php echo esc_html( $size ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Botón Add to Cart -->
                <button class="single-add-btn"
                        data-product-id="<?php echo esc_attr( $pid ); ?>"
                        id="single-atc">
                    Add to Cart
                </button>

                <!-- Enlace a la página del carrito -->
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="btn-ghost" style="display:block;text-align:center;margin-bottom:8px;">
                    View Cart
                </a>

                <!-- Trust badges -->
                <div class="trust-badges">
                    <div class="trust-badge">
                        <div class="trust-badge__icon">📦</div>
                        <p class="trust-badge__name">Free Shipping</p>
                        <p class="trust-badge__desc">Orders over €80</p>
                    </div>
                    <div class="trust-badge">
                        <div class="trust-badge__icon">↩</div>
                        <p class="trust-badge__name">Free Returns</p>
                        <p class="trust-badge__desc">30-day window</p>
                    </div>
                    <div class="trust-badge">
                        <div class="trust-badge__icon">✓</div>
                        <p class="trust-badge__name">Premium Quality</p>
                        <p class="trust-badge__desc">Certified materials</p>
                    </div>
                    <div class="trust-badge">
                        <div class="trust-badge__icon">🔒</div>
                        <p class="trust-badge__name">Secure Payment</p>
                        <p class="trust-badge__desc">SSL encrypted</p>
                    </div>
                </div>

            </div><!-- /single-info -->
        </div><!-- /layout -->

        <!-- Descripción completa (accordion) -->
        <?php if ( $desc_full ) : ?>
            <div style="margin-top:64px;padding-top:48px;border-top:1px solid var(--border);max-width:720px;">
                <h3 style="font-size:24px;letter-spacing:3px;margin-bottom:16px;">Product Details</h3>
                <div style="font-size:13px;color:var(--muted);line-height:1.9;">
                    <?php echo wp_kses_post( $desc_full ); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Productos relacionados -->
        <?php
        $related_ids = wc_get_related_products( $pid, 4 );
        if ( ! empty( $related_ids ) ) : ?>
            <div class="related-section">
                <h2 class="related-title">You May Also Like</h2>
                <div class="products-grid products-grid--4">
                    <?php foreach ( $related_ids as $rid ) :
                        $rp = wc_get_product( $rid );
                        if ( ! $rp ) continue;
                        global $post, $product;
                        $post    = get_post( $rid );
                        $product = $rp;
                        setup_postdata( $post );
                        get_template_part( 'template-parts/product-card' );
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div><!-- /single-product -->
</div><!-- /wrap -->

<!-- ── Galería JS (thumbnail swap) ──────────────────────────── -->
<script>
(function(){
    var mainImg = document.getElementById('single-main-img');
    if (!mainImg) return;

    document.querySelectorAll('.single-gallery__thumb').forEach(function(thumb){
        thumb.addEventListener('click', function(){
            mainImg.style.opacity = '0';
            setTimeout(function(){
                mainImg.src = thumb.dataset.full || thumb.src;
                mainImg.style.opacity = '1';
            }, 150);
            document.querySelectorAll('.single-gallery__thumb').forEach(function(t){ t.classList.remove('active'); });
            thumb.classList.add('active');
        });
    });
    mainImg.style.transition = 'opacity .15s ease';
})();
</script>

<?php endwhile; get_footer(); ?>
