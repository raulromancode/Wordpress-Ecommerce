<?php
/**
 * OverszClubV2 — functions.php
 * Carga todos los módulos del tema en orden correcto.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'OC_VERSION', '2.0.0' );
define( 'OC_DIR',     get_template_directory() );
define( 'OC_URI',     get_template_directory_uri() );

$modules = [
    'inc/theme-setup.php',
    'inc/customizer.php',
    'inc/enqueue.php',
    'inc/woo-functions.php',
    'inc/cart-functions.php',
];

foreach ( $modules as $file ) {
    $path = OC_DIR . '/' . $file;
    if ( file_exists( $path ) ) {
        require_once $path;
    }
}
