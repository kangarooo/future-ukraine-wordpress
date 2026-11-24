<?php
/**
 * Future Ukraine theme bootstrap.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

define( 'FU_VERSION', '1.0.1' );
define( 'FU_DIR', get_template_directory() );
define( 'FU_URI', get_template_directory_uri() );

require FU_DIR . '/inc/icons.php';
require FU_DIR . '/inc/defaults.php';
require FU_DIR . '/inc/template-functions.php';
require FU_DIR . '/inc/customizer.php';
require FU_DIR . '/inc/forms.php';
require FU_DIR . '/inc/setup.php';
require FU_DIR . '/inc/elementor.php';

/**
 * Theme supports and menus.
 */
function fu_theme_setup() {
	load_theme_textdomain( 'future-ukraine', FU_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 96, 'width' => 96, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'future-ukraine' ),
			'footer'  => __( 'Footer quick links', 'future-ukraine' ),
			'legal'   => __( 'Footer legal links', 'future-ukraine' ),
		)
	);
}
add_action( 'after_setup_theme', 'fu_theme_setup' );

/**
 * Content width for embeds.
 */
function fu_content_width() {
	$GLOBALS['content_width'] = 1140;
}
add_action( 'after_setup_theme', 'fu_content_width', 0 );

/**
 * Front-end assets.
 */
function fu_enqueue_assets() {
	wp_enqueue_style(
		'fu-fonts',
		'https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@300;400;500;600&family=Inter:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'fu-theme', FU_URI . '/assets/css/theme.css', array( 'fu-fonts' ), FU_VERSION );
	wp_enqueue_script( 'fu-theme', FU_URI . '/assets/js/theme.js', array(), FU_VERSION, true );
	wp_localize_script(
		'fu-theme',
		'fuTheme',
		array(
			'copied' => __( 'Copied', 'future-ukraine' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'fu_enqueue_assets' );

/**
 * Preconnect to Google Fonts.
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function fu_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'fu_resource_hints', 10, 2 );

/**
 * Meta description on the front page (from the Customizer).
 */
function fu_meta_description() {
	if ( ! is_front_page() ) {
		return;
	}
	// Leave SEO to dedicated plugins when present.
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
		return;
	}
	$desc = fu_opt( 'meta_description' );
	if ( $desc ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $desc ) );
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $desc ) );
		printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		echo '<meta property="og:type" content="website" />' . "\n";
	}
}
add_action( 'wp_head', 'fu_meta_description', 1 );

/**
 * Body classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function fu_body_classes( $classes ) {
	$classes[] = 'fu-theme';
	return $classes;
}
add_filter( 'body_class', 'fu_body_classes' );
