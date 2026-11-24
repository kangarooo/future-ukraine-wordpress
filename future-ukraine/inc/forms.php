<?php
/**
 * Volunteer + contact forms.
 *
 * Replaces the Supabase "volunteer_signups" table of the React app:
 * submissions are stored as a private "fu_submission" post type (wp-admin → Submissions)
 * and e-mailed to the site admin.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the submissions post type.
 */
function fu_register_submissions() {
	register_post_type(
		'fu_submission',
		array(
			'labels'          => array(
				'name'          => __( 'Submissions', 'future-ukraine' ),
				'singular_name' => __( 'Submission', 'future-ukraine' ),
				'all_items'     => __( 'All submissions', 'future-ukraine' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-groups',
			'menu_position'   => 26,
			'supports'        => array( 'title', 'editor', 'custom-fields' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'fu_register_submissions' );

/**
 * Admin list columns.
 *
 * @param array $cols Columns.
 * @return array
 */
function fu_submission_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['fu_type']  = __( 'Form', 'future-ukraine' );
			$new['fu_email'] = __( 'Email', 'future-ukraine' );
		}
	}
	return $new;
}
add_filter( 'manage_fu_submission_posts_columns', 'fu_submission_columns' );

/**
 * Admin column values.
 *
 * @param string $col     Column.
 * @param int    $post_id Post ID.
 */
function fu_submission_column_value( $col, $post_id ) {
	if ( 'fu_type' === $col ) {
		echo esc_html( ucfirst( (string) get_post_meta( $post_id, '_fu_type', true ) ) );
	}
	if ( 'fu_email' === $col ) {
		$email = (string) get_post_meta( $post_id, '_fu_email', true );
		printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
	}
}
add_action( 'manage_fu_submission_posts_custom_column', 'fu_submission_column_value', 10, 2 );

/**
 * Hidden fields common to both forms.
 *
 * @param string $type volunteer|contact.
 */
function fu_form_hidden_fields( $type ) {
	wp_nonce_field( 'fu_form_' . $type, 'fu_nonce' );
	?>
	<input type="hidden" name="action" value="fu_form">
	<input type="hidden" name="fu_type" value="<?php echo esc_attr( $type ); ?>">
	<input type="hidden" name="fu_return" value="<?php echo esc_url( fu_current_url() ); ?>">
	<div class="fu-hp" aria-hidden="true">
		<label>Website <input type="text" name="fu_website" tabindex="-1" autocomplete="off"></label>
	</div>
	<?php
}

/**
 * Current URL without query.
 *
 * @return string
 */
function fu_current_url() {
	if ( is_singular() ) {
		return get_permalink();
	}
	return home_url( '/' );
}

/**
 * Status notice for a form after redirect.
 *
 * @param string $type    Form type.
 * @param string $success Success message.
 */
function fu_form_notice( $type, $success ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( empty( $_GET['fu_form'] ) || sanitize_key( wp_unslash( $_GET['fu_form'] ) ) !== $type ) {
		return;
	}
	$status = isset( $_GET['fu_status'] ) ? sanitize_key( wp_unslash( $_GET['fu_status'] ) ) : '';
	// phpcs:enable
	$messages = array(
		'ok'      => $success,
		'invalid' => __( 'Please fill in your name, a valid email and the required fields.', 'future-ukraine' ),
		'wait'    => __( 'Please wait a minute before sending again.', 'future-ukraine' ),
		'error'   => __( 'Could not send. Please try again.', 'future-ukraine' ),
	);
	if ( ! isset( $messages[ $status ] ) ) {
		return;
	}
	printf(
		'<div class="fu-notice fu-notice--%1$s" role="status">%2$s</div>',
		'ok' === $status ? 'ok' : 'err',
		esc_html( $messages[ $status ] )
	);
}

/**
 * Handle a submission.
 */
function fu_handle_form() {
	$type   = isset( $_POST['fu_type'] ) ? sanitize_key( wp_unslash( $_POST['fu_type'] ) ) : '';
	$return = isset( $_POST['fu_return'] ) ? esc_url_raw( wp_unslash( $_POST['fu_return'] ) ) : home_url( '/' );
	$return = wp_validate_redirect( $return, home_url( '/' ) );

	$back = function ( $status ) use ( $type, $return ) {
		$anchor = 'volunteer' === $type ? '#volunteer' : '#contact';
		wp_safe_redirect( add_query_arg( array( 'fu_form' => $type, 'fu_status' => $status ), $return ) . $anchor );
		exit;
	};

	if ( ! in_array( $type, array( 'volunteer', 'contact' ), true ) ) {
		$back( 'error' );
	}
	if ( ! isset( $_POST['fu_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fu_nonce'] ) ), 'fu_form_' . $type ) ) {
		$back( 'error' );
	}
	// Honeypot: pretend success for bots.
	if ( ! empty( $_POST['fu_website'] ) ) {
		$back( 'ok' );
	}

	// Simple per-IP throttle.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'fu_rl_' . md5( $ip . $type );
	if ( get_transient( $key ) ) {
		$back( 'wait' );
	}

	$name    = isset( $_POST['fu_name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['fu_name'] ) ), 0, 100 ) : '';
	$email   = isset( $_POST['fu_email'] ) ? mb_substr( sanitize_email( wp_unslash( $_POST['fu_email'] ) ), 0, 255 ) : '';
	$message = isset( $_POST['fu_message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['fu_message'] ) ), 0, 2000 ) : '';
	$fields  = array();

	if ( 'volunteer' === $type ) {
		$fields['Phone'] = isset( $_POST['fu_phone'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['fu_phone'] ) ), 0, 30 ) : '';
		$fields['Area']  = isset( $_POST['fu_area'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['fu_area'] ) ), 0, 100 ) : '';
		if ( '' === $fields['Area'] ) {
			$back( 'invalid' );
		}
	} else {
		$fields['Subject'] = isset( $_POST['fu_subject'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['fu_subject'] ) ), 0, 200 ) : '';
		if ( '' === $message ) {
			$back( 'invalid' );
		}
	}

	if ( '' === $name || ! is_email( $email ) ) {
		$back( 'invalid' );
	}

	$body = '';
	foreach ( array_merge( array( 'Name' => $name, 'Email' => $email ), $fields ) as $k => $v ) {
		$body .= $k . ': ' . $v . "\n";
	}
	if ( '' !== $message ) {
		$body .= "\n" . $message . "\n";
	}

	$title = ( 'volunteer' === $type ? __( 'Volunteer', 'future-ukraine' ) : __( 'Message', 'future-ukraine' ) ) . ': ' . $name;
	if ( ! empty( $fields['Subject'] ) ) {
		$title .= ' — ' . $fields['Subject'];
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'fu_submission',
			'post_status'  => 'private',
			'post_title'   => $title,
			'post_content' => $body,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		$back( 'error' );
	}
	update_post_meta( $post_id, '_fu_type', $type );
	update_post_meta( $post_id, '_fu_email', $email );
	foreach ( $fields as $k => $v ) {
		update_post_meta( $post_id, '_fu_' . strtolower( $k ), $v );
	}

	$to = fu_opt( 'notify_email' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	wp_mail(
		$to,
		'[' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . '] ' . $title,
		$body . "\n" . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	set_transient( $key, 1, MINUTE_IN_SECONDS );
	$back( 'ok' );
}
add_action( 'admin_post_fu_form', 'fu_handle_form' );
add_action( 'admin_post_nopriv_fu_form', 'fu_handle_form' );
