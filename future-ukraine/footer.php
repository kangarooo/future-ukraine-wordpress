<?php
/**
 * Footer.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
	?>
	<footer class="fu-footer">
		<div class="fu-container">
			<div class="fu-footer__grid">
				<div class="fu-footer__brand">
					<?php fu_brand( 'footer' ); ?>
					<?php if ( fu_opt( 'footer_text' ) ) : ?><p class="fu-footer__text"><?php echo esc_html( fu_opt( 'footer_text' ) ); ?></p><?php endif; ?>
					<?php if ( fu_opt( 'footer_reg' ) ) : ?><p class="fu-footer__reg"><?php echo esc_html( fu_opt( 'footer_reg' ) ); ?></p><?php endif; ?>
				</div>
				<div>
					<h4 class="fu-footer__h"><?php esc_html_e( 'QUICK LINKS', 'future-ukraine' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'fu-footer__list', 'depth' => 1 ) );
					} else {
						fu_links_fallback( array( 'About Us' => '#about', 'Donate' => '#donate', 'Reports' => '#reports', 'Contact' => '#contact' ), 'fu-footer__list' );
					}
					?>
				</div>
				<div>
					<h4 class="fu-footer__h"><?php esc_html_e( 'LEGAL', 'future-ukraine' ); ?></h4>
					<?php
					if ( has_nav_menu( 'legal' ) ) {
						wp_nav_menu( array( 'theme_location' => 'legal', 'container' => false, 'menu_class' => 'fu-footer__list', 'depth' => 1 ) );
					} else {
						fu_links_fallback( array( 'Privacy Policy' => get_privacy_policy_url() ? get_privacy_policy_url() : '#', 'Terms of Use' => '#', 'Cookie Policy' => '#' ), 'fu-footer__list' );
					}
					?>
				</div>
			</div>
			<div class="fu-footer__bottom">
				<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( fu_opt( 'copyright' ) ); ?></p>
				<p class="fu-footer__made"><?php esc_html_e( 'Made with', 'future-ukraine' ); ?> <?php echo fu_icon( 'heart', 'fu-heart' ); // phpcs:ignore ?> <?php echo esc_html( fu_opt( 'made_with' ) ); ?></p>
			</div>
		</div>
	</footer>
	<?php
endif;
wp_footer();
?>
</body>
</html>
