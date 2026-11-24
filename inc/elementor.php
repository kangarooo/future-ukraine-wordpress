<?php
/**
 * Elementor integration:
 *  - Theme Builder locations (header / footer / single / archive),
 *  - a "Future Ukraine" widget category with one widget per section, controls generated from the schema.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Elementor Pro theme locations.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Manager.
 */
function fu_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'fu_elementor_locations' );

/**
 * Widget category.
 *
 * @param \Elementor\Elements_Manager $manager Manager.
 */
function fu_elementor_category( $manager ) {
	$manager->add_category(
		'future-ukraine',
		array(
			'title' => __( 'Future Ukraine', 'future-ukraine' ),
			'icon'  => 'eicon-heart',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'fu_elementor_category' );

/**
 * Register widgets.
 *
 * @param \Elementor\Widgets_Manager $manager Manager.
 */
function fu_elementor_widgets( $manager ) {
	require_once FU_DIR . '/inc/class-fu-section-widget.php';
	foreach ( array_keys( fu_sections_schema() ) as $section ) {
		$manager->register( new FU_Section_Widget( array(), array( 'fu_section' => $section ) ) );
	}
}
add_action( 'elementor/widgets/register', 'fu_elementor_widgets' );

/**
 * Load theme CSS + fonts inside the Elementor editor preview.
 */
function fu_elementor_preview_styles() {
	fu_enqueue_assets();
}
add_action( 'elementor/preview/enqueue_styles', 'fu_elementor_preview_styles' );

/**
 * Elementor widget type name for a section.
 *
 * @param string $section Section.
 * @return string
 */
function fu_widget_type( $section ) {
	return 'fu-' . $section;
}

/**
 * Build Elementor page data for a list of sections (used by the importer and the JSON template).
 *
 * @param array $sections Section names.
 * @return array
 */
function fu_elementor_page_data( $sections ) {
	$data = array();
	$zero = array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true );
	foreach ( $sections as $section ) {
		$data[] = array(
			'id'       => substr( md5( 'sec-' . $section ), 0, 7 ),
			'elType'   => 'section',
			'settings' => array(
				'layout'       => 'full_width',
				'gap'          => 'no',
				'padding'      => $zero,
				'margin'       => $zero,
				'css_classes'  => 'fu-el-section',
			),
			'elements' => array(
				array(
					'id'       => substr( md5( 'col-' . $section ), 0, 7 ),
					'elType'   => 'column',
					'settings' => array(
						'_column_size' => 100,
						'padding'      => $zero,
					),
					'elements' => array(
						array(
							'id'         => substr( md5( 'wid-' . $section ), 0, 7 ),
							'elType'     => 'widget',
							'widgetType' => fu_widget_type( $section ),
							'settings'   => new stdClass(),
							'elements'   => array(),
						),
					),
					'isInner'  => false,
				),
			),
			'isInner'  => false,
		);
	}
	return $data;
}
