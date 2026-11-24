<?php
/**
 * "Report" post type — same slug and URL (/reports/) as the previous "Ukraine Support Latvia" theme,
 * so existing reports keep working. Shown in the Reports section (Elementor widget) and at /reports/.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post type + its meta.
 */
function fu_register_reports() {
	register_post_type(
		'report',
		array(
			'labels'       => array(
				'name'          => __( 'Reports', 'future-ukraine' ),
				'singular_name' => __( 'Report', 'future-ukraine' ),
				'add_new'       => __( 'Add New Report', 'future-ukraine' ),
				'add_new_item'  => __( 'Add New Report', 'future-ukraine' ),
				'edit_item'     => __( 'Edit Report', 'future-ukraine' ),
				'new_item'      => __( 'New Report', 'future-ukraine' ),
				'view_item'     => __( 'View Report', 'future-ukraine' ),
				'search_items'  => __( 'Search Reports', 'future-ukraine' ),
				'all_items'     => __( 'All Reports', 'future-ukraine' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'reports' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'menu_icon'    => 'dashicons-media-document',
			'show_in_rest' => true,
			'menu_position' => 25,
		)
	);

	$meta = array(
		'fu_report_type' => __( 'Report type label (e.g. Financial, Audit)', 'future-ukraine' ),
		'fu_report_file' => __( 'Download URL (PDF)', 'future-ukraine' ),
	);
	foreach ( $meta as $key => $desc ) {
		register_post_meta(
			'report',
			$key,
			array(
				'type'              => 'string',
				'description'       => $desc,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'fu_report_file' === $key ? 'esc_url_raw' : 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	// Flush rewrite rules once per theme version so /reports/ resolves.
	if ( get_option( 'fu_rewrite_version' ) !== FU_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'fu_rewrite_version', FU_VERSION );
	}
}
add_action( 'init', 'fu_register_reports' );

/**
 * Report details box (type + file).
 */
function fu_report_metabox() {
	add_meta_box( 'fu_report_details', __( 'Report details', 'future-ukraine' ), 'fu_report_metabox_html', 'report', 'side' );
}
add_action( 'add_meta_boxes', 'fu_report_metabox' );

/**
 * Metabox HTML.
 *
 * @param WP_Post $post Post.
 */
function fu_report_metabox_html( $post ) {
	wp_nonce_field( 'fu_report_meta', 'fu_report_nonce' );
	?>
	<p><label for="fu_report_type"><strong><?php esc_html_e( 'Type label', 'future-ukraine' ); ?></strong></label><br>
	<input type="text" id="fu_report_type" name="fu_report_type" class="widefat" placeholder="Financial, Impact, Audit…" value="<?php echo esc_attr( get_post_meta( $post->ID, 'fu_report_type', true ) ); ?>"></p>
	<p><label for="fu_report_file"><strong><?php esc_html_e( 'Download URL (PDF)', 'future-ukraine' ); ?></strong></label><br>
	<input type="url" id="fu_report_file" name="fu_report_file" class="widefat" placeholder="https://…/report.pdf" value="<?php echo esc_attr( get_post_meta( $post->ID, 'fu_report_file', true ) ); ?>"></p>
	<p class="description"><?php esc_html_e( 'Tip: upload the PDF in Media and paste its URL here.', 'future-ukraine' ); ?></p>
	<?php
}

/**
 * Save metabox.
 *
 * @param int $post_id Post ID.
 */
function fu_report_save( $post_id ) {
	if ( ! isset( $_POST['fu_report_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fu_report_nonce'] ) ), 'fu_report_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'fu_report_type', isset( $_POST['fu_report_type'] ) ? sanitize_text_field( wp_unslash( $_POST['fu_report_type'] ) ) : '' );
	update_post_meta( $post_id, 'fu_report_file', isset( $_POST['fu_report_file'] ) ? esc_url_raw( wp_unslash( $_POST['fu_report_file'] ) ) : '' );
}
add_action( 'save_post_report', 'fu_report_save' );

/**
 * Report posts for the Reports section, normalised to the repeater row shape.
 *
 * @param int $count How many.
 * @return array
 */
function fu_report_rows( $count = 4 ) {
	$q    = new WP_Query(
		array(
			'post_type'      => 'report',
			'posts_per_page' => max( 1, min( 24, (int) $count ) ),
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	$rows = array();
	foreach ( $q->posts as $p ) {
		$type   = get_post_meta( $p->ID, 'fu_report_type', true );
		$rows[] = array(
			'type'  => $type ? $type : __( 'Report', 'future-ukraine' ),
			'title' => get_the_title( $p ),
			'date'  => get_the_date( 'F Y', $p ),
			'file'  => (string) get_post_meta( $p->ID, 'fu_report_file', true ),
			'link'  => get_permalink( $p ),
		);
	}
	return $rows;
}
