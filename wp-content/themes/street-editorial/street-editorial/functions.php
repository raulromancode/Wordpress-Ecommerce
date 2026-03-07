<?php
/**
 * Street Editorial — functions.php
 * Punto de entrada: carga todos los módulos del tema.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Carga de módulos ─────────────────────────────────────── */
$modules = [
    'inc/theme-setup.php',
    'inc/enqueue.php',
    'inc/woo-custom.php',
    'inc/cart-functions.php',
];

foreach ( $modules as $file ) {
    $path = get_template_directory() . '/' . $file;
    if ( file_exists( $path ) ) {
        require_once $path;
    }
}
