<?php
/**
 * woocommerce.php — Wrapper de WooCommerce.
 *
 * WooCommerce usa este archivo como contenedor para todas sus páginas
 * cuando no existen las plantillas específicas (archive-product.php, etc.).
 * En este tema las plantillas específicas están en /woocommerce/, por lo que
 * este archivo actúa solo como seguro.
 */
get_header();
?>
<div class="wrap mt-nav" style="padding:48px 0 80px;">
    <?php woocommerce_content(); ?>
</div>
<?php get_footer(); ?>
