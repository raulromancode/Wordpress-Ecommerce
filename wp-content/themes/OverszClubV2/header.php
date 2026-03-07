<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ══ NAVBAR ══════════════════════════════════════════════ -->
<header class="site-header" id="site-header" role="banner">
    <div class="wrap nav-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url('/') ); ?>"
           class="nav-logo"
           aria-label="<?php bloginfo('name'); ?> — Inicio">
            <?php echo oc_render_logo(); ?>
        </a>

        <!-- Menú desktop -->
        <nav class="nav-links" aria-label="Menú principal">
            <?php wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new OC_Walker(),
                'fallback_cb'    => 'oc_fallback_menu',
            ]); ?>
        </nav>

        <!-- Acciones -->
        <div class="nav-actions">
            <!-- Carrito -->
            <button id="oc-cart-trigger"
                    class="nav-icon"
                    aria-label="<?php esc_attr_e('Abrir carrito','overszclubv2'); ?>"
                    aria-expanded="false">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <?php
                $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                echo '<span id="oc-cart-count" class="cart-badge" style="display:' . ($count > 0 ? 'flex' : 'none') . '">' . absint($count) . '</span>';
                ?>
            </button>

            <!-- Hamburguesa -->
            <button id="oc-hamburger"
                    class="hamburger"
                    aria-label="<?php esc_attr_e('Abrir menú','overszclubv2'); ?>"
                    aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</header>

<!-- Menú móvil -->
<nav id="oc-mobile-menu" class="mobile-menu" aria-label="Menú móvil">
    <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'walker'         => new OC_Walker(),
        'fallback_cb'    => 'oc_fallback_menu',
    ]); ?>
</nav>

<!-- ══ CART DRAWER ════════════════════════════════════════ -->
<div id="oc-cart-overlay" class="cart-overlay" aria-hidden="true"></div>

<aside id="oc-cart-drawer"
       class="cart-drawer"
       role="dialog"
       aria-label="<?php esc_attr_e('Carrito','overszclubv2'); ?>"
       aria-modal="true">

    <div class="cart-drawer__head">
        <h2 class="cart-drawer__title">
            Carrito
            <span class="cart-drawer__badge"><?php echo absint( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
        </h2>
        <button id="oc-cart-close"
                class="cart-close"
                aria-label="<?php esc_attr_e('Cerrar carrito','overszclubv2'); ?>">
            &times;
        </button>
    </div>

    <div class="cart-drawer__body" id="oc-cart-body">
        <?php echo oc_build_cart_html(); ?>
    </div>

    <div id="oc-cart-foot"
         class="cart-drawer__foot"
         <?php if ( WC()->cart && WC()->cart->is_empty() ) echo 'style="display:none"'; ?>>

        <div class="cart-total">
            <span class="cart-total__label">Total</span>
            <span class="cart-total__amount" id="oc-cart-total">
                <?php echo WC()->cart ? strip_tags( WC()->cart->get_cart_total() ) : '0,00 €'; ?>
            </span>
        </div>

        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-checkout-btn">
            Finalizar Compra
        </a>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-view-cart">
            Ver carrito completo
        </a>
    </div>

</aside>

<!-- Notificación -->
<div id="oc-notif" class="added-notif" role="status" aria-live="polite"></div>
