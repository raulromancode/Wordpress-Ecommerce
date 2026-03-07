<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ══ NAVBAR ══════════════════════════════════════════════════ -->
<header class="site-header">
    <div class="container">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-logo">
            <?php bloginfo('name'); ?>
        </a>

        <!-- Menú principal (desktop) -->
        <nav class="nav-menu" aria-label="Menú principal">
            <?php wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new Fashion_Walker(),
                'fallback_cb'    => function() {
                    // Links de ejemplo si no hay menú creado
                    echo '<a href="' . esc_url( home_url('/') ) . '">Inicio</a>';
                    echo '<a href="' . esc_url( wc_get_page_permalink('shop') ) . '">Tienda</a>';
                    $about = get_page_by_path('sobre-nosotros');
                    if ($about) echo '<a href="' . esc_url( get_permalink($about) ) . '">Nosotros</a>';
                },
            ]); ?>
        </nav>

        <!-- Acciones -->
        <div class="nav-actions">
            <!-- Botón carrito -->
            <button id="cart-trigger" class="nav-icon-btn" aria-label="Carrito">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <?php
                $cnt = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                if ( $cnt > 0 ) echo '<span class="cart-bubble" id="cart-item-count">' . $cnt . '</span>';
                else echo '<span class="cart-bubble" id="cart-item-count" style="display:none">' . $cnt . '</span>';
                ?>
            </button>

            <!-- Hamburguesa (móvil) -->
            <button id="hamburger" class="hamburger" aria-label="Menú">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</header>

<!-- Menú móvil -->
<nav id="mobile-nav" class="mobile-nav" aria-label="Menú móvil">
    <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'walker'         => new Fashion_Walker(),
        'fallback_cb'    => function() {
            echo '<a href="' . esc_url( home_url('/') ) . '">Inicio</a>';
            echo '<a href="' . esc_url( wc_get_page_permalink('shop') ) . '">Tienda</a>';
        },
    ]); ?>
</nav>

<!-- ══ BACKDROP + DRAWER ═══════════════════════════════════════ -->
<div id="cart-backdrop" class="cart-backdrop"></div>

<aside id="cart-drawer" class="cart-drawer" aria-label="Carrito de compra">
    <div class="cart-drawer__head">
        <span class="cart-drawer__title">Carrito</span>
        <button id="cart-close" class="cart-drawer__close" aria-label="Cerrar">&times;</button>
    </div>

    <div class="cart-drawer__body" id="cart-drawer-items">
        <?php if ( WC()->cart && ! WC()->cart->is_empty() ) :
            foreach ( WC()->cart->get_cart() as $key => $item ) :
                $product = $item['data'];
                $img     = get_the_post_thumbnail_url( $item['product_id'], 'thumbnail' );
                $name    = $product->get_name();
                $qty     = $item['quantity'];
                $sub     = wc_price( $item['line_total'] );
                $meta    = '';
                if ( ! empty( $item['variation'] ) ) {
                    foreach ( $item['variation'] as $attr => $val ) {
                        $meta .= ucfirst( str_replace('attribute_pa_','', $attr) ) . ': ' . $val . '  ';
                    }
                }
        ?>
            <div class="cart-line">
                <img class="cart-line__img" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                <div class="cart-line__info">
                    <p class="cart-line__name"><?php echo esc_html($name); ?></p>
                    <p class="cart-line__meta"><?php echo esc_html(trim($meta)); ?> &middot; Qty: <?php echo $qty; ?></p>
                    <p class="cart-line__price"><?php echo $sub; ?></p>
                </div>
                <button class="cart-line__rm" data-key="<?php echo esc_attr($key); ?>" aria-label="Eliminar">&times;</button>
            </div>
        <?php endforeach; else : ?>
            <div class="cart-empty">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <p>Tu carrito está vacío</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="cart-drawer__foot" id="cart-drawer-foot"
         <?php if ( WC()->cart && WC()->cart->is_empty() ) echo 'style="display:none"'; ?>>
        <div class="cart-total-row">
            <span class="cart-total-label">Total</span>
            <span class="cart-total-val" id="cart-drawer-total">
                <?php echo WC()->cart ? strip_tags( WC()->cart->get_cart_total() ) : '0,00 €'; ?>
            </span>
        </div>
        <a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn-primary cart-drawer__cta">
            Finalizar compra
        </a>
        <a href="<?php echo wc_get_cart_url(); ?>" class="cart-drawer__view">Ver carrito</a>
    </div>
</aside>
