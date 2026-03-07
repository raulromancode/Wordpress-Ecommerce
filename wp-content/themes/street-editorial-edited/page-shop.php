<?php
/**
 * Template Name: Shop Page
 *
 * Plantilla alternativa para la página de tienda.
 * WooCommerce usa archive-product.php automáticamente,
 * pero esta plantilla puede asignarse manualmente a cualquier página
 * desde el editor de WordPress.
 */
get_header();
?>

<div class="wrap mt-nav">
    <div class="shop-page">

        <div class="shop-header reveal">
            <p class="eyebrow">— All Products</p>
            <h1 class="shop-title">Shop</h1>
        </div>

        <?php
        // Renderiza el contenido de WooCommerce tal cual
        if ( function_exists( 'woocommerce_content' ) ) {
            woocommerce_content();
        } else {
            echo '<p style="color:var(--dim);font-size:13px;text-align:center;padding:60px 0;letter-spacing:3px;">WooCommerce no está activado.</p>';
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>
