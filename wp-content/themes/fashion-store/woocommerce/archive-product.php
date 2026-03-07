<?php get_header(); ?>

<?php
$categories = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true]);
?>

<div class="container mt-nav">
    <div class="shop-page">

        <div class="shop-page__header">
            <p class="eyebrow">— Colección</p>
            <h1 class="shop-page__title">Tienda</h1>
            <?php
            $total = wp_count_posts('product');
            echo '<p class="shop-page__count">' . esc_html($total->publish) . ' productos</p>';
            ?>
        </div>

        <!-- Filtros de categoría -->
        <?php if (!is_wp_error($categories) && !empty($categories)) : ?>
            <div class="cat-filters">
                <button class="cat-filter active" data-cat="all">Todo</button>
                <?php foreach ($categories as $cat) : ?>
                    <button class="cat-filter" data-cat="<?php echo esc_attr($cat->slug); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Grid de productos -->
        <?php if (woocommerce_product_loop()) : ?>
            <div class="products-grid">
                <?php while (have_posts()) : the_post();
                    global $product;
                    $cats  = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'names']);
                    $slugs = wp_get_post_terms(get_the_ID(),'product_cat',['fields'=>'slugs']);
                    $cat_label = $cats  ? $cats[0]  : '';
                    $cat_slug  = $slugs ? $slugs[0] : '';
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
                <?php endwhile; ?>
            </div>

            <div class="woocommerce-pagination">
                <?php woocommerce_pagination(); ?>
            </div>

        <?php else : ?>
            <p style="color:var(--c-text-dim);font-size:13px;padding:60px 0;text-align:center;letter-spacing:2px;">
                No se encontraron productos.
            </p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
