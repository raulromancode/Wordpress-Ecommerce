<?php
/**
 * Homepage de Street Editorial
 * Todos los textos e imágenes editables desde Apariencia → Personalizar
 */
get_header();

// ── Datos del About desde Customizer ──────────────────────────────────
$about_eyebrow = get_theme_mod( 'se_about_eyebrow', 'Our Identity' );
$about_title   = get_theme_mod( 'se_about_title', "Born on the streets.\nBuilt for the stage." );
$about_text    = get_theme_mod( 'se_about_text', 'Street Editorial nació con una sola convicción: la ropa streetwear merece producción editorial. Cada pieza es un statement.' );
$about_btn     = get_theme_mod( 'se_about_btn', 'Read Our Story' );
$about_img     = get_theme_mod( 'se_about_image', '' );
$about_img     = $about_img ?: ''; // Sin fallback externo: usa placeholder CSS si no hay imagen
$about_page    = get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' );

// Procesar título (saltos de línea → <br>)
$about_title_html = implode( '<br>', array_map( 'esc_html', explode( "\n", $about_title ) ) );
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
<!--
    Imagen y textos editables desde:
    Apariencia → Personalizar → Street Editorial Theme → About
-->
<section class="about-strip">

    <?php if ( $about_img ) : ?>
        <img class="about-strip__img reveal"
             src="<?php echo esc_url( $about_img ); ?>"
             alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — About"
             loading="lazy">
    <?php else : ?>
        <!-- Sin imagen: bloque CSS + aviso en Customizer -->
        <div class="about-strip__img about-strip__img--placeholder reveal" aria-hidden="true">
            <?php if ( is_customize_preview() ) : ?>
                <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--border2);text-align:center;line-height:2;">
                    📷 Sube la imagen en<br>Personalizar → About
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="about-strip__body reveal">
        <p class="eyebrow">— <?php echo esc_html( $about_eyebrow ); ?></p>

        <h2 class="about-strip__title">
            <?php echo $about_title_html; ?>
        </h2>

        <p class="about-strip__text">
            <?php echo esc_html( $about_text ); ?>
        </p>

        <?php if ( $about_page ) : ?>
            <a href="<?php echo esc_url( get_permalink( $about_page ) ); ?>" class="btn btn-outline">
                <?php echo esc_html( $about_btn ); ?>
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
