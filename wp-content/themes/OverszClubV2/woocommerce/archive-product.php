<?php get_header(); ?>

<div class="shop-page">
    <div class="shop-header">
        <h1><?php woocommerce_page_title(); ?></h1>
        <p><?php echo esc_html(get_the_archive_description() ?: 'Colección completa de prendas streetwear premium.'); ?></p>
    </div>

    <!-- Filtros por categoría -->
    <div class="shop-filters">
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
           class="filter-btn <?php echo (!is_product_category()) ? 'active' : ''; ?>">
            Todos
        </a>
        <?php
        $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0]);
        if (!is_wp_error($cats)) :
            foreach ($cats as $cat) :
                $is_active = (is_product_category($cat->slug));
        ?>
            <a href="<?php echo esc_url(get_term_link($cat)); ?>"
               class="filter-btn <?php echo $is_active ? 'active' : ''; ?>">
                <?php echo esc_html($cat->name); ?>
            </a>
        <?php endforeach; endif; ?>
    </div>

    <!-- Grid de productos -->
    <div class="wrap">
        <?php if (woocommerce_product_loop()) : ?>

            <?php woocommerce_product_loop_start(); ?>

            <div class="products-grid">
                <?php while (have_posts()) : the_post();
                    global $product;
                    get_template_part('template-parts/product-card');
                endwhile; ?>
            </div>

            <?php woocommerce_product_loop_end(); ?>

            <?php woocommerce_pagination(); ?>

        <?php else : ?>
            <div style="text-align:center;padding:80px 0;">
                <?php wc_no_products_found(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
