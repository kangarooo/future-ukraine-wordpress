<?php
/**
 * Fallback template: blog index, archives, search.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
get_header();
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
	?>
	<section class="fu-section fu-archive">
		<div class="fu-container">
			<header class="fu-section__header">
				<div class="fu-kicker">// <?php echo is_post_type_archive( 'report' ) ? esc_html__( 'TRANSPARENCY', 'future-ukraine' ) : esc_html__( 'LATEST UPDATES', 'future-ukraine' ); ?></div>
				<h1 class="fu-section__title">
					<?php
					if ( is_search() ) {
						/* translators: %s search query */
						printf( esc_html__( 'Search: %s', 'future-ukraine' ), '<span class="fu-c-gold">' . esc_html( get_search_query() ) . '</span>' );
					} elseif ( is_post_type_archive( 'report' ) ) {
						echo esc_html__( 'MONTHLY', 'future-ukraine' ) . ' <span class="fu-c-gold">' . esc_html__( 'REPORTS', 'future-ukraine' ) . '</span>';
					} elseif ( is_archive() ) {
						echo wp_kses_post( get_the_archive_title() );
					} else {
						echo esc_html__( 'FIELD', 'future-ukraine' ) . ' <span class="fu-c-blue">' . esc_html__( 'NEWS', 'future-ukraine' ) . '</span>';
					}
					?>
				</h1>
			</header>
			<?php if ( have_posts() ) : ?>
				<div class="fu-cards fu-cards--3">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'fu-card fu-card--flex' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="fu-news__thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
							<?php endif; ?>
							<div class="fu-meta fu-mb"><?php echo fu_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( get_the_date() ); ?></div>
							<h2 class="fu-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="fu-card__text fu-grow"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
							<a class="fu-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'future-ukraine' ); ?> <?php echo fu_icon( 'arrow-up-right' ); // phpcs:ignore ?></a>
						</article>
					<?php endwhile; ?>
				</div>
				<div class="fu-pagination"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
			<?php else : ?>
				<p class="fu-center fu-muted"><?php esc_html_e( 'Nothing found.', 'future-ukraine' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
endif;
get_footer();
