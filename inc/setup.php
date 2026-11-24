<?php
/**
 * One-click content importer: home page (Elementor), menus, Elementor kit colours/fonts.
 * Runs automatically on theme activation and can be re-run from Appearance → Future Ukraine Setup.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create / update everything. Idempotent.
 *
 * @param bool $force Overwrite the home page layout if it exists.
 * @return array Log lines.
 */
function fu_run_import( $force = false ) {
	$log = array();

	// 1. Home page.
	$page_id = (int) get_option( 'fu_home_page_id' );
	if ( ! $page_id || ! get_post( $page_id ) ) {
		$existing = get_page_by_path( 'future-ukraine-home', OBJECT, 'page' );
		$page_id  = $existing ? $existing->ID : 0;
	}

	$content = "<!-- wp:shortcode -->\n[fu_home]\n<!-- /wp:shortcode -->";
	if ( ! $page_id ) {
		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => __( 'Home', 'future-ukraine' ),
				'post_name'    => 'future-ukraine-home',
				'post_content' => $content,
			)
		);
		$log[] = sprintf( 'Created home page #%d', $page_id );
		$force = true;
	}
	if ( is_wp_error( $page_id ) || ! $page_id ) {
		$log[] = 'ERROR: could not create home page';
		return $log;
	}
	update_option( 'fu_home_page_id', $page_id );
	update_post_meta( $page_id, '_fu_imported', 1 );

	$has_elementor = did_action( 'elementor/loaded' ) || defined( 'ELEMENTOR_VERSION' );
	if ( $force || ! get_post_meta( $page_id, '_elementor_data', true ) ) {
		wp_update_post( array( 'ID' => $page_id, 'post_content' => $content ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( fu_elementor_page_data( fu_home_sections() ) ) ) );
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			update_post_meta( $page_id, '_elementor_version', ELEMENTOR_VERSION );
		}
		delete_post_meta( $page_id, '_elementor_css' );
		$log[] = 'Home page layout written (' . count( fu_home_sections() ) . ' Elementor sections)';
	}

	// 2. Front page.
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_id );
	$log[] = 'Set as static front page';

	// 3. Menus.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = array(
		'primary' => array( 'Future Ukraine — Primary', fu_default_nav() ),
		'footer'  => array(
			'Future Ukraine — Quick links',
			array(
				'About Us' => '#about',
				'Donate'   => '#donate',
				'Reports'  => '#reports',
				'Contact'  => '#contact',
			),
		),
		'legal'   => array(
			'Future Ukraine — Legal',
			array(
				'Privacy Policy' => get_privacy_policy_url() ? get_privacy_policy_url() : '#',
				'Terms of Use'   => '#',
				'Cookie Policy'  => '#',
			),
		),
	);
	foreach ( $menus as $loc => $m ) {
		if ( ! empty( $locations[ $loc ] ) && wp_get_nav_menu_object( $locations[ $loc ] ) ) {
			continue;
		}
		$menu = wp_get_nav_menu_object( $m[0] );
		$id   = $menu ? $menu->term_id : wp_create_nav_menu( $m[0] );
		if ( is_wp_error( $id ) ) {
			continue;
		}
		if ( ! $menu ) {
			$pos = 1;
			foreach ( $m[1] as $label => $url ) {
				wp_update_nav_menu_item(
					$id,
					0,
					array(
						'menu-item-title'    => $label,
						'menu-item-url'      => $url,
						'menu-item-type'     => 'custom',
						'menu-item-status'   => 'publish',
						'menu-item-position' => $pos++,
					)
				);
			}
		}
		$locations[ $loc ] = $id;
		$log[]             = 'Menu ready: ' . $m[0];
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// 4. Elementor kit (global colours + fonts) and default settings.
	if ( $has_elementor ) {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		$kit_id = (int) get_option( 'elementor_active_kit' );
		if ( $kit_id && get_post( $kit_id ) ) {
			$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
			$settings = is_array( $settings ) ? $settings : array();

			$settings['system_colors']     = array(
				array( '_id' => 'primary', 'title' => 'Primary (Gold)', 'color' => '#FFD700' ),
				array( '_id' => 'secondary', 'title' => 'Secondary (Blue)', 'color' => '#005BBD' ),
				array( '_id' => 'text', 'title' => 'Text', 'color' => '#E8E8E3' ),
				array( '_id' => 'accent', 'title' => 'Accent (Olive)', 'color' => '#64783A' ),
			);
			$settings['custom_colors']     = array(
				array( '_id' => 'fu_bg', 'title' => 'Background', 'color' => '#161711' ),
				array( '_id' => 'fu_card', 'title' => 'Card', 'color' => '#20221B' ),
				array( '_id' => 'fu_muted', 'title' => 'Muted text', 'color' => '#929287' ),
				array( '_id' => 'fu_border', 'title' => 'Border', 'color' => '#373B2B' ),
			);
			$settings['system_typography'] = array(
				array( '_id' => 'primary', 'title' => 'Display (Orbitron)', 'typography_typography' => 'custom', 'typography_font_family' => 'Orbitron', 'typography_font_weight' => '700' ),
				array( '_id' => 'secondary', 'title' => 'Mono (JetBrains Mono)', 'typography_typography' => 'custom', 'typography_font_family' => 'JetBrains Mono', 'typography_font_weight' => '400' ),
				array( '_id' => 'text', 'title' => 'Body (Inter)', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '400' ),
				array( '_id' => 'accent', 'title' => 'Accent (Orbitron)', 'typography_typography' => 'custom', 'typography_font_family' => 'Orbitron', 'typography_font_weight' => '600' ),
			);
			$settings['body_background_background'] = 'classic';
			$settings['body_background_color']      = '#161711';
			$settings['container_width']            = array( 'unit' => 'px', 'size' => 1400, 'sizes' => array() );
			update_post_meta( $kit_id, '_elementor_page_settings', $settings );
			$log[] = 'Elementor kit colours + fonts updated (kit #' . $kit_id . ')';
		}
		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
			$log[] = 'Elementor CSS cache cleared';
		}
	} else {
		$log[] = 'Elementor not active — the page renders via the [fu_home] shortcode until Elementor is installed. Re-run this importer after activating Elementor.';
	}

	update_option( 'fu_imported_version', FU_VERSION );
	return $log;
}

