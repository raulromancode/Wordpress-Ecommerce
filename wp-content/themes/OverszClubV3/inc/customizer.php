<?php
/**
 * Ajustes del Customizer.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function overszclub_sanitize_customizer_text( $value ) {
	return is_string( $value ) ? wp_kses_post( $value ) : '';
}

function overszclub_sanitize_customizer_url( $value ) {
	return esc_url_raw( $value );
}

function overszclub_sanitize_hotspot_number( $value ) {
	$value = is_numeric( $value ) ? (float) $value : 0;
	return min( 100, max( 0, $value ) );
}

function overszclub_customize_register( $wp_customize ) {
	$defaults = overszclub_get_default_mods();

	$wp_customize->add_panel(
		'overszclub_theme_panel',
		array(
			'title'       => esc_html__( 'OverszClub', 'overszclub' ),
			'description' => esc_html__( 'Ajustes visuales y de contenido del tema.', 'overszclub' ),
			'priority'    => 30,
		)
	);

	$sections = array(
		'overszclub_home_hero'     => esc_html__( 'Hero', 'overszclub' ),
		'overszclub_home_featured' => esc_html__( 'Piezas clave', 'overszclub' ),
		'overszclub_lookbook'      => esc_html__( 'Lookbook', 'overszclub' ),
		'overszclub_about'         => esc_html__( 'Sobre la marca', 'overszclub' ),
		'overszclub_social'        => esc_html__( 'Redes sociales', 'overszclub' ),
		'overszclub_colors'        => esc_html__( 'Colores del tema', 'overszclub' ),
	);

	foreach ( $sections as $id => $label ) {
		$wp_customize->add_section(
			$id,
			array(
				'title' => $label,
				'panel' => 'overszclub_theme_panel',
			)
		);
	}

	if ( overszclub_has_woocommerce() ) {
		$wp_customize->add_section(
			'overszclub_woocommerce_shop',
			array(
				'title' => esc_html__( 'Tienda principal', 'overszclub' ),
				'panel' => 'woocommerce',
			)
		);
	}

	$fields = array(
		'hero_title'             => array( 'section' => 'overszclub_home_hero', 'label' => esc_html__( 'Titulo del hero', 'overszclub' ), 'type' => 'text' ),
		'hero_button_text'       => array( 'section' => 'overszclub_home_hero', 'label' => esc_html__( 'Texto del boton', 'overszclub' ), 'type' => 'text' ),
		'hero_button_url'        => array( 'section' => 'overszclub_home_hero', 'label' => esc_html__( 'URL del boton', 'overszclub' ), 'type' => 'url' ),
		'marquee_text'           => array( 'section' => 'overszclub_home_hero', 'label' => esc_html__( 'Texto del marquee', 'overszclub' ), 'type' => 'text' ),
		'about_content'          => array( 'section' => 'overszclub_about', 'label' => esc_html__( 'Contenido sobre la marca', 'overszclub' ), 'type' => 'textarea' ),
		'social_instagram'       => array( 'section' => 'overszclub_social', 'label' => esc_html__( 'Instagram', 'overszclub' ), 'type' => 'url' ),
		'social_tiktok'          => array( 'section' => 'overszclub_social', 'label' => esc_html__( 'TikTok', 'overszclub' ), 'type' => 'url' ),
		'social_pinterest'       => array( 'section' => 'overszclub_social', 'label' => esc_html__( 'Pinterest', 'overszclub' ), 'type' => 'url' ),
		'theme_accent_color'     => array( 'section' => 'overszclub_colors', 'label' => esc_html__( 'Color principal', 'overszclub' ), 'type' => 'color' ),
		'theme_background_color' => array( 'section' => 'overszclub_colors', 'label' => esc_html__( 'Fondo', 'overszclub' ), 'type' => 'color' ),
		'theme_surface_color'    => array( 'section' => 'overszclub_colors', 'label' => esc_html__( 'Superficie', 'overszclub' ), 'type' => 'color' ),
		'theme_text_color'       => array( 'section' => 'overszclub_colors', 'label' => esc_html__( 'Texto', 'overszclub' ), 'type' => 'color' ),
		'lookbook_heading'       => array( 'section' => 'overszclub_lookbook', 'label' => esc_html__( 'Titulo lookbook', 'overszclub' ), 'type' => 'text' ),
		'lookbook_intro'         => array( 'section' => 'overszclub_lookbook', 'label' => esc_html__( 'Introduccion lookbook', 'overszclub' ), 'type' => 'textarea' ),
	);

	foreach ( $fields as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $setting_id ] ?? '',
				'sanitize_callback' => 'color' === $config['type'] ? 'sanitize_hex_color' : ( 'url' === $config['type'] ? 'overszclub_sanitize_customizer_url' : 'overszclub_sanitize_customizer_text' ),
				'transport'         => 'refresh',
			)
		);

		if ( 'color' === $config['type'] ) {
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'   => $config['label'],
						'section' => $config['section'],
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$setting_id,
				array(
					'label'   => $config['label'],
					'section' => $config['section'],
					'type'    => $config['type'],
				)
			);
		}
	}

	$wp_customize->add_setting(
		'hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'hero_image',
			array(
				'label'     => esc_html__( 'Imagen principal', 'overszclub' ),
				'section'   => 'overszclub_home_hero',
				'mime_type' => 'image',
			)
		)
	);

	for ( $i = 1; $i <= 6; $i++ ) {
		$wp_customize->add_setting(
			'featured_product_' . $i,
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'featured_product_' . $i,
			array(
				'label'       => sprintf( esc_html__( 'Producto destacado %d', 'overszclub' ), $i ),
				'description' => 1 === $i ? esc_html__( 'Introduce el ID del producto que quieres mostrar en Piezas clave de la temporada.', 'overszclub' ) : '',
				'section'     => 'overszclub_home_featured',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
				),
			)
		);
	}

	if ( overszclub_has_woocommerce() ) {
		$wp_customize->add_setting(
			'shop_categories_title',
			array(
				'default'           => $defaults['shop_categories_title'] ?? '',
				'sanitize_callback' => 'overszclub_sanitize_customizer_text',
			)
		);
		$wp_customize->add_control(
			'shop_categories_title',
			array(
				'label'   => esc_html__( 'Titulo de categorias', 'overszclub' ),
				'section' => 'overszclub_woocommerce_shop',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'shop_categories_intro',
			array(
				'default'           => $defaults['shop_categories_intro'] ?? '',
				'sanitize_callback' => 'overszclub_sanitize_customizer_text',
			)
		);
		$wp_customize->add_control(
			'shop_categories_intro',
			array(
				'label'   => esc_html__( 'Texto introductorio', 'overszclub' ),
				'section' => 'overszclub_woocommerce_shop',
				'type'    => 'textarea',
			)
		);

		$wp_customize->add_setting(
			'shop_categories_mode',
			array(
				'default'           => $defaults['shop_categories_mode'] ?? 'all',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_control(
			'shop_categories_mode',
			array(
				'label'   => esc_html__( 'Origen de categorias', 'overszclub' ),
				'section' => 'overszclub_woocommerce_shop',
				'type'    => 'select',
				'choices' => array(
					'all'     => esc_html__( 'Todas las disponibles', 'overszclub' ),
					'curated' => esc_html__( 'Seleccion manual', 'overszclub' ),
				),
			)
		);

		$wp_customize->add_setting(
			'shop_categories_limit',
			array(
				'default'           => $defaults['shop_categories_limit'] ?? 6,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'shop_categories_limit',
			array(
				'label'       => esc_html__( 'Numero de categorias', 'overszclub' ),
				'description' => esc_html__( 'Se usa cuando eliges mostrar categorias disponibles automaticamente.', 'overszclub' ),
				'section'     => 'overszclub_woocommerce_shop',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 12,
					'step' => 1,
				),
			)
		);
	}

	for ( $i = 1; $i <= 6; $i++ ) {
		$wp_customize->add_setting(
			'shop_category_' . $i,
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'shop_category_' . $i,
			array(
				'label'       => sprintf( esc_html__( 'Categoria de tienda %d', 'overszclub' ), $i ),
				'description' => 1 === $i ? esc_html__( 'Usa estos campos solo si seleccionas "Seleccion manual". Introduce IDs de categorias de producto.', 'overszclub' ) : '',
				'section'     => overszclub_has_woocommerce() ? 'overszclub_woocommerce_shop' : 'overszclub_home_featured',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
				),
			)
		);
	}

	for ( $i = 1; $i <= 4; $i++ ) {
		$wp_customize->add_setting(
			'lookbook_image_' . $i,
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'lookbook_image_' . $i,
				array(
					'label'     => sprintf( esc_html__( 'Imagen %d', 'overszclub' ), $i ),
					'section'   => 'overszclub_lookbook',
					'mime_type' => 'image',
				)
			)
		);

		$wp_customize->add_setting(
			'lookbook_hotspots_' . $i,
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'lookbook_hotspots_' . $i,
			array(
				'label'       => sprintf( esc_html__( 'Hotspots avanzados %d', 'overszclub' ), $i ),
				'description' => esc_html__( 'Formato legacy opcional por linea: producto_id|x|y|etiqueta. Usa mejor los campos guiados de abajo.', 'overszclub' ),
				'section'     => 'overszclub_lookbook',
				'type'        => 'textarea',
			)
		);

		for ( $j = 1; $j <= 3; $j++ ) {
			$wp_customize->add_setting(
				'lookbook_hotspot_' . $i . '_' . $j . '_product',
				array(
					'default'           => '',
					'sanitize_callback' => 'absint',
				)
			);
			$wp_customize->add_control(
				'lookbook_hotspot_' . $i . '_' . $j . '_product',
				array(
					'label'       => sprintf( esc_html__( 'Hotspot %1$d.%2$d Producto ID', 'overszclub' ), $i, $j ),
					'description' => 1 === $j ? esc_html__( 'Ve a Productos y usa el ID del producto. X e Y son porcentajes sobre la imagen.', 'overszclub' ) : '',
					'section'     => 'overszclub_lookbook',
					'type'        => 'number',
					'input_attrs' => array(
						'min' => 1,
						'step' => 1,
					),
				)
			);

			$wp_customize->add_setting(
				'lookbook_hotspot_' . $i . '_' . $j . '_x',
				array(
					'default'           => 50,
					'sanitize_callback' => 'overszclub_sanitize_hotspot_number',
				)
			);
			$wp_customize->add_control(
				'lookbook_hotspot_' . $i . '_' . $j . '_x',
				array(
					'label'       => sprintf( esc_html__( 'Hotspot %1$d.%2$d Posicion X', 'overszclub' ), $i, $j ),
					'section'     => 'overszclub_lookbook',
					'type'        => 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				)
			);

			$wp_customize->add_setting(
				'lookbook_hotspot_' . $i . '_' . $j . '_y',
				array(
					'default'           => 50,
					'sanitize_callback' => 'overszclub_sanitize_hotspot_number',
				)
			);
			$wp_customize->add_control(
				'lookbook_hotspot_' . $i . '_' . $j . '_y',
				array(
					'label'       => sprintf( esc_html__( 'Hotspot %1$d.%2$d Posicion Y', 'overszclub' ), $i, $j ),
					'section'     => 'overszclub_lookbook',
					'type'        => 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				)
			);

			$wp_customize->add_setting(
				'lookbook_hotspot_' . $i . '_' . $j . '_label',
				array(
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'lookbook_hotspot_' . $i . '_' . $j . '_label',
				array(
					'label'   => sprintf( esc_html__( 'Hotspot %1$d.%2$d Etiqueta', 'overszclub' ), $i, $j ),
					'section' => 'overszclub_lookbook',
					'type'    => 'text',
				)
			);
		}
	}
}
add_action( 'customize_register', 'overszclub_customize_register' );

function overszclub_get_lookbook_hotspots( $index ) {
	$raw      = (string) get_theme_mod( 'lookbook_hotspots_' . absint( $index ), '' );
	$lines    = preg_split( '/\r\n|\r|\n/', trim( $raw ) );
	$hotspots = array();

	if ( empty( $lines ) ) {
		return $hotspots;
	}

	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line ) );

		if ( count( $parts ) < 3 ) {
			continue;
		}

		$hotspots[] = array(
			'product_id' => absint( $parts[0] ),
			'x'          => min( 100, max( 0, (float) $parts[1] ) ),
			'y'          => min( 100, max( 0, (float) $parts[2] ) ),
			'label'      => isset( $parts[3] ) ? sanitize_text_field( $parts[3] ) : '',
		);
	}

	for ( $j = 1; $j <= 3; $j++ ) {
		$product_id = absint( get_theme_mod( 'lookbook_hotspot_' . absint( $index ) . '_' . $j . '_product', 0 ) );

		if ( ! $product_id ) {
			continue;
		}

		$hotspots[] = array(
			'product_id' => $product_id,
			'x'          => overszclub_sanitize_hotspot_number( get_theme_mod( 'lookbook_hotspot_' . absint( $index ) . '_' . $j . '_x', 50 ) ),
			'y'          => overszclub_sanitize_hotspot_number( get_theme_mod( 'lookbook_hotspot_' . absint( $index ) . '_' . $j . '_y', 50 ) ),
			'label'      => sanitize_text_field( get_theme_mod( 'lookbook_hotspot_' . absint( $index ) . '_' . $j . '_label', '' ) ),
		);
	}

	if ( ! empty( $hotspots ) ) {
		$hotspots = array_values(
			array_unique(
				$hotspots,
				SORT_REGULAR
			)
		);
	}

	return $hotspots;
}
