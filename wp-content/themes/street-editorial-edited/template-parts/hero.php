<?php
/**
 * Template Part: hero.php
 * Hero fullscreen.
 *
 * IMAGEN: Apariencia → Personalizar → Street Editorial Theme → Hero — Portada → Imagen de fondo
 * Si no hay imagen subida, muestra un fondo CSS oscuro (sin dependencia externa).
 */

$image    = get_theme_mod( 'se_hero_image', '' );
$eyebrow  = get_theme_mod( 'se_hero_eyebrow', 'Nueva Colección — SS25' );
$title_1  = get_theme_mod( 'se_hero_title_1', 'Wear the' );
$title_2  = get_theme_mod( 'se_hero_title_2', 'streets.' );
$subtitle = get_theme_mod( 'se_hero_subtitle', __( 'Ropa streetwear con identidad editorial. Diseñada para destacar, construida para durar.', 'street-editorial' ) );
$btn_text = get_theme_mod( 'se_hero_btn_text', 'Shop Now' );
$btn_url  = get_theme_mod( 'se_hero_btn_url', '' );
$btn_url  = $btn_url ?: ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) );
$btn2_txt = get_theme_mod( 'se_hero_btn2_text', 'Our Story' );
$about    = get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' );
?>

<section class="hero" aria-label="Hero">

    <?php if ( $image ) : ?>
        <!-- Imagen subida desde el Customizer -->
        <img class="hero__bg"
             src="<?php echo esc_url( $image ); ?>"
             alt=""
             loading="eager"
             fetchpriority="high">
    <?php else : ?>
        <!-- Sin imagen: fondo CSS puro + aviso en Customizer -->
        <div class="hero__bg hero__bg--placeholder" aria-hidden="true">
            <?php if ( is_customize_preview() ) : ?>
                <span class="hero__placeholder-hint">
                    📷 Sube la imagen del Hero en<br>
                    <strong>Personalizar → Hero — Portada → Imagen de fondo</strong>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="hero__gradient" aria-hidden="true"></div>

    <div class="hero__content">
        <p class="eyebrow hero__eyebrow">— <?php echo esc_html( $eyebrow ); ?></p>

        <h1 class="hero__title">
            <?php echo esc_html( $title_1 ); ?><br>
            <em><?php echo esc_html( $title_2 ); ?></em>
        </h1>

        <p class="hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>

        <div class="hero__actions">
            <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn-primary">
                <?php echo esc_html( $btn_text ); ?>
            </a>
            <?php if ( $about ) : ?>
                <a href="<?php echo esc_url( get_permalink( $about ) ); ?>" class="btn btn-outline">
                    <?php echo esc_html( $btn2_txt ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero__scroll" aria-hidden="true">
        <div class="hero__scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>
