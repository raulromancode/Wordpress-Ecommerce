<?php
/**
 * Street Editorial — customizer.php
 * Configuración completa del Customizer de WordPress.
 *
 * Secciones:
 *  1. Identidad / Logo
 *  2. Colores del tema
 *  3. Hero principal
 *  4. Marquee / Ticker
 *  5. Sección About (home)
 *  6. Lookbook (imágenes)
 *  7. Redes sociales
 *  8. Footer
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ══════════════════════════════════════════════════════════════
   REGISTRO DE SETTINGS, CONTROLES Y SECCIONES
   ══════════════════════════════════════════════════════════════ */
function se_customizer_register( WP_Customize_Manager $wp_customize ) {

    /* ────────────────────────────────────────────────────────────
       PANEL PRINCIPAL
       ──────────────────────────────────────────────────────────── */
    $wp_customize->add_panel( 'se_panel', [
        'title'       => __( 'Street Editorial — Theme', 'street-editorial' ),
        'description' => __( 'Personaliza todas las secciones del tema.', 'street-editorial' ),
        'priority'    => 10,
    ] );

    /* ════════════════════════════════════════════════════════════
       1. IDENTIDAD / LOGO
       ════════════════════════════════════════════════════════════ */
    // La sección "title_tagline" de WordPress ya gestiona el logo personalizado.
    // Añadimos aquí el texto alternativo del logo en caso de que no haya imagen.

    $wp_customize->add_section( 'se_identity', [
        'title'    => __( 'Identidad del sitio', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 5,
    ] );

    // Texto del logo (cuando no hay imagen)
    $wp_customize->add_setting( 'se_logo_text', [
        'default'           => 'STREET—EDITORIAL',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_logo_text', [
        'label'       => __( 'Texto del logo (sin imagen)', 'street-editorial' ),
        'description' => __( 'Se muestra si no has subido un logo en Identidad del sitio.', 'street-editorial' ),
        'section'     => 'se_identity',
        'type'        => 'text',
    ] );

    /* ════════════════════════════════════════════════════════════
       2. COLORES
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_colors', [
        'title'    => __( 'Colores del tema', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 10,
    ] );

    // Color de acento
    $wp_customize->add_setting( 'se_color_accent', [
        'default'           => '#e8c547',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'se_color_accent', [
            'label'   => __( 'Color de acento', 'street-editorial' ),
            'section' => 'se_colors',
        ]
    ) );

    // Color de fondo principal
    $wp_customize->add_setting( 'se_color_bg', [
        'default'           => '#0a0a0a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'se_color_bg', [
            'label'   => __( 'Color de fondo', 'street-editorial' ),
            'section' => 'se_colors',
        ]
    ) );

    // Color de texto principal
    $wp_customize->add_setting( 'se_color_text', [
        'default'           => '#f5f1eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'se_color_text', [
            'label'   => __( 'Color de texto', 'street-editorial' ),
            'section' => 'se_colors',
        ]
    ) );

    // Color de texto secundario/muted
    $wp_customize->add_setting( 'se_color_muted', [
        'default'           => '#777770',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'se_color_muted', [
            'label'   => __( 'Color de texto secundario', 'street-editorial' ),
            'section' => 'se_colors',
        ]
    ) );

    /* ════════════════════════════════════════════════════════════
       3. HERO
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_hero', [
        'title'    => __( 'Hero — Portada', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 20,
    ] );

    // Imagen de fondo del Hero
    $wp_customize->add_setting( 'se_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize, 'se_hero_image', [
            'label'       => __( 'Imagen de fondo', 'street-editorial' ),
            'description' => __( 'Recomendado: 1800×900 px.', 'street-editorial' ),
            'section'     => 'se_hero',
        ]
    ) );

    // Eyebrow (texto pequeño sobre el título)
    $wp_customize->add_setting( 'se_hero_eyebrow', [
        'default'           => 'Nueva Colección — SS25',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_eyebrow', [
        'label'   => __( 'Eyebrow (texto pequeño superior)', 'street-editorial' ),
        'section' => 'se_hero',
        'type'    => 'text',
    ] );

    // Título del Hero (línea 1)
    $wp_customize->add_setting( 'se_hero_title_1', [
        'default'           => 'Wear the',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_title_1', [
        'label'       => __( 'Título — línea 1', 'street-editorial' ),
        'description' => __( 'Texto en blanco.', 'street-editorial' ),
        'section'     => 'se_hero',
        'type'        => 'text',
    ] );

    // Título del Hero (línea 2 — en color acento)
    $wp_customize->add_setting( 'se_hero_title_2', [
        'default'           => 'streets.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_title_2', [
        'label'       => __( 'Título — línea 2 (color acento)', 'street-editorial' ),
        'description' => __( 'Se muestra en cursiva con el color de acento.', 'street-editorial' ),
        'section'     => 'se_hero',
        'type'        => 'text',
    ] );

    // Subtítulo
    $wp_customize->add_setting( 'se_hero_subtitle', [
        'default'           => 'Ropa streetwear con identidad editorial. Diseñada para destacar, construida para durar.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_subtitle', [
        'label'   => __( 'Subtítulo', 'street-editorial' ),
        'section' => 'se_hero',
        'type'    => 'textarea',
    ] );

    // Texto del botón principal
    $wp_customize->add_setting( 'se_hero_btn_text', [
        'default'           => 'Shop Now',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_btn_text', [
        'label'   => __( 'Texto del botón principal', 'street-editorial' ),
        'section' => 'se_hero',
        'type'    => 'text',
    ] );

    // URL del botón principal
    $wp_customize->add_setting( 'se_hero_btn_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_btn_url', [
        'label'       => __( 'URL del botón principal', 'street-editorial' ),
        'description' => __( 'Dejar vacío para usar la URL de la Tienda automáticamente.', 'street-editorial' ),
        'section'     => 'se_hero',
        'type'        => 'url',
    ] );

    // Texto del botón secundario
    $wp_customize->add_setting( 'se_hero_btn2_text', [
        'default'           => 'Our Story',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_hero_btn2_text', [
        'label'   => __( 'Texto del botón secundario', 'street-editorial' ),
        'section' => 'se_hero',
        'type'    => 'text',
    ] );

    /* ════════════════════════════════════════════════════════════
       4. MARQUEE / TICKER
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_marquee', [
        'title'    => __( 'Marquee / Ticker animado', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 30,
    ] );

    $wp_customize->add_setting( 'se_marquee_text', [
        'default'           => 'New Collection — Limited Drop — Free Shipping — Premium Quality — SS25 — Handcrafted',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_marquee_text', [
        'label'       => __( 'Texto del marquee', 'street-editorial' ),
        'description' => __( 'Separa los bloques con " — " (espacio, guion, espacio).', 'street-editorial' ),
        'section'     => 'se_marquee',
        'type'        => 'textarea',
    ] );

    /* ════════════════════════════════════════════════════════════
       5. ABOUT — SECCIÓN HOME
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_about', [
        'title'    => __( 'About — Sección en Home', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 40,
    ] );

    // Imagen de la sección About en Home
    $wp_customize->add_setting( 'se_about_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize, 'se_about_image', [
            'label'       => __( 'Imagen de la sección About', 'street-editorial' ),
            'description' => __( 'Aparece a la izquierda en la sección de About de la home.', 'street-editorial' ),
            'section'     => 'se_about',
        ]
    ) );

    // Eyebrow de About
    $wp_customize->add_setting( 'se_about_eyebrow', [
        'default'           => 'Our Identity',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_about_eyebrow', [
        'label'   => __( 'Eyebrow (texto pequeño)', 'street-editorial' ),
        'section' => 'se_about',
        'type'    => 'text',
    ] );

    // Título de About
    $wp_customize->add_setting( 'se_about_title', [
        'default'           => "Born on the streets.\nBuilt for the stage.",
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_about_title', [
        'label'       => __( 'Título (saltos de línea permitidos)', 'street-editorial' ),
        'section'     => 'se_about',
        'type'        => 'textarea',
    ] );

    // Texto de About
    $wp_customize->add_setting( 'se_about_text', [
        'default'           => 'Street Editorial nació con una sola convicción: la ropa streetwear merece producción editorial. Cada pieza es un statement.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_about_text', [
        'label'   => __( 'Texto descriptivo', 'street-editorial' ),
        'section' => 'se_about',
        'type'    => 'textarea',
    ] );

    // Texto del botón de About
    $wp_customize->add_setting( 'se_about_btn', [
        'default'           => 'Read Our Story',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_about_btn', [
        'label'   => __( 'Texto del botón', 'street-editorial' ),
        'section' => 'se_about',
        'type'    => 'text',
    ] );

    /* ════════════════════════════════════════════════════════════
       6. LOOKBOOK — IMÁGENES
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_lookbook', [
        'title'       => __( 'Lookbook — Imágenes', 'street-editorial' ),
        'description' => __( 'Sube hasta 9 imágenes para el grid editorial del lookbook.', 'street-editorial' ),
        'panel'       => 'se_panel',
        'priority'    => 50,
    ] );

    for ( $i = 1; $i <= 9; $i++ ) {
        $wp_customize->add_setting( "se_lookbook_img_{$i}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize, "se_lookbook_img_{$i}", [
                /* translators: %d = número de imagen */
                'label'   => sprintf( __( 'Imagen %d', 'street-editorial' ), $i ),
                'section' => 'se_lookbook',
            ]
        ) );
    }

    /* ════════════════════════════════════════════════════════════
       7. REDES SOCIALES
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_social', [
        'title'    => __( 'Redes sociales', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 60,
    ] );

    $social_networks = [
        'instagram' => 'Instagram',
        'tiktok'    => 'TikTok',
        'twitter'   => 'X / Twitter',
        'facebook'  => 'Facebook',
        'youtube'   => 'YouTube',
        'pinterest' => 'Pinterest',
    ];

    foreach ( $social_networks as $key => $label ) {
        $wp_customize->add_setting( "se_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( "se_social_{$key}", [
            'label'       => $label,
            'description' => __( 'URL completa (https://...)', 'street-editorial' ),
            'section'     => 'se_social',
            'type'        => 'url',
        ] );
    }

    /* ════════════════════════════════════════════════════════════
       8. FOOTER
       ════════════════════════════════════════════════════════════ */
    $wp_customize->add_section( 'se_footer', [
        'title'    => __( 'Footer', 'street-editorial' ),
        'panel'    => 'se_panel',
        'priority' => 70,
    ] );

    $wp_customize->add_setting( 'se_footer_tagline', [
        'default'           => 'Ropa streetwear con identidad editorial.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_footer_tagline', [
        'label'   => __( 'Tagline del footer', 'street-editorial' ),
        'section' => 'se_footer',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'se_footer_copyright', [
        'default'           => '© ' . date( 'Y' ) . ' Street Editorial. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'se_footer_copyright', [
        'label'   => __( 'Texto de copyright', 'street-editorial' ),
        'section' => 'se_footer',
        'type'    => 'text',
    ] );

    /* ════════════════════════════════════════════════════════════
       PARTIAL REFRESH (postMessage preview en vivo)
       ════════════════════════════════════════════════════════════ */

    // Hero eyebrow
    $wp_customize->selective_refresh->add_partial( 'se_hero_eyebrow', [
        'selector'            => '.hero__eyebrow',
        'container_inclusive' => false,
        'render_callback'     => function() {
            return '— ' . esc_html( get_theme_mod( 'se_hero_eyebrow', 'Nueva Colección — SS25' ) );
        },
    ] );

    // Hero título
    $wp_customize->selective_refresh->add_partial( 'se_hero_title_1', [
        'selector'        => '.hero__title',
        'render_callback' => 'se_render_hero_title',
    ] );
    $wp_customize->selective_refresh->add_partial( 'se_hero_title_2', [
        'selector'        => '.hero__title',
        'render_callback' => 'se_render_hero_title',
    ] );

    // Hero subtítulo
    $wp_customize->selective_refresh->add_partial( 'se_hero_subtitle', [
        'selector'        => '.hero__subtitle',
        'render_callback' => function() {
            return esc_html( get_theme_mod( 'se_hero_subtitle', '' ) );
        },
    ] );

    // Hero botón texto
    $wp_customize->selective_refresh->add_partial( 'se_hero_btn_text', [
        'selector'        => '.hero__actions .btn-primary',
        'render_callback' => function() {
            return esc_html( get_theme_mod( 'se_hero_btn_text', 'Shop Now' ) );
        },
    ] );

    // Marquee
    $wp_customize->selective_refresh->add_partial( 'se_marquee_text', [
        'selector'        => '.marquee-track',
        'render_callback' => 'se_render_marquee_track',
    ] );

    // About sección home
    $wp_customize->selective_refresh->add_partial( 'se_about_title', [
        'selector'        => '.about-strip__title',
        'render_callback' => function() {
            return se_about_title_html();
        },
    ] );
    $wp_customize->selective_refresh->add_partial( 'se_about_text', [
        'selector'        => '.about-strip__text',
        'render_callback' => function() {
            return esc_html( get_theme_mod( 'se_about_text', '' ) );
        },
    ] );

    // Logo text
    $wp_customize->selective_refresh->add_partial( 'se_logo_text', [
        'selector'        => '.nav-logo',
        'render_callback' => 'se_render_logo',
    ] );

    // Footer tagline y copyright
    $wp_customize->selective_refresh->add_partial( 'se_footer_tagline', [
        'selector'        => '.footer-tagline',
        'render_callback' => function() {
            return esc_html( get_theme_mod( 'se_footer_tagline', '' ) );
        },
    ] );
    $wp_customize->selective_refresh->add_partial( 'se_footer_copyright', [
        'selector'        => '.footer-copy',
        'render_callback' => function() {
            return esc_html( get_theme_mod( 'se_footer_copyright', '' ) );
        },
    ] );
}
add_action( 'customize_register', 'se_customizer_register' );


/* ══════════════════════════════════════════════════════════════
   CSS DINÁMICO EN <head>
   Inyecta las variables CSS según los colores elegidos en el
   Customizer para que cambien en tiempo real.
   ══════════════════════════════════════════════════════════════ */
function se_customizer_css() {
    $accent = get_theme_mod( 'se_color_accent', '#e8c547' );
    $bg     = get_theme_mod( 'se_color_bg',     '#0a0a0a' );
    $text   = get_theme_mod( 'se_color_text',   '#f5f1eb' );
    $muted  = get_theme_mod( 'se_color_muted',  '#777770' );

    echo '<style id="se-customizer-css">
:root {
    --accent:   ' . esc_attr( $accent ) . ';
    --accent-h: ' . esc_attr( se_lighten_hex( $accent, 15 ) ) . ';
    --black:    ' . esc_attr( $bg ) . ';
    --white:    ' . esc_attr( $text ) . ';
    --muted:    ' . esc_attr( $muted ) . ';
}
</style>' . "\n";
}
add_action( 'wp_head', 'se_customizer_css', 99 );


/* ══════════════════════════════════════════════════════════════
   HELPERS DE RENDER (usados por selective_refresh y templates)
   ══════════════════════════════════════════════════════════════ */

/**
 * Construye el HTML del título del Hero.
 */
function se_render_hero_title() {
    $t1 = esc_html( get_theme_mod( 'se_hero_title_1', 'Wear the' ) );
    $t2 = esc_html( get_theme_mod( 'se_hero_title_2', 'streets.' ) );
    return $t1 . '<br><em>' . $t2 . '</em>';
}

/**
 * Construye el HTML del track del marquee.
 */
function se_render_marquee_track() {
    $raw   = get_theme_mod( 'se_marquee_text', 'New Collection — Limited Drop — Free Shipping — Premium Quality — SS25' );
    $items = array_filter( array_map( 'trim', explode( '—', $raw ) ) );
    $out   = '';
    // Duplicar para loop infinito perfecto
    $all   = array_merge( array_values( $items ), array_values( $items ) );
    foreach ( $all as $item ) {
        $out .= '<span class="marquee-item">' . esc_html( strtoupper( $item ) ) . '<span class="marquee-dot"></span></span>';
    }
    return $out;
}

/**
 * HTML del título de About con saltos de línea.
 */
function se_about_title_html() {
    $raw   = get_theme_mod( 'se_about_title', "Born on the streets.\nBuilt for the stage." );
    $lines = array_map( 'esc_html', explode( "\n", $raw ) );
    return implode( '<br>', $lines );
}

/**
 * Render del logo en el navbar.
 */
function se_render_logo() {
    if ( has_custom_logo() ) {
        return get_custom_logo();
    }
    return esc_html( get_theme_mod( 'se_logo_text', 'STREET—EDITORIAL' ) );
}

/**
 * Helper: aclara un color hex (para accent-h).
 *
 * @param  string $hex Color en formato #rrggbb.
 * @param  int    $amount Cantidad de aclarado (0-255).
 * @return string Color resultante en #rrggbb.
 */
function se_lighten_hex( $hex, $amount = 20 ) {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = min( 255, hexdec( substr( $hex, 0, 2 ) ) + $amount );
    $g = min( 255, hexdec( substr( $hex, 2, 2 ) ) + $amount );
    $b = min( 255, hexdec( substr( $hex, 4, 2 ) ) + $amount );
    return sprintf( '#%02x%02x%02x', $r, $g, $b );
}

/**
 * Helper público: devuelve la URL de una red social.
 */
function se_social_url( $network ) {
    return esc_url( get_theme_mod( "se_social_{$network}", '' ) );
}

/**
 * Helper público: ¿hay alguna red social configurada?
 */
function se_has_social() {
    $nets = [ 'instagram','tiktok','twitter','facebook','youtube','pinterest' ];
    foreach ( $nets as $n ) {
        if ( get_theme_mod( "se_social_{$n}", '' ) ) return true;
    }
    return false;
}
