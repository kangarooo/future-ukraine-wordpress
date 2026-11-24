<?php
/**
 * Field news section — latest WordPress posts.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a     = $args;
$qargs = array(
	'post_type'           => 'post',
	'posts_per_page'      => max( 1, min( 12, absint( $a['count'] ) ) ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( ! empty( $a['category'] ) ) {
	$qargs['category_name'] = sanitize_title( $a['category'] );
}
$news = new WP_Query( $qargs );
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-news">
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>
		<?php if ( $news->have_posts() ) : ?>
			<div class="fu-cards fu-cards--3">
				<?php
				while ( $news->have_posts() ) :
					$news->the_post();
					?>
					<article class="fu-card fu-card--flex">
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="fu-news__thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
						<?php endif; ?>
						<div class="fu-meta fu-mb"><?php echo fu_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( get_the_date() ); ?></div>
						<h3 class="fu-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="fu-card__text fu-grow"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
						<a class="fu-link" href="<?php the_permalink(); ?>"><?php echo esc_html( $a['read_more'] ); ?> <?php echo fu_icon( 'arrow-up-right' ); // phpcs:ignore ?></a>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="fu-center fu-muted"><?php echo esc_html( $a['empty_text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
