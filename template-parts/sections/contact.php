<?php
/**
 * Contact section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a      = $args;
$uid    = wp_unique_id( 'fu-c-' );
$social = array(
	'facebook'  => $a['facebook'],
	'instagram' => $a['instagram'],
	'linkedin'  => $a['linkedin'],
	'twitter'   => $a['twitter'],
);
$social = array_filter( $social );
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-contact fu-section--tint">
	<div class="fu-grid-pattern" style="opacity:.1"></div>
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>

		<div class="fu-contact__grid">
			<div>
				<ul class="fu-contact__list">
					<?php if ( $a['email'] ) : ?>
						<li><span class="fu-card__icon fu-card__icon--sm"><?php echo fu_icon( 'mail' ); // phpcs:ignore ?></span><div><h4>EMAIL</h4><a href="mailto:<?php echo esc_attr( antispambot( $a['email'] ) ); ?>"><?php echo esc_html( antispambot( $a['email'] ) ); ?></a></div></li>
					<?php endif; ?>
					<?php if ( $a['phone'] ) : ?>
						<li><span class="fu-card__icon fu-card__icon--sm fu-bg-blue"><?php echo fu_icon( 'phone' ); // phpcs:ignore ?></span><div><h4>PHONE</h4><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $a['phone'] ) ); ?>"><?php echo esc_html( $a['phone'] ); ?></a></div></li>
					<?php endif; ?>
					<?php if ( $a['address'] ) : ?>
						<li><span class="fu-card__icon fu-card__icon--sm"><?php echo fu_icon( 'map-pin' ); // phpcs:ignore ?></span><div><h4>ADDRESS</h4><p><?php echo nl2br( esc_html( $a['address'] ) ); // phpcs:ignore ?></p></div></li>
					<?php endif; ?>
				</ul>

				<?php if ( $social ) : ?>
					<h4 class="fu-contact__h"><?php esc_html_e( 'FOLLOW US', 'future-ukraine' ); ?></h4>
					<div class="fu-social">
						<?php foreach ( $social as $net => $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $net ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo fu_icon( $net ); // phpcs:ignore ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="fu-card fu-hours">
					<h4 class="fu-contact__h"><?php esc_html_e( 'WORKING HOURS', 'future-ukraine' ); ?></h4>
					<div class="fu-hours__row"><span><?php esc_html_e( 'Monday - Friday', 'future-ukraine' ); ?></span><span class="fu-mono"><?php echo esc_html( $a['hours_week'] ); ?></span></div>
					<div class="fu-hours__row"><span><?php esc_html_e( 'Saturday', 'future-ukraine' ); ?></span><span class="fu-mono"><?php echo esc_html( $a['hours_sat'] ); ?></span></div>
					<div class="fu-hours__row"><span><?php esc_html_e( 'Sunday', 'future-ukraine' ); ?></span><span class="fu-mono fu-c-gold"><?php echo esc_html( $a['hours_sun'] ); ?></span></div>
				</div>
			</div>

			<div class="fu-card fu-contact__form">
				<h3 class="fu-card__title"><?php echo esc_html( $a['form_title'] ); ?></h3>
				<?php fu_form_notice( 'contact', $a['success'] ); ?>
				<form class="fu-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php fu_form_hidden_fields( 'contact' ); ?>
					<div class="fu-form__grid">
						<p class="fu-form__field">
							<label class="fu-label-mono" for="<?php echo esc_attr( $uid ); ?>-name">NAME</label>
							<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-name" name="fu_name" type="text" maxlength="100" placeholder="<?php esc_attr_e( 'Your name', 'future-ukraine' ); ?>" required autocomplete="name">
						</p>
						<p class="fu-form__field">
							<label class="fu-label-mono" for="<?php echo esc_attr( $uid ); ?>-email">EMAIL</label>
							<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-email" name="fu_email" type="email" maxlength="255" placeholder="your@email.com" required autocomplete="email">
						</p>
					</div>
					<p class="fu-form__field">
						<label class="fu-label-mono" for="<?php echo esc_attr( $uid ); ?>-subject">SUBJECT</label>
						<input class="fu-input" id="<?php echo esc_attr( $uid ); ?>-subject" name="fu_subject" type="text" maxlength="200" placeholder="<?php esc_attr_e( 'How can we help?', 'future-ukraine' ); ?>">
					</p>
					<p class="fu-form__field">
						<label class="fu-label-mono" for="<?php echo esc_attr( $uid ); ?>-msg">MESSAGE</label>
						<textarea class="fu-input" id="<?php echo esc_attr( $uid ); ?>-msg" name="fu_message" rows="5" maxlength="2000" placeholder="<?php esc_attr_e( 'Your message...', 'future-ukraine' ); ?>" required></textarea>
					</p>
					<button type="submit" class="fu-btn fu-btn--hero fu-btn--lg fu-btn--block"><?php echo fu_icon( 'send' ); // phpcs:ignore ?><?php echo esc_html( $a['button_text'] ); ?></button>
				</form>
			</div>
		</div>
	</div>
</section>
