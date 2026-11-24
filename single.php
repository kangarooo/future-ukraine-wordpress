<?php
/**
 * Single post.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
get_header();
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'fu-page' ); ?>>
			<div class="fu-page__head"><div class="fu-container fu-container--md">
				<div class="fu-kicker">// <?php esc_html_e( 'FIELD NEWS', 'future-ukraine' ); ?></div>
				<?php the_title( '<h1 class="fu-page__title">', '</h1>' ); ?>
				<div class="fu-meta"><?php echo fu_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( get_the_date() ); ?></div>
			</div></div>
			<div class="fu-container fu-container--md fu-prose">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="fu-page__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
				<?php endif; ?>
				<?php
				the_content();
				wp_link_pages();
				?>
				<nav class="fu-postnav">
					<?php previous_post_link( '<span>%link</span>', '← %title' ); ?>
					<?php next_post_link( '<span>%link</span>', '%title →' ); ?>
				</nav>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</article>
		<?php
	endwhile;
endif;
get_footer();
