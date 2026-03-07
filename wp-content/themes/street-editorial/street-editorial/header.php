<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ══ NAVBAR ══════════════════════════════════════════════════ -->
<header class="site-header" role="banner">
    <div class="wrap nav-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Inicio">
            STREET<span>—</span>EDITORIAL
        </a>

        <!-- Menú desktop -->
        <nav class="nav-links" aria-label="Menú principal">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new SE_Walker(),
                'fallback_cb'    => function() {
                    $shop    = wc_get_page_permalink( 'shop' );
                    $look    = get_permalink( get_page_by_path( 'lookbook' ) );
                    $about   = get_permalink( get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' ) );
                    echo '<a href="' . esc_url( home_url() )  . '">Home</a>';
                    echo '<a href="' . esc_url( $shop )       . '">Shop</a>';
                    if ( $look )  echo '<a href="' . esc_url( $look )  . '">Lookbook</a>';
                    if ( $about ) echo '<a href="' . esc_url( $about ) . '">About</a>';
                },
            ] );
            ?>
        </nav>

        <!-- Acciones -->
        <div class="nav-actions">
            <!-- Carrito -->
            <button id="cart-trigger" class="nav-icon" aria-label="Abrir carrito">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <?php
                $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                $style = $count > 0 ? '' : 'display:none';
                echo '<span class="cart-badge" id="cart-count" style="' . esc_attr($style) . '">' . absint($count) . '</span>';
                ?>
            </button>

            <!-- Hamburguesa -->
            <button id="hamburger" class="hamburger" aria-label="Menú móvil" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</header>

<!-- Menú móvil -->
<nav id="mobile-menu" class="mobile-menu" aria-label="Menú móvil">
    <?php
    wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'walker'         => new SE_Walker(),
        'fallback_cb'    => function() {
            echo '<a href="' . esc_url( home_url() )                     . '">Home</a>';
            echo '<a href="' . esc_url( wc_get_page_permalink('shop') ) . '">Shop</a>';
            $look  = get_page_by_path( 'lookbook' );
            $about = get_page_by_path( 'about' ) ?? get_page_by_path( 'sobre-nosotros' );
            if ( $look )  echo '<a href="' . esc_url( get_permalink($look)  ) . '">Lookbook</a>';
            if ( $about ) echo '<a href="' . esc_url( get_permalink($about) ) . '">About</a>';
        },
    ] );
    ?>
</nav>

<!-- ══ CART DRAWER ══════════════════════════════════════════════ -->
<div id="cart-overlay" class="cart-overlay" aria-hidden="true"></div>

<aside id="cart-drawer" class="cart-drawer" role="dialog" aria-label="Carrito de compra" aria-modal="true">

    <div class="cart-drawer__head">
        <h2 class="cart-drawer__title">Cart</h2>
        <button id="cart-close" class="cart-close" aria-label="Cerrar carrito">&times;</button>
    </div>

    <div class="cart-drawer__body" id="cart-items">
        <?php
        if ( WC()->cart && ! WC()->cart->is_empty() ) {
            echo se_build_cart_items_html();
        } else {
            echo '<div class="cart-empty">
                    <svg fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <p>Tu carrito está vacío</p>
                  </div>';
        }
        ?>
    </div>

    <div id="cart-footer"
         class="cart-drawer__foot"
         <?php if ( WC()->cart && WC()->cart->is_empty() ) echo 'style="display:none"'; ?>>

        <div class="cart-total">
            <span class="cart-total__label">Total</span>
            <span class="cart-total__value" id="cart-total">
                <?php echo WC()->cart ? strip_tags( WC()->cart->get_cart_total() ) : '0,00 €'; ?>
            </span>
        </div>

        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-checkout-btn">
            Checkout
        </a>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-view-btn">
            Ver carrito completo
        </a>
    </div>

</aside>
<!-- /cart-drawer -->

<!-- Notificación añadido al carrito -->
<div id="added-notif" class="added-notif" role="status" aria-live="polite"></div>
