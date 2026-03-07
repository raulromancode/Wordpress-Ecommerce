<?php
/**
 * Cabecera del tema.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-shell">
	<header class="site-header" data-header>
		<div class="site-header__inner container">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo wp_kses_post( overszclub_get_logo_markup() ); ?>
			</a>
			<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="site-navigation-panel">
				<span><?php echo esc_html__( 'Menu', 'overszclub' ); ?></span>
			</button>
			<nav id="site-navigation-panel" class="site-navigation" data-menu-panel aria-label="<?php echo esc_attr__( 'Menu principal', 'overszclub' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'primary-menu',
						'fallback_cb'    => 'overszclub_primary_menu_fallback',
					)
				);
				?>
			</nav>
			<div class="site-header__actions">
				<a class="header-link" href="<?php echo esc_url( home_url( '/lookbook/' ) ); ?>">
					<?php echo esc_html__( 'Lookbook', 'overszclub' ); ?>
				</a>
				<button class="header-cart" type="button" data-cart-toggle>
					<?php echo esc_html__( 'Carrito', 'overszclub' ); ?>
					<span class="cart-count" data-cart-count><?php echo esc_html( overszclub_has_woocommerce() && WC()->cart ? (string) WC()->cart->get_cart_contents_count() : '0' ); ?></span>
				</button>
			</div>
		</div>
	</header>
