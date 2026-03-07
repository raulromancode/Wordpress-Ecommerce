<?php
/**
 * Bootstrap principal del tema OverszClub.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$overszclub_includes = array(
	'/inc/theme-setup.php',
	'/inc/enqueue.php',
	'/inc/customizer.php',
	'/inc/woo-functions.php',
	'/inc/ajax-functions.php',
	'/inc/cart-functions.php',
);

foreach ( $overszclub_includes as $overszclub_file ) {
	$overszclub_path = get_template_directory() . $overszclub_file;

	if ( file_exists( $overszclub_path ) ) {
		require_once $overszclub_path;
	}
}

