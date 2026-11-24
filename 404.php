<?php
/**
 * 404.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="fu-section fu-404">
	<div class="fu-grid-pattern" style="opacity:.3"></div>
	<div class="fu-container fu-center">
		<div class="fu-kicker">// ERROR 404</div>
		<h1 class="fu-section__title"><?php esc_html_e( 'PAGE', 'future-ukraine' ); ?> <span class="fu-c-gold"><?php esc_html_e( 'NOT FOUND', 'future-ukraine' ); ?></span></h1>
		<p class="fu-section__intro"><?php esc_html_e( 'The page you are looking for does not exist.', 'future-ukraine' ); ?></p>
		<a class="fu-btn fu-btn--hero fu-btn--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to Home', 'future-ukraine' ); ?></a>
	</div>
</section>
<?php
get_footer();
