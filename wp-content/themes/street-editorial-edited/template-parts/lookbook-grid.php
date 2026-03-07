<?php
/**
 * Template Part: lookbook-grid.php
 * Grid masonry editorial.
 *
 * IMÁGENES: Apariencia → Personalizar → Street Editorial Theme → Lookbook — Imágenes
 * Si no se sube ninguna imagen, muestra bloques CSS con instrucciones.
 */

// ── Leer imágenes del Customizer ─────────────────────────────────────
$images = [];
for ( $i = 1; $i <= 9; $i++ ) {
    $url = get_theme_mod( "se_lookbook_img_{$i}", '' );
    $images[] = [
        'src'   => $url,   // Vacío si no se subió
        'num'   => str_pad( $i, 2, '0', STR_PAD_LEFT ),
        'empty' => empty( $url ),
    ];
}

// Si $args fuerza imágenes externas (uso programático), se respetan
if ( ! empty( $args['images'] ) ) {
    $images = $args['images'];
}

$placeholder_url = get_template_directory_uri() . '/assets/images/placeholder-lookbook.svg';
?>

<div class="lookbook-masonry">
    <?php foreach ( $images as $img ) : ?>
        <div class="lookbook-item reveal <?php echo $img['empty'] ? 'lookbook-item--empty' : ''; ?>">

            <?php if ( ! $img['empty'] ) : ?>
                <!-- Imagen subida desde el Customizer -->
                <img
                    src="<?php echo esc_url( $img['src'] ); ?>"
                    alt="<?php echo esc_attr( 'Look ' . $img['num'] ); ?>"
                    loading="lazy"
                >
            <?php else : ?>
                <!-- Placeholder local: sin peticiones externas -->
                <img
                    src="<?php echo esc_url( $placeholder_url ); ?>"
                    alt=""
                    loading="lazy"
                    aria-hidden="true"
                >
                <?php if ( is_customize_preview() ) : ?>
                    <div class="lookbook-item__hint">
                        📷 Imagen <?php echo esc_html( $img['num'] ); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="lookbook-item__caption">
                <span class="lookbook-item__num"><?php echo esc_html( $img['num'] ); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if ( is_customize_preview() ) : ?>
    <p class="lookbook-customizer-hint">
        📷 Sube tus imágenes en <strong>Personalizar → Lookbook — Imágenes</strong>
    </p>
<?php endif; ?>
