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
 * Full stored settings for a section widget: every text field and every repeater row,
 * so the content lives in the page itself (editable in Elementor and by any tool that edits Elementor data).
 *
 * @param string $section Section.
 * @return array|stdClass
 */
function fu_elementor_default_settings( $section ) {
	$schema = fu_sections_schema();
	if ( empty( $schema[ $section ] ) ) {
		return new stdClass();
	}
	$out = array();
	foreach ( $schema[ $section ]['fields'] as $key => $spec ) {
		if ( 'media' === $spec[0] ) {
			continue; // Empty media falls back to the theme image.
		}
		if ( 'anchor' === $key ) {
			$out[ $key ] = $schema[ $section ]['anchor'];
			continue;
		}
		$out[ $key ] = $spec[2];
	}
	foreach ( $schema[ $section ]['repeaters'] as $key => $rep ) {
		$rows = array();
		foreach ( $rep['default'] as $i => $row ) {
			$row['_id'] = substr( md5( $section . $key . $i ), 0, 7 );
			$rows[]     = $row;
		}
		$out[ $key ] = $rows;
	}
	return $out ? $out : new stdClass();
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
							'settings'   => fu_elementor_default_settings( $section ),
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
