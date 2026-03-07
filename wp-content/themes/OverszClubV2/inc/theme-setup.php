<?php
/**
 * OverszClubV2 — theme-setup.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Walker: menú plano sin ul/li ─────────────────────────── */
class OC_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $active  = in_array( 'current-menu-item', $item->classes ) ? ' active' : '';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( trim( $active ) ) . '">'
                 . esc_html( $item->title ) . '</a>';
    }
    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/* ── Fallback menú ────────────────────────────────────────── */
function oc_fallback_menu() {
    $shop  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' );
    $look  = get_permalink( get_page_by_path( 'lookbook' ) );
    $about = get_permalink( get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' ) );

    echo '<a href="' . esc_url( home_url() ) . '">Inicio</a>';
    echo '<a href="' . esc_url( $shop ) . '">Tienda</a>';
    if ( $look )  echo '<a href="' . esc_url( $look )  . '">Lookbook</a>';
    if ( $about ) echo '<a href="' . esc_url( $about ) . '">Nosotros</a>';
}

/* ── Setup ────────────────────────────────────────────────── */
function oc_theme_setup() {
    load_theme_textdomain( 'overszclubv2', OC_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'align-wide' );

    add_image_size( 'oc-card',   480, 640, true );
    add_image_size( 'oc-hero',  1800, 900, true );
    add_image_size( 'oc-square', 600, 600, true );

    register_nav_menus( [
        'primary' => __( 'Menú principal', 'overszclubv2' ),
        'footer'  => __( 'Menú footer',    'overszclubv2' ),
    ] );
}
add_action( 'after_setup_theme', 'oc_theme_setup' );

/* ── Widgets ──────────────────────────────────────────────── */
function oc_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Sidebar tienda', 'overszclubv2' ),
        'id'            => 'shop-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'oc_widgets_init' );
