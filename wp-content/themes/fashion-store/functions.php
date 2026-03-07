<?php
/**
 * Fashion Store — functions.php
 */

/* ─── 1. Configuración del tema ─────────────────────────────── */
function fashion_setup() {
    load_theme_textdomain( 'fashion-store', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', [ 'search-form','comment-form','gallery','caption','style','script' ] );

    // WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Menús
    register_nav_menus( [
        'primary' => __( 'Menú principal', 'fashion-store' ),
        'footer'  => __( 'Menú footer',    'fashion-store' ),
    ] );
}
add_action( 'after_setup_theme', 'fashion_setup' );


/* ─── 2. Assets ─────────────────────────────────────────────── */
function fashion_enqueue() {
    // Google Fonts
    wp_enqueue_style( 'fashion-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap',
        [], null );

    // Estilos
    wp_enqueue_style( 'fashion-style', get_stylesheet_uri(), [], '1.0.0' );
    wp_enqueue_style( 'fashion-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['fashion-style'], '1.0.0' );

    // Scripts
    wp_enqueue_script( 'fashion-main',
        get_template_directory_uri() . '/assets/js/main.js',
        ['jquery'], '1.0.0', true );

    wp_localize_script( 'fashion-main', 'fashionAjax', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'fashion_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'fashion_enqueue' );

// Quitar estilos por defecto de WooCommerce
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


/* ─── 3. AJAX — fragmento del carrito ───────────────────────── */
function fashion_cart_fragment() {
    ob_start();
    $cart = WC()->cart;

    if ( $cart->is_empty() ) {
        echo '<div class="cart-empty">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <p>Tu carrito está vacío</p>
              </div>';
    } else {
        foreach ( $cart->get_cart() as $key => $item ) {
            $product = $item['data'];
            $img     = get_the_post_thumbnail_url( $item['product_id'], 'thumbnail' );
            $name    = $product->get_name();
            $qty     = $item['quantity'];
            $sub     = wc_price( $item['line_total'] );
            $meta    = '';
            if ( ! empty( $item['variation'] ) ) {
                foreach ( $item['variation'] as $attr => $val ) {
                    $meta .= ucfirst( str_replace( 'attribute_pa_', '', $attr ) ) . ': ' . $val . '  ';
                }
            }
            echo '<div class="cart-line">
                    <img class="cart-line__img" src="' . esc_url( $img ) . '" alt="' . esc_attr( $name ) . '">
                    <div class="cart-line__info">
                        <p class="cart-line__name">' . esc_html( $name ) . '</p>
                        <p class="cart-line__meta">' . esc_html( trim($meta) ) . ' &middot; Qty: ' . $qty . '</p>
                        <p class="cart-line__price">' . $sub . '</p>
                    </div>
                    <button class="cart-line__rm" data-key="' . esc_attr( $key ) . '" aria-label="Eliminar">&times;</button>
                  </div>';
        }
    }
    $html = ob_get_clean();

    wp_send_json_success( [
        'items_html' => $html,
        'count'      => $cart->get_cart_contents_count(),
        'total'      => strip_tags( $cart->get_cart_total() ),
    ] );
}
add_action( 'wp_ajax_fashion_cart_fragment',        'fashion_cart_fragment' );
add_action( 'wp_ajax_nopriv_fashion_cart_fragment', 'fashion_cart_fragment' );


/* ─── 4. AJAX — eliminar item del carrito ───────────────────── */
function fashion_remove_item() {
    $key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    if ( $key ) WC()->cart->remove_cart_item( $key );
    wp_send_json_success();
}
add_action( 'wp_ajax_fashion_remove_item',        'fashion_remove_item' );
add_action( 'wp_ajax_nopriv_fashion_remove_item', 'fashion_remove_item' );


/* ─── 5. WooCommerce — configuración ────────────────────────── */
add_filter( 'loop_shop_columns',   fn() => 3 );
add_filter( 'loop_shop_per_page',  fn() => 12, 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );


/* ─── 6. Widgets ─────────────────────────────────────────────── */
function fashion_widgets() {
    register_sidebar( [
        'name'          => __( 'Sidebar tienda', 'fashion-store' ),
        'id'            => 'shop-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--c-accent);margin-bottom:12px;">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'fashion_widgets' );


/* ─── 7. Walker de menú simplificado ───────────────────────── */
class Fashion_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $cls = in_array( 'current-menu-item', $item->classes ) ? ' current-menu-item' : '';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( trim($cls) ) . '">' . esc_html( $item->title ) . '</a>';
    }
    function end_el( &$output, $item, $depth = 0, $args = null ) {}
    function start_lvl( &$output, $depth = 0, $args = null ) {}
    function end_lvl( &$output, $depth = 0, $args = null ) {}
}
