<?php
/**
 * Fundraising goals section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a = $args;
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-campaigns">
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>
		<div class="fu-cards fu-cards--3">
			<?php
			foreach ( $a['campaigns'] as $c ) :
				$goal   = max( 0, (float) ( $c['goal'] ?? 0 ) );
				$raised = max( 0, (float) ( $c['raised'] ?? 0 ) );
				$pct    = $goal > 0 ? (int) min( 100, round( $raised / $goal * 100 ) ) : 0;
				?>
				<div class="fu-card fu-card--flex">
					<div class="fu-card__icon fu-card__icon--sm"><?php echo fu_icon( 'target' ); // phpcs:ignore ?></div>
					<h3 class="fu-card__title"><?php echo esc_html( $c['title'] ?? '' ); ?></h3>
					<p class="fu-card__text fu-grow"><?php echo esc_html( $c['description'] ?? '' ); ?></p>
					<div class="fu-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo (int) $pct; ?>"><div class="fu-progress__bar" style="width:<?php echo (int) $pct; ?>%"></div></div>
					<div class="fu-progress__meta">
						<span class="fu-c-gold"><?php echo esc_html( fu_money( $raised, $a['currency'] ) ); ?></span>
						<span class="fu-muted"><?php echo esc_html( sprintf( /* translators: 1 percent, 2 goal */ __( '%1$s%% of %2$s', 'future-ukraine' ), $pct, fu_money( $goal, $a['currency'] ) ) ); ?></span>
					</div>
					<?php if ( ! empty( $c['button_text'] ) ) : ?>
						<a class="fu-btn fu-btn--primary" href="<?php echo esc_url( fu_link( $c['button_url'] ?? '#donate' ) ); ?>"><?php echo esc_html( $c['button_text'] ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
