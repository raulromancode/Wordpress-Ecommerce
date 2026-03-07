<?php
/**
 * Template Name: About
 *
 * Página de historia de marca con diseño editorial.
 * Asigna esta plantilla a la página "About" desde el editor de WordPress.
 */
get_header();
while ( have_posts() ) : the_post();

$cover_img = get_the_post_thumbnail_url( get_the_ID(), 'full' )
             ?: get_theme_mod( 'se_about_image', '' )
             ?: ''; // Sin fallback externo: usa imagen del Customizer

$has_content = ! empty( trim( strip_tags( get_the_content() ) ) );
?>

<div class="mt-nav">
    <div class="wrap">
        <div class="about-page">

            <!-- Hero título -->
            <div class="reveal">
                <p class="eyebrow" style="margin-bottom:20px;">— Our Story</p>
                <h1 class="about-hero-title">
                    Street<br><span>Editorial</span>
                </h1>
            </div>

            <!-- Imagen de portada -->
            <img class="about-cover reveal"
                 src="<?php echo esc_url( $cover_img ); ?>"
                 alt="Street Editorial — Brand Story"
                 loading="lazy">

            <?php if ( $has_content ) : ?>
                <!-- Contenido del editor de WP -->
                <div style="font-size:14px;color:var(--muted);line-height:1.95;max-width:820px;">
                    <?php the_content(); ?>
                </div>

            <?php else : ?>
                <!-- Contenido de ejemplo editorial -->

                <!-- Pull quote -->
                <p class="about-pull reveal">
                    "La moda es arquitectura: es una cuestión de proporciones."
                </p>

                <!-- Dos columnas de texto -->
                <div class="about-blocks">
                    <div class="about-block reveal">
                        <h2 class="about-block__title">The Beginning</h2>
                        <p class="about-block__text">
                            Street Editorial nació en 2020 de una pregunta simple:
                            ¿por qué la ropa streetwear no recibe el mismo tratamiento
                            editorial que la alta moda? Queríamos prendas que hablasen
                            por sí solas, sin logotipos que gritaran.
                        </p>
                    </div>
                    <div class="about-block reveal">
                        <h2 class="about-block__title">The Craft</h2>
                        <p class="about-block__text">
                            Cada pieza se desarrolla durante meses. Empezamos por la
                            tela: algodón orgánico de 380 gsm para hoodies, lino lavado
                            para las piezas de verano. El corte lo hacemos en pequeñas
                            tandas de no más de 200 unidades por colorway.
                        </p>
                    </div>
                    <div class="about-block reveal">
                        <h2 class="about-block__title">No Seasons</h2>
                        <p class="about-block__text">
                            No creemos en el calendario de la moda. Lanzamos drops
                            cuando el producto está terminado. Sin sprints de producción.
                            Sin rebajas artificiales. Solo piezas que queremos que duren
                            una década en tu armario.
                        </p>
                    </div>
                    <div class="about-block reveal">
                        <h2 class="about-block__title">The Future</h2>
                        <p class="about-block__text">
                            Estamos construyendo un archivo. Cada colección documenta
                            un momento en el tiempo. Dentro de diez años, una pieza
                            de Street Editorial debería seguir sintiéndose igual de
                            relevante que el día que la compraste.
                        </p>
                    </div>
                </div>

                <!-- Texto central largo -->
                <div class="about-full reveal">
                    <p class="about-block__text">
                        No somos una marca de tendencias. No tenemos una colección de
                        primavera/verano y otra de otoño/invierno. Tenemos un archivo
                        en construcción permanente. Cada pieza entra cuando está lista
                        y sale cuando se agota. Sin re-stocks, sin descuentos.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- CTA final -->
    <div style="background:var(--black2);padding:80px 0;text-align:center;margin-top:80px;">
        <p class="eyebrow" style="margin-bottom:20px;">— The Collection</p>
        <h2 style="font-size:clamp(48px,6vw,90px);margin-bottom:32px;">Shop the Archive</h2>
        <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="btn btn-primary">
            Browse All Pieces
        </a>
    </div>

</div>

<?php endwhile; get_footer(); ?>
