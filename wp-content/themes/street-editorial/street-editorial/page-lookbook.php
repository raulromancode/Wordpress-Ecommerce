<?php
/**
 * Template Name: Lookbook
 *
 * Página editorial tipo magazine con galería masonry.
 * Asigna esta plantilla a cualquier página desde el editor de WordPress.
 */
get_header();
while ( have_posts() ) : the_post();

// Si la página tiene una galería de WordPress en el contenido, la usamos.
// Si no, usamos las imágenes de ejemplo del template-part.
$has_content = ! empty( trim( strip_tags( get_the_content() ) ) );
?>

<div class="mt-nav">
    <div class="wrap">
        <div class="lookbook-page">

            <!-- Cabecera -->
            <div class="reveal">
                <p class="eyebrow">— SS25 Editorial</p>
                <h1 class="lookbook-title">Lookbook</h1>
                <p class="lookbook-sub">
                    <?php
                    if ( get_the_excerpt() ) {
                        echo esc_html( get_the_excerpt() );
                    } else {
                        esc_html_e( 'Street Editorial — Season 25', 'street-editorial' );
                    }
                    ?>
                </p>
            </div>

            <?php if ( $has_content ) : ?>
                <!-- Contenido del editor de WP (galería Gutenberg, etc.) -->
                <div class="lookbook-masonry">
                    <?php the_content(); ?>
                </div>
            <?php else : ?>
                <!-- Galería editorial de ejemplo -->
                <?php get_template_part( 'template-parts/lookbook-grid' ); ?>
            <?php endif; ?>

        </div>
    </div>

    <!-- Drop strip inferior -->
    <?php get_template_part( 'template-parts/marquee', null, [
        'items' => [ 'Lookbook SS25', 'Street Editorial', 'Limited Edition', 'New Drops', 'Shop Now' ]
    ] ); ?>

</div>

<?php endwhile; get_footer(); ?>
