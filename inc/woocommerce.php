<?php
/**
 * WooCommerce support: theme wrappers + dark styling for shop, product, cart, checkout and account pages.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Declare support.
 */
function fu_wc_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
			'product_grid'          => array( 'default_columns' => 4, 'max_columns' => 4 ),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'fu_wc_setup' );

/**
 * Styles (only when WooCommerce is active).
 */
function fu_wc_assets() {
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'fu-woocommerce', FU_URI . '/assets/css/woocommerce.css', array( 'fu-theme' ), FU_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'fu_wc_assets', 20 );
add_action( 'elementor/preview/enqueue_styles', 'fu_wc_assets' );

/**
 * Replace WooCommerce's default content wrappers with the theme's.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Opening wrapper.
 */
function fu_wc_wrapper_start() {
	echo '<section class="fu-section fu-wc"><div class="fu-container">';
}
add_action( 'woocommerce_before_main_content', 'fu_wc_wrapper_start', 10 );

/**
 * Closing wrapper.
 */
function fu_wc_wrapper_end() {
	echo '</div></section>';
}
add_action( 'woocommerce_after_main_content', 'fu_wc_wrapper_end', 10 );

/**
 * Cart link with item count, for the header.
 */
function fu_wc_cart_link() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return '';
	}
	$count = WC()->cart->get_cart_contents_count();
	return sprintf(
		'<a class="fu-cart-link" href="%1$s" aria-label="%2$s">%3$s<span class="fu-cart-count%4$s">%5$d</span></a>',
		esc_url( wc_get_cart_url() ),
		esc_attr__( 'Cart', 'future-ukraine' ),
		fu_icon( 'shopping-bag' ),
		$count ? '' : ' is-empty',
		(int) $count
	);
}

/**
 * Keep the header cart count fresh after AJAX add-to-cart.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function fu_wc_cart_fragment( $fragments ) {
	$fragments['a.fu-cart-link'] = fu_wc_cart_link();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'fu_wc_cart_fragment' );
