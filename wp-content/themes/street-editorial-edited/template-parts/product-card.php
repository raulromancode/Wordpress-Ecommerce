<?php
/**
 * Template Part: product-card.php
 * Tarjeta de producto reutilizable.
 * Requiere el loop de WC activo ($product global disponible).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
if ( ! $product ) return;

$pid       = $product->get_id();
$name      = $product->get_name();
$permalink = get_permalink( $pid );
$price     = $product->get_price_html();
$img_url   = get_the_post_thumbnail_url( $pid, 'se-card' ) ?: wc_placeholder_img_src();

// Categoría
$cats      = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'names' ] );
$cat_label = ! is_wp_error( $cats ) && ! empty( $cats ) ? $cats[0] : '';
$cat_slugs = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'slugs' ] );
$cat_slug  = ! is_wp_error( $cat_slugs ) && ! empty( $cat_slugs ) ? $cat_slugs[0] : '';

// Badge
$badge = '';
if ( $product->is_on_sale() ) {
    $badge = '<span class="product-card__badge badge-sale">Sale</span>';
} elseif ( $product->is_featured() ) {
    $badge = '<span class="product-card__badge badge-new">New</span>';
}

// Tallas disponibles (atributo pa_talla)
$sizes = [];
if ( $product->is_type( 'variable' ) ) {
    $available = $product->get_available_variations();
    foreach ( $available as $var ) {
        if ( ! empty( $var['attributes']['attribute_pa_talla'] ) ) {
            $sizes[] = strtoupper( $var['attributes']['attribute_pa_talla'] );
        }
    }
    $sizes = array_unique( $sizes );
}
// Tallas por defecto si no hay variaciones configuradas
if ( empty( $sizes ) ) {
    $sizes = [ 'XS', 'S', 'M', 'L', 'XL' ];
}
?>

<article class="product-card" data-cat="<?php echo esc_attr( $cat_slug ); ?>">

    <!-- Thumbnail con overlay -->
    <a href="<?php echo esc_url( $permalink ); ?>" class="product-card__thumb" tabindex="-1" aria-hidden="true">
        <img class="product-card__img"
             src="<?php echo esc_url( $img_url ); ?>"
             alt="<?php echo esc_attr( $name ); ?>"
             loading="lazy">
        <?php echo $badge; ?>

        <!-- Quick Add overlay -->
        <div class="product-card__overlay">
            <div class="size-selector" role="group" aria-label="Seleccionar talla">
                <?php foreach ( $sizes as $size ) : ?>
                    <button class="size-btn"
                            data-size="<?php echo esc_attr( strtolower( $size ) ); ?>"
                            aria-label="Talla <?php echo esc_attr( $size ); ?>">
                        <?php echo esc_html( $size ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <button class="quick-add-btn"
                    data-product-id="<?php echo esc_attr( $pid ); ?>"
                    aria-label="Añadir <?php echo esc_attr( $name ); ?> al carrito">
                Quick Add
            </button>
        </div>
    </a>

    <!-- Info -->
    <div class="product-card__info">
        <p class="product-card__cat"><?php echo esc_html( $cat_label ); ?></p>
        <a href="<?php echo esc_url( $permalink ); ?>">
            <p class="product-card__name"><?php echo esc_html( $name ); ?></p>
        </a>
        <p class="product-card__price"><?php echo $price; ?></p>
    </div>

</article>
