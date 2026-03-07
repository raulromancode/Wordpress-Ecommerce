<?php
/**
 * Template Part: lookbook-grid.php — Imágenes desde el Customizer.
 */

$images = [];
for ($i = 1; $i <= 9; $i++) {
    $url = get_theme_mod("oc_lookbook_img_{$i}", '');
    $images[] = ['src' => $url, 'num' => str_pad($i,2,'0',STR_PAD_LEFT), 'empty' => empty($url)];
}
?>

<div class="lookbook-grid">
    <?php foreach ($images as $img) : ?>
        <div class="lookbook-item reveal<?php echo $img['empty'] ? ' lookbook-item--empty' : ''; ?>">
            <?php if (!$img['empty']) : ?>
                <img src="<?php echo esc_url($img['src']); ?>"
                     alt="Look <?php echo esc_attr($img['num']); ?>"
                     loading="lazy">
            <?php else : ?>
                <div class="lookbook-placeholder-hint">
                    <?php if (is_customize_preview()) : ?>
                        📷 Imagen <?php echo esc_html($img['num']); ?><br>
                        <small>Personalizar → Lookbook</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <span class="lookbook-item__num"><?php echo esc_html($img['num']); ?></span>
        </div>
    <?php endforeach; ?>
</div>
