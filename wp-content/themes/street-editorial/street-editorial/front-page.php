<?php
/**
 * Homepage de Street Editorial
 */
get_header();
?>

<!-- ── Hero ─────────────────────────────────────────────────── -->
<?php get_template_part( 'template-parts/hero' ); ?>

<!-- ── Marquee ──────────────────────────────────────────────── -->
<?php get_template_part( 'template-parts/marquee' ); ?>

<!-- ── Productos destacados ─────────────────────────────────── -->
<section class="section">
    <div class="wrap">
        <div class="section-head reveal">
            <div class="section-head__left">
                <p class="eyebrow">— Featured</p>
                <h2 class="section-title">Best Sellers</h2>
            </div>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="see-all">
                View All →
            </a>
        </div>

        <div class="products-grid">
            <?php
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'meta_key'       => 'total_sales',
                'orderby'        => 'meta_value_num',
                'order'          => 'DESC',
            ];
            $q = new WP_Query( $args );
            while ( $q->have_posts() ) {
                $q->the_post();
                global $product;
                get_template_part( 'template-parts/product-card' );
            }
            wp_reset_postdata();

            // Si no hay productos reales, mostrar placeholders
            if ( ! $q->have_posts() && $q->post_count === 0 ) :
                for ( $i = 0; $i < 4; $i++ ) : ?>
                    <div class="product-card" style="animation-delay:<?php echo $i * 80; ?>ms">
                        <div class="product-card__thumb" style="background:var(--surface);aspect-ratio:3/4;display:flex;align-items:center;justify-content:center;">
                            <span style="color:var(--dim);font-size:11px;letter-spacing:3px;">PRODUCT <?php echo $i + 1; ?></span>
                        </div>
                        <div class="product-card__info">
                            <p class="product-card__cat">Hoodies</p>
                            <p class="product-card__name">Sample Product</p>
                            <p class="product-card__price">€89.00</p>
                        </div>
                    </div>
                <?php endfor;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- ── About strip ──────────────────────────────────────────── -->
<section class="about-strip">
    <img class="about-strip__img reveal"
         src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&q=80"
         alt="About Street Editorial"
         loading="lazy">
    <div class="about-strip__body reveal">
        <p class="eyebrow">— Our Identity</p>
        <h2 class="about-strip__title">Born on the streets.<br>Built for the stage.</h2>
        <p class="about-strip__text">
            Street Editorial nació con una sola convicción: la ropa streetwear
            merece producción editorial. Cada pieza es un statement.
        </p>
        <?php
        $about = get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' );
        if ( $about ) : ?>
            <a href="<?php echo esc_url( get_permalink( $about ) ); ?>" class="btn btn-outline">
                Read Our Story
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- ── Nueva colección ──────────────────────────────────────── -->
<section class="section section--dark">
    <div class="wrap">
        <div class="section-head reveal">
            <div class="section-head__left">
                <p class="eyebrow">— Recién llegados</p>
                <h2 class="section-title">New Drops</h2>
            </div>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="see-all">
                Shop All →
            </a>
        </div>

        <div class="products-grid">
            <?php
            $args2 = [
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ];
            $q2 = new WP_Query( $args2 );
            while ( $q2->have_posts() ) {
                $q2->the_post();
                global $product;
                get_template_part( 'template-parts/product-card' );
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
