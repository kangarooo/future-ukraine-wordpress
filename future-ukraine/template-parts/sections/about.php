<?php
/**
 * About / Mission section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a = $args;
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-about">
	<div class="fu-bg-hero-gradient" style="opacity:.5"></div>
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>

		<div class="fu-cards fu-cards--4">
			<?php foreach ( $a['missions'] as $m ) : ?>
				<div class="fu-card fu-card--hover">
					<div class="fu-card__icon"><?php echo fu_icon( $m['icon'] ?? 'shield' ); // phpcs:ignore ?></div>
					<h3 class="fu-card__title"><?php echo esc_html( $m['title'] ?? '' ); ?></h3>
					<p class="fu-card__text"><?php echo esc_html( $m['description'] ?? '' ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="fu-about__org">
			<div>
				<h3 class="fu-about__org-title"><?php echo esc_html( $a['org_title'] ); ?> <span class="fu-c-blue"><?php echo esc_html( $a['org_highlight'] ); ?></span></h3>
				<p class="fu-about__org-text"><?php echo esc_html( $a['org_text'] ); ?></p>
				<ul class="fu-dots">
					<?php foreach ( $a['org_items'] as $i ) : ?>
						<li><span class="fu-dot <?php echo esc_attr( fu_color_class( $i['color'] ?? 'gold' ) ); ?>"></span><span><?php echo esc_html( $i['text'] ?? '' ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="fu-stat-grid">
				<?php foreach ( $a['org_stats'] as $s ) : ?>
					<div class="fu-card fu-card--stat">
						<div class="fu-stat__value <?php echo esc_attr( fu_color_class( $s['color'] ?? 'gold' ) ); ?>"><?php echo esc_html( $s['value'] ?? '' ); ?></div>
						<div class="fu-stat__label"><?php echo esc_html( $s['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
