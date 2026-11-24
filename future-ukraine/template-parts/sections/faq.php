<?php
/**
 * FAQ section.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a = $args;
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-faq">
	<div class="fu-container fu-container--md">
		<?php fu_section_header( $a ); ?>
		<div class="fu-card fu-accordion">
			<?php foreach ( $a['faqs'] as $f ) : ?>
				<details class="fu-accordion__item" name="fu-faq">
					<summary class="fu-accordion__q"><?php echo esc_html( $f['q'] ?? '' ); ?><?php echo fu_icon( 'chevron-down', 'fu-accordion__chev' ); // phpcs:ignore ?></summary>
					<div class="fu-accordion__a"><?php echo wp_kses_post( wpautop( $f['a'] ?? '' ) ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
