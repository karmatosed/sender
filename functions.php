<?php
if ( ! function_exists( 'sender_support' ) ) :
	function sender_support() {

		// Alignwide and alignfull classes in the block editor.
		add_theme_support( 'align-wide' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for responsive embedded content.
		// https://github.com/WordPress/gutenberg/issues/26901
		add_theme_support( 'responsive-embeds' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for post thumbnails.
		add_theme_support( 'post-thumbnails' );

		add_theme_support('custom-spacing');

		// Enqueue editor styles.
		add_editor_style( array( 
			'style.css',
			'editor-hacks.css'
		) );

		// Add support for custom units.
		add_theme_support( 'custom-units' );
	}
	add_action( 'after_setup_theme', 'sender_support' );
endif;


/**
 * Fonts and styles.
 */

define( 'GOOGLE_FONTS_URL', 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600;1,700;1,800&display=swap' );
function sender_fonts() {
	wp_enqueue_style( 'sender_fonts', GOOGLE_FONTS_URL );
}
add_action( 'wp_enqueue_scripts', 'sender_fonts' );
add_action( 'admin_head', 'sender_fonts' );
add_editor_style( GOOGLE_FONTS_URL );

/**
 * !!!!! work out if this is needed or the one above
 * Enqueue scripts and styles.
 */
function sender_editor_styles() {
	// Enqueue editor styles.
	add_editor_style(
		array(
			sender_fonts_url(),
		)
	);
}
add_action( 'admin_init', 'sender_editor_styles' );

/**
 *
 * Enqueue scripts and styles.
 */
function sender_scripts() {
	wp_enqueue_style( 'sender_style', get_stylesheet_uri() );
	// Enqueue Google fonts
	wp_enqueue_style( 'sender_fonts', sender_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'sender_scripts' );

/**
 * Add Google webfonts
 *
 * @return $fonts_url
 */
function sender_fonts_url() {
	if ( ! class_exists( 'WP_Theme_JSON_Resolver_Gutenberg' ) ) {
		return '';
	}

	$theme_data = WP_Theme_JSON_Resolver_Gutenberg::get_merged_data()->get_settings();
	if ( empty( $theme_data ) || empty( $theme_data['custom'] ) ) {
		return '';
	}

	$custom_data = $theme_data['custom'];
	if ( ! array_key_exists( 'fontsToLoadFromGoogle', $custom_data ) ) {
		return '';
	}

	$font_families   = $theme_data['custom']['fontsToLoadFromGoogle'];
	$font_families[] = 'display=swap';

	// Make a single request for the theme fonts.
	return esc_url_raw( 'https://fonts.googleapis.com/css2?' . implode( '&', $font_families ) );
}