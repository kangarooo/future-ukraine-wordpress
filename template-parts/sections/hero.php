<?php
/**
 * Hero section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a  = $args;
$bg = ! empty( $a['background'] ) ? $a['background'] : FU_URI . '/assets/img/hero-bg.jpg';
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-hero">
	<div class="fu-hero__bg" style="background-image:url('<?php echo esc_url( $bg ); ?>')"><div class="fu-hero__shade"></div></div>
	<div class="fu-hero__grid fu-grid-pattern"></div>
	<div class="fu-hero__scan fu-scan-line"></div>

	<div class="fu-container fu-hero__content">
		<?php if ( $a['badge'] ) : ?>
			<div class="fu-badge fu-anim"><span class="fu-badge__dot"></span><span><?php echo esc_html( $a['badge'] ); ?></span></div>
		<?php endif; ?>

		<h1 class="fu-hero__title fu-anim fu-d1">
			<span class="fu-c-gold fu-glow-gold"><?php echo esc_html( $a['title_1'] ); ?></span>
			<span><?php echo esc_html( $a['title_2'] ); ?></span><br>
			<span class="fu-c-blue fu-glow-blue"><?php echo esc_html( $a['title_3'] ); ?></span>
		</h1>

		<?php if ( $a['subtitle'] ) : ?>
			<p class="fu-hero__subtitle fu-anim fu-d2"><?php echo esc_html( $a['subtitle'] ); ?></p>
		<?php endif; ?>

		<div class="fu-hero__ctas fu-anim fu-d3">
			<?php if ( $a['cta_primary_text'] ) : ?>
				<a class="fu-btn fu-btn--hero fu-btn--xl" href="<?php echo esc_url( fu_link( $a['cta_primary_url'] ) ); ?>"><?php echo fu_icon( 'heart' ); // phpcs:ignore ?><?php echo esc_html( $a['cta_primary_text'] ); ?></a>
			<?php endif; ?>
			<?php if ( $a['cta_secondary_text'] ) : ?>
				<a class="fu-btn fu-btn--hero-outline fu-btn--xl" href="<?php echo esc_url( fu_link( $a['cta_secondary_url'] ) ); ?>"><?php echo esc_html( $a['cta_secondary_text'] ); ?><?php echo fu_icon( 'arrow-right' ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $a['stats'] ) ) : ?>
			<div class="fu-hero__stats fu-anim fu-d4" style="--fu-cols:<?php echo (int) count( $a['stats'] ); ?>">
				<?php foreach ( $a['stats'] as $s ) : ?>
					<div class="fu-hero__stat">
						<div class="fu-stat__value <?php echo esc_attr( fu_color_class( $s['color'] ?? 'gold' ) ); ?>"><?php echo esc_html( $s['value'] ?? '' ); ?></div>
						<div class="fu-stat__label"><?php echo esc_html( $s['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( 'yes' === $a['show_scroll'] ) : ?>
		<div class="fu-hero__scroll" aria-hidden="true"><div class="fu-hero__mouse"><div class="fu-hero__wheel"></div></div></div>
	<?php endif; ?>
</section>
