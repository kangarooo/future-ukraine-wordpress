<?php
/**
 * Front page: page content (Elementor / shortcodes) or, when empty, all home sections.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fu_has_content = false;
if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( '' !== trim( get_the_content() ) || ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) ) {
			$fu_has_content = true;
			the_content();
		}
	}
}

if ( ! $fu_has_content ) {
	foreach ( fu_home_sections() as $fu_section ) {
		fu_render_section( $fu_section );
	}
}

get_footer();
