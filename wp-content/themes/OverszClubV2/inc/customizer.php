<?php
/**
 * OverszClubV2 — customizer.php
 * Toda la personalización desde Apariencia → Personalizar.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ══════════════════════════════════════════════════════════
   REGISTRO
   ══════════════════════════════════════════════════════════ */
function oc_customizer_register( WP_Customize_Manager $wp ) {

    /* Panel principal */
    $wp->add_panel( 'oc_panel', [
        'title'    => __( 'OverszClub Theme', 'overszclubv2' ),
        'priority' => 10,
    ] );

    /* ── 1. COLORES ──────────────────────────────────────── */
    $wp->add_section( 'oc_colors', [
        'title'    => __( 'Colores del tema', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 5,
    ] );

    _oc_color( $wp, 'oc_color_accent', __( 'Color de acento',         'overszclubv2' ), '#e8c547', 'oc_colors' );
    _oc_color( $wp, 'oc_color_bg',     __( 'Fondo principal',          'overszclubv2' ), '#080808', 'oc_colors' );
    _oc_color( $wp, 'oc_color_text',   __( 'Color de texto',           'overszclubv2' ), '#f0ece4', 'oc_colors' );
    _oc_color( $wp, 'oc_color_muted',  __( 'Texto secundario (muted)', 'overszclubv2' ), '#6b6b65', 'oc_colors' );

    /* ── 2. IDENTIDAD / LOGO ─────────────────────────────── */
    $wp->add_section( 'oc_identity', [
        'title'    => __( 'Identidad del sitio', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 10,
    ] );

    $wp->add_setting( 'oc_logo_text', [
        'default'           => 'OVERSZCLUB',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp->add_control( 'oc_logo_text', [
        'label'       => __( 'Texto del logo (sin imagen)', 'overszclubv2' ),
        'section'     => 'oc_identity',
        'type'        => 'text',
    ] );

    /* ── 3. HERO ─────────────────────────────────────────── */
    $wp->add_section( 'oc_hero', [
        'title'    => __( 'Hero — Portada', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 20,
    ] );

    _oc_image(  $wp, 'oc_hero_image',    __( 'Imagen de fondo (1800×900 px)',  'overszclubv2' ), '',                           'oc_hero' );
    _oc_text(   $wp, 'oc_hero_eyebrow',  __( 'Eyebrow (texto pequeño)',         'overszclubv2' ), 'Nueva Colección — SS25',    'oc_hero' );
    _oc_text(   $wp, 'oc_hero_title_1',  __( 'Título línea 1',                  'overszclubv2' ), 'Wear the',                  'oc_hero' );
    _oc_text(   $wp, 'oc_hero_title_2',  __( 'Título línea 2 (color acento)',   'overszclubv2' ), 'Streets.',                  'oc_hero' );
    _oc_textarea( $wp, 'oc_hero_subtitle', __( 'Subtítulo',                     'overszclubv2' ), 'Streetwear con identidad editorial.', 'oc_hero' );
    _oc_text(   $wp, 'oc_hero_btn',      __( 'Texto del botón',                 'overszclubv2' ), 'VER COLECCIÓN',             'oc_hero' );
    _oc_url(    $wp, 'oc_hero_btn_url',  __( 'URL del botón (vacío = tienda)',  'overszclubv2' ), '',                          'oc_hero' );
    _oc_text(   $wp, 'oc_hero_btn2',     __( 'Texto botón secundario',          'overszclubv2' ), 'Nuestra Historia',          'oc_hero' );

    /* ── 4. MARQUEE ──────────────────────────────────────── */
    $wp->add_section( 'oc_marquee', [
        'title'    => __( 'Marquee / Ticker', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 30,
    ] );

    _oc_textarea( $wp, 'oc_marquee_text', __( 'Texto (separa con " — ")', 'overszclubv2' ),
        'Nueva Colección — Drops Limitados — Envío Gratis — Streetwear Premium — Calidad Superior',
        'oc_marquee' );

    /* ── 5. ABOUT HOME ───────────────────────────────────── */
    $wp->add_section( 'oc_about_home', [
        'title'    => __( 'About — Strip en Home', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 40,
    ] );

    _oc_image(    $wp, 'oc_about_img',     __( 'Imagen',             'overszclubv2' ), '', 'oc_about_home' );
    _oc_text(     $wp, 'oc_about_eyebrow', __( 'Eyebrow',            'overszclubv2' ), 'Nuestra Identidad', 'oc_about_home' );
    _oc_textarea( $wp, 'oc_about_title',   __( 'Título (saltos ok)', 'overszclubv2' ), "Nacidos en\nla calle.", 'oc_about_home' );
    _oc_textarea( $wp, 'oc_about_text',    __( 'Texto descriptivo',  'overszclubv2' ),
        'OverszClub nació con una sola convicción: el streetwear merece producción editorial. Cada pieza es un statement.',
        'oc_about_home' );
    _oc_text(     $wp, 'oc_about_btn',     __( 'Texto del botón',    'overszclubv2' ), 'Nuestra Historia', 'oc_about_home' );

    /* ── 6. LOOKBOOK ─────────────────────────────────────── */
    $wp->add_section( 'oc_lookbook', [
        'title'       => __( 'Lookbook — Imágenes', 'overszclubv2' ),
        'description' => __( 'Sube hasta 9 imágenes editoriales.', 'overszclubv2' ),
        'panel'       => 'oc_panel',
        'priority'    => 50,
    ] );

    for ( $i = 1; $i <= 9; $i++ ) {
        $wp->add_setting( "oc_lookbook_img_{$i}", [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp->add_control( new WP_Customize_Image_Control( $wp, "oc_lookbook_img_{$i}", [
            'label'   => sprintf( __( 'Imagen %d', 'overszclubv2' ), $i ),
            'section' => 'oc_lookbook',
        ] ) );
    }

    /* ── 7. REDES SOCIALES ───────────────────────────────── */
    $wp->add_section( 'oc_social', [
        'title'    => __( 'Redes sociales', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 60,
    ] );

    $nets = [ 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'twitter' => 'X / Twitter',
              'facebook'  => 'Facebook',  'youtube' => 'YouTube', 'pinterest' => 'Pinterest' ];
    foreach ( $nets as $key => $label ) {
        _oc_url( $wp, "oc_social_{$key}", $label, '', 'oc_social' );
    }

    /* ── 8. FOOTER ───────────────────────────────────────── */
    $wp->add_section( 'oc_footer', [
        'title'    => __( 'Footer', 'overszclubv2' ),
        'panel'    => 'oc_panel',
        'priority' => 70,
    ] );

    _oc_text( $wp, 'oc_footer_tagline',   __( 'Tagline',    'overszclubv2' ), 'Streetwear con identidad editorial.', 'oc_footer' );
    _oc_text( $wp, 'oc_footer_copyright', __( 'Copyright',  'overszclubv2' ), '© ' . date('Y') . ' OverszClub. All rights reserved.', 'oc_footer' );

    /* ── PARTIALS para preview en vivo ───────────────────── */
    $wp->selective_refresh->add_partial( 'oc_hero_eyebrow',  [ 'selector' => '.hero__eyebrow span', 'render_callback' => fn() => esc_html( get_theme_mod('oc_hero_eyebrow','') ) ] );
    $wp->selective_refresh->add_partial( 'oc_marquee_text',  [ 'selector' => '.marquee-track',      'render_callback' => 'oc_render_marquee_track' ] );
    $wp->selective_refresh->add_partial( 'oc_logo_text',     [ 'selector' => '.nav-logo',           'render_callback' => 'oc_render_logo' ] );
    $wp->selective_refresh->add_partial( 'oc_footer_tagline',[ 'selector' => '.footer-tagline',     'render_callback' => fn() => esc_html( get_theme_mod('oc_footer_tagline','') ) ] );
}
add_action( 'customize_register', 'oc_customizer_register' );

/* ── Helpers de registro ────────────────────────────────── */
function _oc_text( $wp, $id, $label, $default, $section ) {
    $wp->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
    $wp->add_control( $id, [ 'label' => $label, 'section' => $section, 'type' => 'text' ] );
}
function _oc_textarea( $wp, $id, $label, $default, $section ) {
    $wp->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'postMessage' ] );
    $wp->add_control( $id, [ 'label' => $label, 'section' => $section, 'type' => 'textarea' ] );
}
function _oc_url( $wp, $id, $label, $default, $section ) {
    $wp->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ] );
    $wp->add_control( $id, [ 'label' => $label, 'section' => $section, 'type' => 'url' ] );
}
function _oc_color( $wp, $id, $label, $default, $section ) {
    $wp->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ] );
    $wp->add_control( new WP_Customize_Color_Control( $wp, $id, [ 'label' => $label, 'section' => $section ] ) );
}
function _oc_image( $wp, $id, $label, $default, $section ) {
    $wp->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ] );
    $wp->add_control( new WP_Customize_Image_Control( $wp, $id, [ 'label' => $label, 'section' => $section ] ) );
}

/* ══════════════════════════════════════════════════════════
   CSS DINÁMICO — variables de color en <head>
   ══════════════════════════════════════════════════════════ */
function oc_customizer_css() {
    $accent = get_theme_mod( 'oc_color_accent', '#e8c547' );
    $bg     = get_theme_mod( 'oc_color_bg',     '#080808' );
    $text   = get_theme_mod( 'oc_color_text',   '#f0ece4' );
    $muted  = get_theme_mod( 'oc_color_muted',  '#6b6b65' );
    $ah     = oc_lighten( $accent, 13 );

    echo '<style id="oc-customizer-vars">:root{';
    echo '--c-accent:' . esc_attr( $accent ) . ';';
    echo '--c-accent-h:' . esc_attr( $ah ) . ';';
    echo '--c-bg:' . esc_attr( $bg ) . ';';
    echo '--c-text:' . esc_attr( $text ) . ';';
    echo '--c-muted:' . esc_attr( $muted ) . ';';
    echo '}</style>' . "\n";
}
add_action( 'wp_head', 'oc_customizer_css', 99 );

/* ══════════════════════════════════════════════════════════
   HELPERS PÚBLICOS
   ══════════════════════════════════════════════════════════ */
function oc_render_logo() {
    if ( has_custom_logo() ) return get_custom_logo();
    return '<span>' . esc_html( get_theme_mod( 'oc_logo_text', 'OVERSZCLUB' ) ) . '</span>';
}

function oc_render_marquee_track() {
    $raw   = get_theme_mod( 'oc_marquee_text', 'Nueva Colección — Drops Limitados — Envío Gratis' );
    $items = array_filter( array_map( 'trim', explode( '—', $raw ) ) );
    $all   = array_merge( array_values($items), array_values($items) );
    $out   = '';
    foreach ( $all as $item ) {
        $out .= '<span class="marquee-item">' . esc_html( strtoupper( $item ) ) . '</span>';
    }
    return $out;
}

function oc_social( $net ) {
    return esc_url( get_theme_mod( "oc_social_{$net}", '' ) );
}

function oc_has_social() {
    foreach ( ['instagram','tiktok','twitter','facebook','youtube','pinterest'] as $n ) {
        if ( get_theme_mod( "oc_social_{$n}", '' ) ) return true;
    }
    return false;
}

function oc_about_title_html() {
    $raw = get_theme_mod( 'oc_about_title', "Nacidos en\nla calle." );
    return implode( '<br>', array_map( 'esc_html', explode( "\n", $raw ) ) );
}

function oc_lighten( $hex, $amt = 15 ) {
    $hex = ltrim( $hex, '#' );
    if ( strlen($hex) === 3 ) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    $r = min( 255, hexdec( substr($hex,0,2) ) + $amt );
    $g = min( 255, hexdec( substr($hex,2,2) ) + $amt );
    $b = min( 255, hexdec( substr($hex,4,2) ) + $amt );
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}
