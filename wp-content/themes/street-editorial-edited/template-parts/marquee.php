<?php
/**
 * Template Part: marquee.php
 * Barra de texto animada — texto desde el Customizer.
 */

$raw   = get_theme_mod(
    'se_marquee_text',
    'New Collection — Limited Drop — Free Shipping — Premium Quality — SS25 — Handcrafted'
);
$items = array_filter( array_map( 'trim', explode( '—', $raw ) ) );

// Si se pasan $args['items'] explícitamente, tienen prioridad
if ( ! empty( $args['items'] ) ) {
    $items = $args['items'];
}

// Duplicar items para loop infinito perfecto
$all_items = array_merge( array_values( $items ), array_values( $items ) );
?>

<div class="marquee-strip" aria-hidden="true">
    <div class="marquee-track">
        <?php foreach ( $all_items as $item ) : ?>
            <span class="marquee-item">
                <?php echo esc_html( strtoupper( trim( $item ) ) ); ?>
                <span class="marquee-dot"></span>
            </span>
        <?php endforeach; ?>
    </div>
</div>