/**
 * Auto-import on first activation.
 */
function fu_after_switch_theme() {
	if ( ! get_option( 'fu_imported_version' ) ) {
		set_transient( 'fu_import_log', fu_run_import( false ), HOUR_IN_SECONDS );
	}
}
add_action( 'after_switch_theme', 'fu_after_switch_theme' );

/**
 * Admin page.
 */
function fu_setup_menu() {
	add_theme_page( __( 'Future Ukraine Setup', 'future-ukraine' ), __( 'Future Ukraine Setup', 'future-ukraine' ), 'edit_theme_options', 'fu-setup', 'fu_setup_page' );
}
add_action( 'admin_menu', 'fu_setup_menu' );

/**
 * Admin page output + handler.
 */
function fu_setup_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$log = get_transient( 'fu_import_log' );
	delete_transient( 'fu_import_log' );

	if ( isset( $_POST['fu_import'] ) && check_admin_referer( 'fu_import' ) ) {
		$log = fu_run_import( ! empty( $_POST['fu_force'] ) );
	}
	$page_id = (int) get_option( 'fu_home_page_id' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Future Ukraine Setup', 'future-ukraine' ); ?></h1>
		<p><?php esc_html_e( 'Creates the one-page home (Hero, Mission, Goals, Donate, Reports, News, Volunteer, FAQ, Contact) as an Elementor page, sets it as the front page, creates menus and applies the brand colours and fonts to the Elementor kit.', 'future-ukraine' ); ?></p>
		<?php if ( ! defined( 'ELEMENTOR_VERSION' ) ) : ?>
			<div class="notice notice-warning inline"><p><?php esc_html_e( 'Elementor is not active. The site still works (sections render from the theme), but install and activate Elementor to edit sections visually, then run the import again.', 'future-ukraine' ); ?></p></div>
		<?php endif; ?>
		<?php if ( $log ) : ?>
			<div class="notice notice-success inline"><ul><?php foreach ( (array) $log as $l ) { echo '<li>' . esc_html( $l ) . '</li>'; } ?></ul></div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'fu_import' ); ?>
			<p><label><input type="checkbox" name="fu_force" value="1"> <?php esc_html_e( 'Reset the home page layout to the defaults (overwrites Elementor edits on that page)', 'future-ukraine' ); ?></label></p>
			<p><button class="button button-primary" name="fu_import" value="1"><?php esc_html_e( 'Run import', 'future-ukraine' ); ?></button>
			<?php if ( $page_id && get_post( $page_id ) ) : ?>
				<a class="button" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>"><?php esc_html_e( 'View home page', 'future-ukraine' ); ?></a>
				<?php if ( defined( 'ELEMENTOR_VERSION' ) ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $page_id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'future-ukraine' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>
			</p>
		</form>
		<h2><?php esc_html_e( 'Before going live', 'future-ukraine' ); ?></h2>
		<p><?php esc_html_e( 'The demo content contains placeholder bank details, crypto addresses, registration number and statistics from the original design. Replace them with real data in the Donate, About and Reports widgets.', 'future-ukraine' ); ?></p>
	</div>
	<?php
}

/**
 * Admin notice after activation.
 */
function fu_activation_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'themes' !== $screen->id || ! get_transient( 'fu_import_log' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-success is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'Future Ukraine: demo content imported.', 'future-ukraine' ),
		esc_url( admin_url( 'themes.php?page=fu-setup' ) ),
		esc_html__( 'See details', 'future-ukraine' )
	);
}
add_action( 'admin_notices', 'fu_activation_notice' );
