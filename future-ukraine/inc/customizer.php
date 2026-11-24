<?php
/**
 * Customizer: brand, header button, footer, SEO, form notifications.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Option defaults.
 *
 * @return array key => array( label, default, type )
 */
function fu_options_schema() {
	return array(
		'brand_mark'       => array( __( 'Logo mark text (used when no logo image)', 'future-ukraine' ), 'UA', 'text' ),
		'brand_line_1'     => array( __( 'Brand line 1', 'future-ukraine' ), 'UKRAINE', 'text' ),
		'brand_line_2'     => array( __( 'Brand line 2', 'future-ukraine' ), 'SUPPORT LATVIA', 'text' ),
		'nav_cta_text'     => array( __( 'Header button text', 'future-ukraine' ), 'Support Now', 'text' ),
		'nav_cta_url'      => array( __( 'Header button link', 'future-ukraine' ), '#donate', 'text' ),
		'footer_text'      => array( __( 'Footer description', 'future-ukraine' ), 'Ukrainian NGO in Latvia dedicated to providing humanitarian aid, supporting refugees, and helping rebuild communities affected by war.', 'textarea' ),
		'footer_reg'       => array( __( 'Footer registration line', 'future-ukraine' ), 'Reg. No: 40008XXXXXX | Rīga, Latvia', 'text' ),
		'copyright'        => array( __( 'Copyright text (year is added automatically)', 'future-ukraine' ), 'Ukraine Support Latvia. All rights reserved.', 'text' ),
		'made_with'        => array( __( '"Made with ♥" suffix', 'future-ukraine' ), 'for Ukraine', 'text' ),
		'meta_description' => array( __( 'Front page meta description', 'future-ukraine' ), 'Ukrainian NGO in Latvia providing critical humanitarian aid, military support, and rehabilitation services. Donate now to help Ukraine.', 'textarea' ),
		'notify_email'     => array( __( 'Send form submissions to (empty = admin email)', 'future-ukraine' ), '', 'text' ),
	);
}

/**
 * Get an option.
 *
 * @param string $key Key.
 * @return string
 */
function fu_opt( $key ) {
	$schema = fu_options_schema();
	$def    = isset( $schema[ $key ] ) ? $schema[ $key ][1] : '';
	return (string) get_theme_mod( 'fu_' . $key, $def );
}

/**
 * Register Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function fu_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'fu_theme',
		array(
			'title'       => __( 'Future Ukraine', 'future-ukraine' ),
			'description' => __( 'Header, footer and site-wide texts. Page sections are edited with Elementor (or Appearance → Future Ukraine Setup).', 'future-ukraine' ),
			'priority'    => 30,
		)
	);
	foreach ( fu_options_schema() as $key => $spec ) {
		$wp_customize->add_setting(
			'fu_' . $key,
			array(
				'default'           => $spec[1],
				'sanitize_callback' => 'textarea' === $spec[2] ? 'sanitize_textarea_field' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'fu_' . $key,
			array(
				'label'   => $spec[0],
				'section' => 'fu_theme',
				'type'    => $spec[2],
			)
		);
	}
}
add_action( 'customize_register', 'fu_customize_register' );
