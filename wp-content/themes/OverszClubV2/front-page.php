<?php get_header(); ?>

<!-- HERO -->
<?php get_template_part('template-parts/hero'); ?>

<!-- MARQUEE -->
<?php get_template_part('template-parts/marquee'); ?>

<!-- PRODUCTOS DESTACADOS -->
<section class="section">
    <div class="wrap">
        <div class="section-head reveal">
            <div>
                <p style="font-size:9px;letter-spacing:5px;text-transform:uppercase;color:var(--c-accent);margin-bottom:8px;">— Selección</p>
                <h2>Best <em>Sellers</em></h2>
            </div>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="see-all">Ver todo</a>
        </div>

        <div class="products-grid">
            <?php
            $q = new WP_Query([
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'meta_key'       => 'total_sales',
                'orderby'        => 'meta_value_num',
                'order'          => 'DESC',
            ]);
            if ($q->have_posts()) :
                while ($q->have_posts()) : $q->the_post();
                    global $product;
                    get_template_part('template-parts/product-card');
                endwhile;
                wp_reset_postdata();
            else :
                for ($i = 0; $i < 4; $i++) :
            ?>
                <div class="product-card">
                    <div class="product-card__thumb" style="aspect-ratio:3/4;background:var(--c-surface);display:flex;align-items:center;justify-content:center;">
                        <span style="font-size:9px;letter-spacing:4px;text-transform:uppercase;color:var(--c-dim);">Producto <?php echo $i+1; ?></span>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__cat">CATEGORÍA</p>
                        <p class="product-card__name">Nombre del Producto</p>
                        <p class="product-card__price">€89,00</p>
                    </div>
                </div>
            <?php endfor; endif; ?>
        </div>
    </div>
</section>

<!-- ABOUT STRIP -->
<?php
$about_img     = get_theme_mod('oc_about_img','');
$about_eyebrow = get_theme_mod('oc_about_eyebrow','Nuestra Identidad');
$about_text    = get_theme_mod('oc_about_text','OverszClub nació con una sola convicción: el streetwear merece producción editorial. Cada pieza es un statement.');
$about_btn     = get_theme_mod('oc_about_btn','Nuestra Historia');
$about_page    = get_page_by_path('about') ?? get_page_by_path('sobre-nosotros');
?>
<section class="about-strip">

    <?php if ($about_img) : ?>
        <img class="about-strip__img reveal"
             src="<?php echo esc_url($about_img); ?>"
             alt="<?php bloginfo('name'); ?>"
             loading="lazy">
    <?php else : ?>
        <div class="about-strip__img--empty reveal">
            <?php if (is_customize_preview()) : ?>
                <span style="font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--c-dim);text-align:center;">
                    📷 Personalizar → About
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="about-strip__body reveal">
        <p class="about-strip__eyebrow"><?php echo esc_html($about_eyebrow); ?></p>
        <h2 class="about-strip__title"><?php echo oc_about_title_html(); ?></h2>
        <p class="about-strip__text"><?php echo esc_html($about_text); ?></p>
        <?php if ($about_page) : ?>
            <a href="<?php echo esc_url(get_permalink($about_page)); ?>" class="btn btn-outline">
                <?php echo esc_html($about_btn); ?>
            </a>
        <?php endif; ?>
    </div>

</section>

<!-- NEW DROPS -->
<section class="section section--dark">
    <div class="wrap">
        <div class="section-head reveal">
            <div>
                <p style="font-size:9px;letter-spacing:5px;text-transform:uppercase;color:var(--c-accent);margin-bottom:8px;">— Recién llegados</p>
                <h2>New <em>Drops</em></h2>
            </div>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="see-all">Shop All</a>
        </div>
        <div class="products-grid">
            <?php
            $q2 = new WP_Query(['post_type'=>'product','posts_per_page'=>4,'orderby'=>'date','order'=>'DESC']);
            while ($q2->have_posts()) : $q2->the_post();
                global $product;
                get_template_part('template-parts/product-card');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
