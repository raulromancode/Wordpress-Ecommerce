<?php
/**
 * Template Part: hero.php
 * Hero fullscreen de la homepage
 *
 * Variables opcionales:
 *   $args['image']    — URL de la imagen de fondo
 *   $args['eyebrow']  — Texto pequeño superior
 *   $args['title']    — Título principal (admite <em> para acento)
 *   $args['subtitle'] — Subtítulo
 */

$image   = $args['image']   ?? 'https://images.unsplash.com/photo-1536766820879-059fec98ec0a?w=1800&q=80';
$eyebrow = $args['eyebrow'] ?? __( 'Nueva Colección — SS25', 'street-editorial' );
$title   = $args['title']   ?? 'Wear the <em>streets.</em>';
$sub     = $args['subtitle'] ?? __( 'Ropa streetwear con identidad editorial. Diseñada para destacar, construida para durar.', 'street-editorial' );
$shop    = wc_get_page_permalink( 'shop' );
?>

<section class="hero" aria-label="Hero">
    <img class="hero__bg"
         src="<?php echo esc_url( $image ); ?>"
         alt=""
         loading="eager"
         fetchpriority="high">
    <div class="hero__gradient" aria-hidden="true"></div>

    <div class="hero__content">
        <p class="eyebrow hero__eyebrow">— <?php echo esc_html( $eyebrow ); ?></p>

        <h1 class="hero__title">
            <?php echo wp_kses( $title, [ 'em' => [], 'br' => [] ] ); ?>
        </h1>

        <p class="hero__subtitle"><?php echo esc_html( $sub ); ?></p>

        <div class="hero__actions">
            <a href="<?php echo esc_url( $shop ); ?>" class="btn btn-primary">
                Shop Now
            </a>
            <?php
            $about = get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' );
            if ( $about ) : ?>
                <a href="<?php echo esc_url( get_permalink( $about ) ); ?>" class="btn btn-outline">
                    Our Story
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Indicador de scroll -->
    <div class="hero__scroll" aria-hidden="true">
        <div class="hero__scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>
