<?php
/**
 * Template Part: hero.php — Valores desde el Customizer.
 */

$image    = get_theme_mod('oc_hero_image',   '');
$eyebrow  = get_theme_mod('oc_hero_eyebrow', 'Nueva Colección — SS25');
$title_1  = get_theme_mod('oc_hero_title_1', 'Wear the');
$title_2  = get_theme_mod('oc_hero_title_2', 'Streets.');
$subtitle = get_theme_mod('oc_hero_subtitle','Streetwear con identidad editorial.');
$btn      = get_theme_mod('oc_hero_btn',     'VER COLECCIÓN');
$btn_url  = get_theme_mod('oc_hero_btn_url', '');
$btn_url  = $btn_url ?: (function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/'));
$btn2     = get_theme_mod('oc_hero_btn2',    'Nuestra Historia');
$about    = get_page_by_path('about') ?? get_page_by_path('sobre-nosotros');
?>

<section class="hero" aria-label="Hero">

    <?php if ($image) : ?>
        <img class="hero__bg"
             src="<?php echo esc_url($image); ?>"
             alt=""
             loading="eager"
             fetchpriority="high">
    <?php else : ?>
        <div class="hero__bg--empty" aria-hidden="true">
            <?php if (is_customize_preview()) : ?>
                <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:11px;letter-spacing:4px;text-transform:uppercase;color:#333;text-align:center;line-height:2.2;padding:24px;border:1px dashed #222;">
                    📷 Sube la imagen en<br><strong>Personalizar → Hero</strong>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__content reveal">
        <p class="hero__eyebrow">
            <span><?php echo esc_html($eyebrow); ?></span>
        </p>
        <h1 class="hero__title">
            <?php echo esc_html($title_1); ?><br>
            <em><?php echo esc_html($title_2); ?></em>
        </h1>
        <?php if ($subtitle) : ?>
            <p style="font-size:14px;color:rgba(240,236,228,.65);margin-bottom:36px;max-width:480px;line-height:1.8;">
                <?php echo esc_html($subtitle); ?>
            </p>
        <?php endif; ?>
        <div class="hero__actions">
            <a href="<?php echo esc_url($btn_url); ?>" class="btn btn-primary">
                <?php echo esc_html($btn); ?>
            </a>
            <?php if ($about) : ?>
                <a href="<?php echo esc_url(get_permalink($about)); ?>" class="btn btn-outline">
                    <?php echo esc_html($btn2); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero__scroll" aria-hidden="true">
        <span>Scroll</span>
    </div>

</section>
