<?php
/**
 * Street Editorial — theme-setup.php
 * Configuración inicial del tema
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function se_theme_setup() {

    // Traducciones
    load_theme_textdomain( 'street-editorial', get_template_directory() . '/languages' );

    // Etiqueta <title>
    add_theme_support( 'title-tag' );

    // Imágenes destacadas
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'se-card',    480, 640, true );
    add_image_size( 'se-hero',   1800, 900, true );
    add_image_size( 'se-square',  600, 600, true );

    // HTML5
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );

    // Logo personalizado
    add_theme_support( 'custom-logo' );

    // Gutenberg
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-color-palette', [
        [ 'name' => 'Acento',  'slug' => 'accent', 'color' => '#e8c547' ],
        [ 'name' => 'Negro',   'slug' => 'black',  'color' => '#0a0a0a' ],
        [ 'name' => 'Blanco',  'slug' => 'white',  'color' => '#f5f1eb' ],
    ] );

    // WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Menús
    register_nav_menus( [
        'primary' => __( 'Menú principal',   'street-editorial' ),
        'footer'  => __( 'Menú pie de página', 'street-editorial' ),
    ] );
}
add_action( 'after_setup_theme', 'se_theme_setup' );


/**
 * Widgets
 */
function se_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Sidebar tienda', 'street-editorial' ),
        'id'            => 'shop-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title" style="font-size:10px;letter-spacing:4px;text-transform:uppercase;color:var(--accent);margin-bottom:14px;">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'se_widgets_init' );


/**
 * Walker para menú sin <ul>/<li>
 */
class SE_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $cls = in_array( 'current-menu-item', $item->classes ) ? ' active' : '';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( trim( $cls ) ) . '">'
                 . esc_html( $item->title ) . '</a>';
    }
    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
}
