<?php
/**
 * Generic Elementor widget — one instance per section, controls built from fu_sections_schema().
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

/**
 * Section widget.
 */
class FU_Section_Widget extends Widget_Base {

	/**
	 * Section key.
	 *
	 * @var string
	 */
	protected $fu_section = '';

	/**
	 * Constructor.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Args; expects fu_section.
	 */
	public function __construct( $data = array(), $args = null ) {
		if ( is_array( $args ) && ! empty( $args['fu_section'] ) ) {
			$this->fu_section = $args['fu_section'];
		} elseif ( ! empty( $data['widgetType'] ) ) {
			$this->fu_section = preg_replace( '/^fu-/', '', $data['widgetType'] );
		}
		parent::__construct( $data, $args );
	}

	/**
	 * Schema for this section.
	 *
	 * @return array
	 */
	protected function fu_schema() {
		$all = fu_sections_schema();
		return isset( $all[ $this->fu_section ] ) ? $all[ $this->fu_section ] : array( 'title' => $this->fu_section, 'icon' => 'eicon-section', 'fields' => array(), 'repeaters' => array() );
	}

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return fu_widget_type( $this->fu_section );
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		$s = $this->fu_schema();
		return 'FU · ' . $s['title'];
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		$s = $this->fu_schema();
		return $s['icon'];
	}

	/**
	 * Categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'future-ukraine' );
	}

	/**
	 * Keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'ukraine', 'ngo', $this->fu_section );
	}

	/**
	 * Map a schema field to an Elementor control.
	 *
	 * @param array $spec Field spec.
	 * @return array
	 */
	protected function fu_control( $spec ) {
		$map  = array(
			'text'     => Controls_Manager::TEXT,
			'url'      => Controls_Manager::TEXT,
			'textarea' => Controls_Manager::TEXTAREA,
			'number'   => Controls_Manager::NUMBER,
			'select'   => Controls_Manager::SELECT,
			'media'    => Controls_Manager::MEDIA,
			'switcher' => Controls_Manager::SWITCHER,
		);
		$type = isset( $map[ $spec[0] ] ) ? $map[ $spec[0] ] : Controls_Manager::TEXT;
		$c    = array(
			'label'       => $spec[1],
			'type'        => $type,
			'label_block' => in_array( $spec[0], array( 'text', 'url', 'textarea' ), true ),
		);
		if ( 'media' === $spec[0] ) {
			$c['default'] = array( 'url' => $spec[2] );
		} elseif ( 'switcher' === $spec[0] ) {
			$c['default']      = $spec[2];
			$c['return_value'] = 'yes';
		} else {
			$c['default'] = $spec[2];
		}
		if ( 'select' === $spec[0] && isset( $spec[3] ) ) {
			$c['options'] = $spec[3];
		}
		if ( 'url' === $spec[0] ) {
			$c['placeholder'] = 'https://… or #anchor';
		}
		if ( 'textarea' === $spec[0] ) {
			$c['rows'] = 4;
		}
		return $c;
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$s = $this->fu_schema();

		$this->start_controls_section( 'fu_content', array( 'label' => $s['title'], 'tab' => Controls_Manager::TAB_CONTENT ) );
		foreach ( $s['fields'] as $key => $spec ) {
			$this->add_control( $key, $this->fu_control( $spec ) );
		}
		$this->end_controls_section();

		foreach ( $s['repeaters'] as $key => $rep ) {
			$this->start_controls_section( 'fu_rep_' . $key, array( 'label' => $rep['label'], 'tab' => Controls_Manager::TAB_CONTENT ) );
			$repeater = new Repeater();
			$first    = null;
			foreach ( $rep['fields'] as $fkey => $fspec ) {
				if ( null === $first ) {
					$first = $fkey;
				}
				$repeater->add_control( $fkey, $this->fu_control( $fspec ) );
			}
			$this->add_control(
				$key,
				array(
					'label'       => $rep['label'],
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => $rep['default'],
					'title_field' => '{{{ ' . $first . ' }}}',
				)
			);
			$this->end_controls_section();
		}
	}

	/**
	 * Render.
	 */
	protected function render() {
		fu_render_section( $this->fu_section, $this->get_settings_for_display() );
	}
}
