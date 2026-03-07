<?php
/**
 * Template Part: marquee.php — Ticker animado infinito.
 */

$raw   = get_theme_mod('oc_marquee_text', 'Nueva Colección — Drops Limitados — Envío Gratis — Streetwear Premium');
$items = array_filter(array_map('trim', explode('—', $raw)));
// Duplicar para loop suave
$all = array_merge(array_values($items), array_values($items));
?>

<div class="marquee-strip" aria-hidden="true">
    <div class="marquee-track">
        <?php foreach ($all as $item) : ?>
            <span class="marquee-item"><?php echo esc_html(strtoupper(trim($item))); ?></span>
        <?php endforeach; ?>
    </div>
</div>
