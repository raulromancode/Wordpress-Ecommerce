<?php get_header(); ?>

<?php
$hero_img  = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=80';
$about_img = 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1000&q=80';
?>

<!-- ══ HERO ════════════════════════════════════════════════════ -->
<section class="hero">
    <img src="<?php echo esc_url($hero_img); ?>" alt="Fashion Store" class="hero__bg">
    <div class="hero__grad"></div>
    <div class="hero__content">
        <p class="hero__eyebrow">— Nueva colección</p>
        <h1 class="hero__title">Viste con<br><em>intención.</em></h1>
        <p class="hero__sub">
            Prendas diseñadas para quienes saben lo que quieren.
            Materiales de calidad, cortes modernos y estilo atemporal.
        </p>
        <div class="hero__actions">
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn-primary">Explorar colección</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('sobre-nosotros'))); ?>" class="btn btn-outline">Nuestra historia</a>
        </div>
    </div>
</section>

<!-- ══ MARQUEE ══════════════════════════════════════════════════ -->
<div class="marquee-strip">
    <div class="marquee-track">
        <?php
        $t = 'Nueva colección <span class="marquee-dot">·</span> Envío gratis desde 60€ <span class="marquee-dot">·</span> Devolución gratuita <span class="marquee-dot">·</span> Materiales premium <span class="marquee-dot">·</span> ';
        echo str_repeat( $t, 6 );
        ?>
    </div>
</div>

<!-- ══ PRODUCTOS DESTACADOS ════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">— Destacados</p>
                <h2 class="section-title">Lo más vendido</h2>
            </div>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="see-all">Ver todo →</a>
        </div>

        <div class="products-grid">
            <?php
            $args = ['post_type'=>'product','posts_per_page'=>3,'meta_key'=>'total_sales','orderby'=>'meta_value_num','order'=>'DESC'];
            $q = new WP_Query($args);
            while ($q->have_posts()) : $q->the_post();
                global $product;
                $cats = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'names']);
                $cat_slugs = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'slugs']);
                $cat_label = $cats ? $cats[0] : '';
                $cat_slug  = $cat_slugs ? $cat_slugs[0] : '';
                $badge = '';
                if ($product->is_on_sale())      $badge = '<span class="product-card__badge badge-sale">Sale</span>';
                elseif ($product->is_featured()) $badge = '<span class="product-card__badge badge-new">New</span>';
            ?>
                <div class="product-card" data-cat="<?php echo esc_attr($cat_slug); ?>">
                    <a href="<?php the_permalink(); ?>" class="product-card__thumb">
                        <?php echo get_the_post_thumbnail(get_the_ID(),'large',['class'=>'product-card__img']); ?>
                        <?php echo $badge; ?>
                    </a>
                    <div class="product-card__info">
                        <div class="product-card__row">
                            <div>
                                <p class="product-card__name"><?php the_title(); ?></p>
                                <p class="product-card__cat"><?php echo esc_html($cat_label); ?></p>
                            </div>
                            <p class="product-card__price"><?php echo $product->get_price_html(); ?></p>
                        </div>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

<!-- ══ ABOUT STRIP ══════════════════════════════════════════════ -->
<section class="about-strip">
    <img src="<?php echo esc_url($about_img); ?>" alt="Sobre nosotros" class="about-strip__img">
    <div class="about-strip__content">
        <p class="eyebrow">— Quiénes somos</p>
        <h2 class="about-strip__title">Moda con propósito</h2>
        <p class="about-strip__text">
            Creemos que la ropa debe durar más que una temporada.
            Cada prenda está pensada para combinarse, reutilizarse y
            envejecer con estilo. Sin tendencias efímeras.
        </p>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('sobre-nosotros'))); ?>" class="btn btn-outline">
            Conoce la marca
        </a>
    </div>
</section>

<!-- ══ NUEVOS PRODUCTOS ═════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">— Recién llegados</p>
                <h2 class="section-title">Nuevos drops</h2>
            </div>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="see-all">Ver todos →</a>
        </div>
        <div class="products-grid--4 products-grid">
            <?php
            $args2 = ['post_type'=>'product','posts_per_page'=>4,'orderby'=>'date','order'=>'DESC'];
            $q2 = new WP_Query($args2);
            while ($q2->have_posts()) : $q2->the_post();
                global $product;
                $cats2 = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'names']);
                $slug2 = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'slugs']);
            ?>
                <div class="product-card" data-cat="<?php echo $slug2 ? esc_attr($slug2[0]) : ''; ?>">
                    <a href="<?php the_permalink(); ?>" class="product-card__thumb">
                        <?php echo get_the_post_thumbnail(get_the_ID(),'large',['class'=>'product-card__img']); ?>
                        <span class="product-card__badge badge-new">New</span>
                    </a>
                    <div class="product-card__info">
                        <div class="product-card__row">
                            <div>
                                <p class="product-card__name"><?php the_title(); ?></p>
                                <p class="product-card__cat"><?php echo $cats2 ? esc_html($cats2[0]) : ''; ?></p>
                            </div>
                            <p class="product-card__price"><?php echo $product->get_price_html(); ?></p>
                        </div>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
