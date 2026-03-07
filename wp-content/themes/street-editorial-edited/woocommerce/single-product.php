<?php
/**
 * WooCommerce — single-product.php
 * Diseño editorial premium. Carrito 100% compatible con WooCommerce nativo.
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
    $sku        = $product->get_sku();
    $in_stock   = $product->is_in_stock();
    $is_variable = $product->is_type( 'variable' );

    // Imagen principal + galería
    $main_img_id  = $product->get_image_id();
    $main_img     = $main_img_id
        ? wp_get_attachment_image_url( $main_img_id, 'woocommerce_single' )
        : wc_placeholder_img_src( 'woocommerce_single' );
    $gallery_ids  = $product->get_gallery_image_ids();

    // Categoría
    $cats      = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'names' ] );
    $cat_label = ( ! is_wp_error( $cats ) && ! empty( $cats ) ) ? $cats[0] : '';

    // Tallas (para variable) o array vacío (simples van sin selector obligatorio)
    $sizes = [];
    if ( $is_variable ) {
        $attrs = $product->get_variation_attributes();
        foreach ( $attrs as $attr_name => $attr_values ) {
            if ( stripos( $attr_name, 'talla' ) !== false
              || stripos( $attr_name, 'size'  ) !== false
              || stripos( $attr_name, 'talle' ) !== false ) {
                $sizes = array_map( 'strtoupper', $attr_values );
                break;
            }
        }
        // Fallback: primera variación disponible
        if ( empty( $sizes ) && ! empty( $attrs ) ) {
            $first = reset( $attrs );
            $sizes = array_map( 'strtoupper', $first );
        }
    }

    // Nonce WooCommerce para AJAX
    $nonce = wp_create_nonce( 'se_atc_nonce' );
?>

<div class="sp-wrap">

    <!-- ══ BREADCRUMB ══ -->
    <nav class="sp-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url() ); ?>">Home</a>
        <span aria-hidden="true">—</span>
        <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>">Shop</a>
        <span aria-hidden="true">—</span>
        <span><?php echo esc_html( $name ); ?></span>
    </nav>

    <!-- ══ LAYOUT PRINCIPAL ══ -->
    <div class="sp-layout">

        <!-- ── COLUMNA GALERÍA ────────────────────────────── -->
        <div class="sp-gallery">

            <!-- Imagen principal -->
            <div class="sp-gallery__main-wrap">
                <img id="sp-main-img"
                     class="sp-gallery__main"
                     src="<?php echo esc_url( $main_img ); ?>"
                     alt="<?php echo esc_attr( $name ); ?>"
                     loading="eager">

                <?php if ( $product->is_on_sale() ) : ?>
                    <span class="sp-badge sp-badge--sale">Sale</span>
                <?php elseif ( $product->is_featured() ) : ?>
                    <span class="sp-badge sp-badge--new">New</span>
                <?php endif; ?>
            </div>

            <!-- Miniaturas -->
            <?php if ( ! empty( $gallery_ids ) ) : ?>
            <div class="sp-gallery__thumbs">
                <!-- Thumbnail de la imagen principal -->
                <button class="sp-thumb active"
                        data-full="<?php echo esc_url( $main_img ); ?>"
                        aria-label="Imagen principal">
                    <img src="<?php echo esc_url( wp_get_attachment_image_url( $main_img_id, 'thumbnail' ) ?: $main_img ); ?>"
                         alt="">
                </button>
                <?php foreach ( $gallery_ids as $gid ) :
                    $thumb = wp_get_attachment_image_url( $gid, 'thumbnail' );
                    $full  = wp_get_attachment_image_url( $gid, 'woocommerce_single' );
                    $alt   = get_post_meta( $gid, '_wp_attachment_image_alt', true );
                ?>
                <button class="sp-thumb"
                        data-full="<?php echo esc_url( $full ); ?>"
                        aria-label="<?php echo esc_attr( $alt ?: 'Imagen de producto' ); ?>">
                    <img src="<?php echo esc_url( $thumb ); ?>" alt="">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div><!-- /sp-gallery -->

        <!-- ── COLUMNA INFO ───────────────────────────────── -->
        <div class="sp-info">

            <!-- Categoría + nombre -->
            <?php if ( $cat_label ) : ?>
                <p class="sp-info__cat"><?php echo esc_html( strtoupper( $cat_label ) ); ?></p>
            <?php endif; ?>

            <h1 class="sp-info__name"><?php echo esc_html( $name ); ?></h1>

            <!-- Precio -->
            <div class="sp-info__price"><?php echo $price_html; ?></div>

            <!-- Descripción corta -->
            <?php if ( $desc_short ) : ?>
                <div class="sp-info__desc"><?php echo wp_kses_post( $desc_short ); ?></div>
            <?php endif; ?>

            <div class="sp-divider"></div>

            <?php if ( $in_stock ) : ?>

                <!-- ── FORMULARIO DE CARRITO ── -->
                <!--
                    Usamos el form nativo de WooCommerce para que funcione siempre.
                    Para simple:   submit directo via WC.
                    Para variable: JS gestiona variation_id antes de enviar.
                -->
                <form class="sp-atc-form"
                      id="sp-atc-form"
                      action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <!-- Selector de tallas (solo si hay tallas configuradas) -->
                    <?php if ( ! empty( $sizes ) ) : ?>
                    <div class="sp-size-section">
                        <div class="sp-size-label">
                            <span>Selecciona tu talla</span>
                            <a href="#" class="sp-size-guide">Guía de tallas</a>
                        </div>
                        <div class="sp-size-grid" id="sp-size-grid" role="group" aria-label="Talla">
                            <?php foreach ( $sizes as $size ) : ?>
                                <button type="button"
                                        class="sp-size-opt"
                                        data-size="<?php echo esc_attr( strtolower( $size ) ); ?>"
                                        aria-label="Talla <?php echo esc_attr( $size ); ?>">
                                    <?php echo esc_html( $size ); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <p class="sp-size-error" id="sp-size-error" style="display:none;">
                            ← Selecciona una talla
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Cantidad + botón -->
                    <div class="sp-atc-row">
                        <div class="sp-qty">
                            <button type="button" class="sp-qty-btn" id="sp-qty-minus" aria-label="Reducir">−</button>
                            <input type="number"
                                   name="quantity"
                                   id="sp-qty-input"
                                   class="sp-qty-input"
                                   value="1"
                                   min="1"
                                   max="<?php echo esc_attr( $product->get_max_purchase_quantity() ?: 10 ); ?>"
                                   aria-label="Cantidad">
                            <button type="button" class="sp-qty-btn" id="sp-qty-plus" aria-label="Aumentar">+</button>
                        </div>

                        <button type="submit"
                                name="add-to-cart"
                                value="<?php echo esc_attr( $pid ); ?>"
                                class="sp-atc-btn"
                                id="sp-atc-btn"
                                data-product-id="<?php echo esc_attr( $pid ); ?>"
                                data-is-variable="<?php echo $is_variable ? '1' : '0'; ?>"
                                data-nonce="<?php echo esc_attr( $nonce ); ?>">
                            <span class="sp-atc-btn__text">Añadir al carrito</span>
                            <span class="sp-atc-btn__loading" aria-hidden="true" style="display:none;">
                                <svg class="sp-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Campo oculto para variación -->
                    <input type="hidden" name="variation_id" id="sp-variation-id" value="">
                    <?php if ( $is_variable ) :
                        $attrs = $product->get_variation_attributes();
                        foreach ( $attrs as $attr_name => $attr_values ) : ?>
                            <input type="hidden"
                                   name="attribute_<?php echo esc_attr( sanitize_title( $attr_name ) ); ?>"
                                   id="sp-attr-<?php echo esc_attr( sanitize_title( $attr_name ) ); ?>"
                                   value="">
                        <?php endforeach;
                    endif; ?>

                    <?php wp_nonce_field( 'woocommerce-add_to_cart', '_wpnonce' ); ?>

                </form>

                <!-- Enlace directo a carrito -->
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="sp-view-cart">
                    Ver carrito →
                </a>

            <?php else : ?>
                <div class="sp-out-of-stock">
                    <span>Agotado — Avísame cuando esté disponible</span>
                </div>
            <?php endif; ?>

            <div class="sp-divider"></div>

            <!-- Trust features -->
            <ul class="sp-features">
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <div>
                        <strong>Envío gratis</strong>
                        <span>En pedidos superiores a 80€</span>
                    </div>
                </li>
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    <div>
                        <strong>Devolución gratuita</strong>
                        <span>30 días sin preguntas</span>
                    </div>
                </li>
                <li class="sp-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <div>
                        <strong>Pago seguro</strong>
                        <span>Cifrado SSL — Tarjeta y PayPal</span>
                    </div>
                </li>
            </ul>

            <!-- SKU + categorías -->
            <div class="sp-meta">
                <?php if ( $sku ) : ?>
                    <span>REF: <?php echo esc_html( $sku ); ?></span>
                <?php endif; ?>
                <?php if ( $cat_label ) : ?>
                    <span>Cat: <?php echo esc_html( $cat_label ); ?></span>
                <?php endif; ?>
            </div>

        </div><!-- /sp-info -->
    </div><!-- /sp-layout -->

    <!-- ══ DESCRIPCIÓN COMPLETA ══ -->
    <?php if ( $desc_full ) : ?>
    <div class="sp-full-desc">
        <button class="sp-accordion" type="button" aria-expanded="false" id="sp-desc-toggle">
            <span>Descripción completa</span>
            <svg class="sp-accordion__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>
        <div class="sp-accordion__body" id="sp-desc-body" aria-hidden="true">
            <?php echo wp_kses_post( $desc_full ); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══ PRODUCTOS RELACIONADOS ══ -->
    <?php
    $related_ids = wc_get_related_products( $pid, 4 );
    if ( ! empty( $related_ids ) ) : ?>
    <div class="sp-related">
        <h2 class="sp-related__title">También te puede gustar</h2>
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
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /sp-wrap -->

<?php endwhile; get_footer(); ?>
