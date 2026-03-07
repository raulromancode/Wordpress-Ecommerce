<?php
/**
 * Template Part: lookbook-grid.php
 * Galería masonry estilo editorial
 */

// Imágenes de ejemplo (en producción se usaría una galería de WP o ACF)
$images = $args['images'] ?? [
    [ 'src' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&q=80', 'num' => '01' ],
    [ 'src' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&q=80', 'num' => '02' ],
    [ 'src' => 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=800&q=80', 'num' => '03' ],
    [ 'src' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&q=80', 'num' => '04' ],
    [ 'src' => 'https://images.unsplash.com/photo-1516726817505-f5ed825624d8?w=800&q=80', 'num' => '05' ],
    [ 'src' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&q=80', 'num' => '06' ],
    [ 'src' => 'https://images.unsplash.com/photo-1520367745676-56196632073f?w=800&q=80', 'num' => '07' ],
    [ 'src' => 'https://images.unsplash.com/photo-1588099768531-a72d4a198538?w=800&q=80', 'num' => '08' ],
    [ 'src' => 'https://images.unsplash.com/photo-1495385794356-15371f348c31?w=800&q=80', 'num' => '09' ],
];
?>

<div class="lookbook-masonry">
    <?php foreach ( $images as $img ) : ?>
        <div class="lookbook-item reveal">
            <img src="<?php echo esc_url( $img['src'] ); ?>" alt="Look <?php echo esc_attr( $img['num'] ); ?>" loading="lazy">
            <div class="lookbook-item__caption">
                <span class="lookbook-item__num"><?php echo esc_html( $img['num'] ); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>
