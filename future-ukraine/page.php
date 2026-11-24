<?php
/**
 * Default page template.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	$fu_elementor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
	if ( $fu_elementor ) {
		the_content();
		continue;
	}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'fu-page' ); ?>>
		<div class="fu-page__head"><div class="fu-container fu-container--md">
			<div class="fu-kicker">// <?php echo esc_html( strtoupper( get_bloginfo( 'name' ) ) ); ?></div>
			<?php the_title( '<h1 class="fu-page__title">', '</h1>' ); ?>
		</div></div>
		<div class="fu-container fu-container--md fu-prose">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="fu-container fu-container--md fu-prose">';
		comments_template();
		echo '</div>';
	}
endwhile;
get_footer();
