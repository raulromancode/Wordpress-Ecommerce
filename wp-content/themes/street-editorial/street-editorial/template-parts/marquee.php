<?php
/**
 * Template Part: marquee.php
 * Barra de texto animada horizontal
 */

$items = $args['items'] ?? [
    __( 'New Collection', 'street-editorial' ),
    __( 'Limited Drop',   'street-editorial' ),
    __( 'Free Shipping',  'street-editorial' ),
    __( 'Premium Quality','street-editorial' ),
    __( 'SS25',           'street-editorial' ),
];
?>

<div class="marquee-strip" aria-hidden="true">
    <div class="marquee-track">
        <?php foreach ( $items as $item ) : ?>
            <span class="marquee-item">
                <?php echo esc_html( strtoupper( $item ) ); ?>
                <span class="marquee-dot"></span>
            </span>
        <?php endforeach; ?>
    </div>
</div>
