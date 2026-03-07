<?php
/**
 * WooCommerce — archive-product.php
 * Página de tienda: catálogo de productos con filtros por categoría.
 */
get_header();

$all_cats = get_terms( [
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'orderby'    => 'name',
] );
?>

<div class="wrap mt-nav">
    <div class="shop-page">

        <!-- Cabecera -->
        <div class="shop-header reveal">
            <p class="eyebrow">— Colección actual</p>
            <h1 class="shop-title">
                <?php
                if ( is_product_category() ) {
                    single_cat_title();
                } else {
                    woocommerce_page_title();
                }
                ?>
            </h1>
            <p class="shop-count">
                <?php
                global $wp_query;
                printf(
                    _n( '%d producto', '%d productos', $wp_query->found_posts, 'street-editorial' ),
                    $wp_query->found_posts
                );
                ?>
            </p>
        </div>

        <!-- Filtros de categoría -->
        <?php if ( ! is_wp_error( $all_cats ) && ! empty( $all_cats ) ) : ?>
            <div class="shop-filters reveal" role="group" aria-label="Filtrar por categoría">
                <button class="shop-filter active" data-cat="all">
                    All
                </button>
                <?php foreach ( $all_cats as $cat ) : ?>
                    <button class="shop-filter" data-cat="<?php echo esc_attr( $cat->slug ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- JS para filtros de categoría -->
            <script>
            (function(){
                const btns  = document.querySelectorAll('.shop-filter');
                const cards = document.querySelectorAll('.product-card');
                btns.forEach(function(btn){
                    btn.addEventListener('click', function(){
                        btns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const cat = this.dataset.cat;
                        cards.forEach(function(card){
                            card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
                        });
                    });
                });
            })();
            </script>
        <?php endif; ?>

        <!-- Grid -->
        <?php if ( woocommerce_product_loop() ) : ?>
            <div class="products-grid" id="products-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    global $product;
                    get_template_part( 'template-parts/product-card' );
                endwhile;
                ?>
            </div>

            <!-- Paginación -->
            <div class="woocommerce-pagination">
                <?php woocommerce_pagination(); ?>
            </div>

        <?php else : ?>
            <div style="text-align:center;padding:80px 0;color:var(--dim);">
                <p style="font-size:11px;letter-spacing:4px;text-transform:uppercase;">
                    No se encontraron productos en esta categoría.
                </p>
                <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>"
                   class="btn btn-outline" style="display:inline-flex;margin-top:24px;">
                    Ver toda la tienda
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
