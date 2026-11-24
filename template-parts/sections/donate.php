<?php
/**
 * Donate section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a       = $args;
$amounts = array_filter( array_map( 'absint', explode( ',', (string) $a['amounts'] ) ) );
$def     = absint( $a['default_amount'] );
$trust   = array_filter( array_map( 'trim', explode( '|', (string) $a['trust'] ) ) );
$dots    = array( 'fu-c-gold', 'fu-c-blue' );
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-donate fu-section--tint">
	<div class="fu-grid-pattern" style="opacity:.2"></div>
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>

		<div class="fu-donate__quick fu-card" data-fu-donate data-url="<?php echo esc_url( $a['donate_url'] ); ?>" data-currency="<?php echo esc_attr( $a['currency'] ); ?>">
			<h3 class="fu-card__title fu-center"><?php echo esc_html( $a['quick_title'] ); ?></h3>
			<div class="fu-amounts">
				<?php foreach ( $amounts as $amt ) : ?>
					<button type="button" class="fu-amount<?php echo $amt === $def ? ' is-active' : ''; ?>" data-amount="<?php echo (int) $amt; ?>"><?php echo esc_html( $a['currency'] . $amt ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="fu-donate__row">
				<label class="screen-reader-text" for="fu-custom-amount"><?php esc_html_e( 'Custom amount', 'future-ukraine' ); ?></label>
				<input id="fu-custom-amount" class="fu-input fu-mono" type="number" min="1" step="1" placeholder="<?php esc_attr_e( 'Custom amount', 'future-ukraine' ); ?>" value="<?php echo $def ? (int) $def : ''; ?>">
				<a class="fu-btn fu-btn--hero fu-btn--lg fu-donate__go" href="<?php echo esc_url( $a['donate_url'] ? $a['donate_url'] : '#fu-donate-methods' ); ?>"><?php echo fu_icon( 'credit-card' ); // phpcs:ignore ?><span><?php esc_html_e( 'Donate', 'future-ukraine' ); ?> <span data-fu-amount-label><?php echo esc_html( $a['currency'] . $def ); ?></span></span></a>
			</div>
		</div>

		<div id="fu-donate-methods" class="fu-cards fu-cards--3 fu-donate__methods">
			<div class="fu-card">
				<div class="fu-method__head">
					<div class="fu-card__icon fu-card__icon--sm fu-bg-blue"><?php echo fu_icon( 'building' ); // phpcs:ignore ?></div>
					<div><h4 class="fu-method__title"><?php echo esc_html( $a['bank_title'] ); ?></h4><p class="fu-method__sub"><?php echo esc_html( $a['bank_sub'] ); ?></p></div>
				</div>
				<div class="fu-field-label">IBAN</div>
				<?php fu_copy_field( $a['iban'], 'IBAN' ); ?>
				<div class="fu-field-label">SWIFT</div>
				<?php fu_copy_field( $a['swift'], 'SWIFT' ); ?>
			</div>

			<div class="fu-card">
				<div class="fu-method__head">
					<div class="fu-card__icon fu-card__icon--sm"><?php echo fu_icon( 'credit-card' ); // phpcs:ignore ?></div>
					<div><h4 class="fu-method__title"><?php echo esc_html( $a['paypal_title'] ); ?></h4><p class="fu-method__sub"><?php echo esc_html( $a['paypal_sub'] ); ?></p></div>
				</div>
				<p class="fu-card__text"><?php echo esc_html( $a['paypal_text'] ); ?></p>
				<?php fu_copy_field( $a['paypal_email'], 'PayPal' ); ?>
				<?php if ( $a['paypal_url'] ) : ?>
					<a class="fu-btn fu-btn--outline fu-btn--block" href="<?php echo esc_url( $a['paypal_url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open PayPal', 'future-ukraine' ); ?> <?php echo fu_icon( 'external-link' ); // phpcs:ignore ?></a>
				<?php endif; ?>
			</div>

			<div class="fu-card">
				<div class="fu-method__head">
					<div class="fu-card__icon fu-card__icon--sm fu-bg-orange"><?php echo fu_icon( 'bitcoin' ); // phpcs:ignore ?></div>
					<div><h4 class="fu-method__title"><?php echo esc_html( $a['crypto_title'] ); ?></h4><p class="fu-method__sub"><?php echo esc_html( $a['crypto_sub'] ); ?></p></div>
				</div>
				<?php if ( $a['btc'] ) : ?>
					<div class="fu-field-label">Bitcoin (BTC)</div>
					<?php fu_copy_field( $a['btc'], 'BTC', true ); ?>
				<?php endif; ?>
				<?php if ( $a['eth'] ) : ?>
					<div class="fu-field-label">Ethereum (ETH)</div>
					<?php fu_copy_field( $a['eth'], 'ETH', true ); ?>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $trust ) : ?>
			<p class="fu-trust">
				<?php
				$i = 0;
				foreach ( $trust as $t ) {
					if ( $i ) {
						echo '<span class="fu-trust__sep">|</span>';
					}
					printf( '<span><span class="%s">●</span> %s</span>', esc_attr( $dots[ $i % 2 ] ), esc_html( $t ) );
					$i++;
				}
				?>
			</p>
		<?php endif; ?>
	</div>
</section>
