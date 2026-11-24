<?php
/**
 * Financial reports section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a = $args;
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-reports">
	<div class="fu-bg-hero-gradient" style="opacity:.3"></div>
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>

		<div class="fu-cards fu-cards--2 fu-narrow">
			<?php foreach ( $a['reports'] as $r ) : ?>
				<div class="fu-card fu-card--hover fu-report">
					<div class="fu-card__icon fu-card__icon--sm fu-bg-muted"><?php echo fu_icon( 'file-text' ); // phpcs:ignore ?></div>
					<div class="fu-report__body">
						<?php if ( ! empty( $r['type'] ) ) : ?><span class="fu-tag"><?php echo esc_html( $r['type'] ); ?></span><?php endif; ?>
						<h4 class="fu-report__title"><?php echo esc_html( $r['title'] ?? '' ); ?></h4>
						<div class="fu-meta"><?php echo fu_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( $r['date'] ?? '' ); ?></div>
					</div>
					<?php if ( ! empty( $r['file'] ) ) : ?>
						<a class="fu-report__dl" href="<?php echo esc_url( $r['file'] ); ?>" download aria-label="<?php echo esc_attr( sprintf( /* translators: %s report */ __( 'Download %s', 'future-ukraine' ), $r['title'] ?? '' ) ); ?>"><?php echo fu_icon( 'download' ); // phpcs:ignore ?></a>
					<?php else : ?>
						<span class="fu-report__dl is-disabled" aria-hidden="true"><?php echo fu_icon( 'download' ); // phpcs:ignore ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $a['view_all_text'] ) : ?>
			<div class="fu-center fu-mt-lg">
				<a class="fu-btn fu-btn--outline fu-btn--lg" href="<?php echo esc_url( fu_link( $a['view_all_url'] ) ); ?>"><?php echo esc_html( $a['view_all_text'] ); ?> <?php echo fu_icon( 'external-link' ); // phpcs:ignore ?></a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $a['stats'] ) ) : ?>
			<div class="fu-card fu-statbar">
				<?php foreach ( $a['stats'] as $s ) : ?>
					<div>
						<div class="fu-stat__value <?php echo esc_attr( fu_color_class( $s['color'] ?? 'gold' ) ); ?>"><?php echo esc_html( $s['value'] ?? '' ); ?></div>
						<div class="fu-stat__label"><?php echo esc_html( $s['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
