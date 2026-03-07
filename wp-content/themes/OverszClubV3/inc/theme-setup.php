<?php
/**
 * Setup del tema.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_get_theme_version() {
	$theme = wp_get_theme( 'OverszClub' );

	return $theme->get( 'Version' ) ? $theme->get( 'Version' ) : '1.0.0';
}

function overszclub_get_default_mods() {
	return array(
		'hero_title'             => 'NUEVA ERA DEL STREETWEAR',
		'hero_button_text'       => 'VER COLECCION',
		'hero_button_url'        => home_url( '/tienda/' ),
		'marquee_text'           => 'NUEVA COLECCION - ENVIO GRATIS - EDICION LIMITADA',
		'about_content'          => 'OverszClub combina siluetas sobrias, materiales premium y una direccion creativa editorial pensada para una nueva generacion.',
		'theme_accent_color'     => '#e8c547',
		'theme_background_color' => '#070707',
		'theme_surface_color'    => '#111111',
		'theme_text_color'       => '#f5f1e8',
		'social_instagram'       => '#',
		'social_tiktok'          => '#',
		'social_pinterest'       => '#',
		'lookbook_heading'       => 'LOOKBOOK',
		'lookbook_intro'         => 'Explora una seleccion visual con hotspots interactivos para descubrir cada producto.',
		'shop_categories_title'  => 'EXPLORA LA TIENDA POR COLECCIONES',
		'shop_categories_intro'  => 'Descubre todas las categorias disponibles y entra en cada una para ver sus productos.',
		'shop_categories_mode'   => 'all',
		'shop_categories_limit'  => 6,
	);
}

function overszclub_get_theme_mod( $key ) {
	$defaults = overszclub_get_default_mods();
	$fallback = $defaults[ $key ] ?? '';

	return get_theme_mod( $key, $fallback );
}

function overszclub_has_woocommerce() {
	return class_exists( 'WooCommerce' );
}

function overszclub_theme_setup() {
	load_theme_textdomain( 'overszclub', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 72, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Acento', 'overszclub' ),
				'slug'  => 'accent',
				'color' => '#e8c547',
			),
			array(
				'name'  => __( 'Fondo', 'overszclub' ),
				'slug'  => 'background',
				'color' => '#070707',
			),
			array(
				'name'  => __( 'Superficie', 'overszclub' ),
				'slug'  => 'surface',
				'color' => '#111111',
			),
			array(
				'name'  => __( 'Texto', 'overszclub' ),
				'slug'  => 'text',
				'color' => '#f5f1e8',
			),
		)
	);
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Caption', 'overszclub' ),
				'shortName' => __( 'XS', 'overszclub' ),
				'size' => 14,
				'slug' => 'caption',
			),
			array(
				'name' => __( 'Base', 'overszclub' ),
				'shortName' => __( 'M', 'overszclub' ),
				'size' => 18,
				'slug' => 'base',
			),
			array(
				'name' => __( 'Display', 'overszclub' ),
				'shortName' => __( 'XL', 'overszclub' ),
				'size' => 56,
				'slug' => 'display',
			),
		)
	);
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'appearance-tools' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Menu principal', 'overszclub' ),
			'footer'  => esc_html__( 'Menu footer', 'overszclub' ),
		)
	);

	if ( overszclub_has_woocommerce() ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'overszclub_theme_setup' );

function overszclub_set_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'overszclub_content_width', 1440 );
}
add_action( 'after_setup_theme', 'overszclub_set_content_width', 0 );

function overszclub_register_sidebars() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'overszclub' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Widgets del footer.', 'overszclub' ),
			'before_widget' => '<section class="footer-widget">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="footer-widget__title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'overszclub_register_sidebars' );

function overszclub_body_classes( $classes ) {
	$classes[] = 'overszclub-theme';

	if ( is_front_page() ) {
		$classes[] = 'is-editorial-home';
	}

	if ( overszclub_has_woocommerce() ) {
		$classes[] = 'has-woocommerce';
	}

	return $classes;
}
add_filter( 'body_class', 'overszclub_body_classes' );

function overszclub_get_logo_markup() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( $custom_logo_id ) {
		return wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'site-logo__image', 'alt' => get_bloginfo( 'name' ) ) );
	}

	return esc_html( get_bloginfo( 'name' ) );
}

function overszclub_get_current_shop_title() {
	if ( is_tax() || is_product_category() || is_product_tag() ) {
		return single_term_title( '', false );
	}

	if ( is_post_type_archive( 'product' ) ) {
		return post_type_archive_title( '', false );
	}

	if ( is_page() ) {
		return get_the_title();
	}

	return __( 'Coleccion completa', 'overszclub' );
}

function overszclub_primary_menu_fallback() {
	echo '<ul class="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'overszclub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/tienda/' ) ) . '">' . esc_html__( 'Tienda', 'overszclub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/lookbook/' ) ) . '">' . esc_html__( 'Lookbook', 'overszclub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'Marca', 'overszclub' ) . '</a></li>';
	echo '</ul>';
}

function overszclub_footer_menu_fallback() {
	echo '<ul class="footer-menu">';
	echo '<li><a href="' . esc_url( home_url( '/tienda/' ) ) . '">' . esc_html__( 'Tienda', 'overszclub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/lookbook/' ) ) . '">' . esc_html__( 'Lookbook', 'overszclub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'Sobre la marca', 'overszclub' ) . '</a></li>';
	echo '</ul>';
}

function overszclub_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['data-menu-link'] = 'true';
	}

	if ( 0 === (int) $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-expanded'] = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'overszclub_nav_menu_link_attributes', 10, 4 );

function overszclub_nav_menu_start_el( $item_output, $item, $depth, $args ) {
	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $item_output;
	}

	if ( 0 === (int) $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$item_output = str_replace( '</a>', '<span class="menu-item-indicator"></span></a>', $item_output );
	}

	if ( $item->description && $depth > 0 ) {
		$item_output = str_replace(
			'</a>',
			'<span class="menu-item-description">' . esc_html( $item->description ) . '</span></a>',
			$item_output
		);
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'overszclub_nav_menu_start_el', 10, 4 );
