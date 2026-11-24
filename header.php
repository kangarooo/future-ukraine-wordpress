<?php
/**
 * Header.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#fu-main"><?php esc_html_e( 'Skip to content', 'future-ukraine' ); ?></a>
<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	?>
	<nav class="fu-nav" data-fu-nav aria-label="<?php esc_attr_e( 'Primary', 'future-ukraine' ); ?>">
		<div class="fu-container fu-nav__inner">
			<?php fu_brand( 'header' ); ?>
			<div class="fu-nav__desktop">
				<?php fu_primary_nav(); ?>
				<?php if ( fu_opt( 'nav_cta_text' ) ) : ?>
					<a class="fu-btn fu-btn--hero fu-btn--sm" href="<?php echo esc_url( fu_link( fu_opt( 'nav_cta_url' ) ) ); ?>"><?php echo esc_html( fu_opt( 'nav_cta_text' ) ); ?></a>
				<?php endif; ?>
			</div>
			<button class="fu-nav__toggle" type="button" aria-expanded="false" aria-controls="fu-nav-mobile" aria-label="<?php esc_attr_e( 'Toggle menu', 'future-ukraine' ); ?>">
				<?php echo fu_icon( 'menu', 'fu-nav__open' ); // phpcs:ignore ?>
				<?php echo fu_icon( 'x', 'fu-nav__close' ); // phpcs:ignore ?>
			</button>
		</div>
		<div id="fu-nav-mobile" class="fu-nav__mobile fu-container" hidden>
			<?php fu_primary_nav(); ?>
			<?php if ( fu_opt( 'nav_cta_text' ) ) : ?>
				<a class="fu-btn fu-btn--hero fu-btn--block" href="<?php echo esc_url( fu_link( fu_opt( 'nav_cta_url' ) ) ); ?>"><?php echo esc_html( fu_opt( 'nav_cta_text' ) ); ?></a>
			<?php endif; ?>
		</div>
	</nav>
	<?php
endif;
?>
<main id="fu-main" class="fu-main">
