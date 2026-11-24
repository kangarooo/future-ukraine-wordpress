<?php
/**
 * Volunteer signup section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a     = $args;
$areas = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $a['areas'] ) ) );
$uid   = wp_unique_id( 'fu-v-' );
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-volunteer">
	<div class="fu-container fu-container--sm">
		<?php fu_section_header( $a ); ?>
		<?php fu_form_notice( 'volunteer', $a['success'] ); ?>
		<form class="fu-card fu-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php fu_form_hidden_fields( 'volunteer' ); ?>
			<div class="fu-form__grid">
				<p class="fu-form__field">
					<label for="<?php echo esc_attr( $uid ); ?>-name"><?php esc_html_e( 'Name', 'future-ukraine' ); ?></label>
					<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-name" name="fu_name" type="text" maxlength="100" required autocomplete="name">
				</p>
				<p class="fu-form__field">
					<label for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'Email', 'future-ukraine' ); ?></label>
					<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-email" name="fu_email" type="email" maxlength="255" required autocomplete="email">
				</p>
				<p class="fu-form__field">
					<label for="<?php echo esc_attr( $uid ); ?>-phone"><?php esc_html_e( 'Phone (optional)', 'future-ukraine' ); ?></label>
					<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-phone" name="fu_phone" type="tel" maxlength="30" autocomplete="tel">
				</p>
				<p class="fu-form__field">
					<label for="<?php echo esc_attr( $uid ); ?>-area"><?php esc_html_e( 'How can you help?', 'future-ukraine' ); ?></label>
					<select class="fu-input" id="<?php echo esc_attr( $uid ); ?>-area" name="fu_area" required>
						<option value=""><?php esc_html_e( 'Choose one', 'future-ukraine' ); ?></option>
						<?php foreach ( $areas as $area ) : ?>
							<option value="<?php echo esc_attr( $area ); ?>"><?php echo esc_html( $area ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			</div>
			<p class="fu-form__field">
				<label for="<?php echo esc_attr( $uid ); ?>-msg"><?php esc_html_e( 'Message (optional)', 'future-ukraine' ); ?></label>
				<textarea class="fu-input" id="<?php echo esc_attr( $uid ); ?>-msg" name="fu_message" rows="4" maxlength="1000"></textarea>
			</p>
			<button type="submit" class="fu-btn fu-btn--primary fu-btn--lg fu-btn--block"><?php echo fu_icon( 'hand-heart' ); // phpcs:ignore ?><?php echo esc_html( $a['button_text'] ); ?></button>
		</form>
	</div>
</section>
