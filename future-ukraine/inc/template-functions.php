<?php
/**
 * Rendering helpers.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render one section.
 *
 * @param string $name     Section name.
 * @param array  $settings Settings.
 * @param bool   $echo     Echo or return.
 * @return string|void
 */
function fu_render_section( $name, $settings = array(), $echo = true ) {
	$schema = fu_sections_schema();
	if ( empty( $schema[ $name ] ) ) {
		return '';
	}
	$args = fu_section_args( $name, (array) $settings );

	ob_start();
	get_template_part( 'template-parts/sections/' . $name, null, $args );
	$html = ob_get_clean();

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in templates.
		return;
	}
	return $html;
}

/**
 * Colour modifier class.
 *
 * @param string $color gold|blue|light.
 * @return string
 */
function fu_color_class( $color ) {
	$color = in_array( $color, array( 'gold', 'blue', 'light' ), true ) ? $color : 'gold';
	return 'fu-c-' . $color;
}

/**
 * Standard section header.
 *
 * @param array $a Section args.
 */
function fu_section_header( $a ) {
	?>
	<header class="fu-section__header">
		<?php if ( ! empty( $a['kicker'] ) ) : ?>
			<div class="fu-kicker"><?php echo esc_html( $a['kicker'] ); ?></div>
		<?php endif; ?>
		<h2 class="fu-section__title">
			<?php echo esc_html( $a['title'] ); ?>
			<?php if ( ! empty( $a['highlight'] ) ) : ?>
				<span class="<?php echo esc_attr( fu_color_class( $a['highlight_color'] ) ); ?>"><?php echo esc_html( $a['highlight'] ); ?></span>
			<?php endif; ?>
		</h2>
		<?php if ( ! empty( $a['intro'] ) ) : ?>
			<p class="fu-section__intro"><?php echo esc_html( $a['intro'] ); ?></p>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Turn "#anchor" into a link that also works off the front page.
 *
 * @param string $url URL or anchor.
 * @return string
 */
function fu_link( $url ) {
	$url = (string) $url;
	if ( '' === $url ) {
		return '#';
	}
	if ( 0 === strpos( $url, '#' ) && strlen( $url ) > 1 && ! is_front_page() ) {
		return home_url( '/' ) . $url;
	}
	return $url;
}

/**
 * Format money.
 *
 * @param float  $n        Amount.
 * @param string $currency Symbol.
 * @return string
 */
function fu_money( $n, $currency = '€' ) {
	return $currency . number_format_i18n( (float) $n );
}

/**
 * Copy-to-clipboard field.
 *
 * @param string $value Value.
 * @param string $label Accessible label.
 * @param bool   $short Truncate visually (crypto).
 */
function fu_copy_field( $value, $label, $short = false ) {
	$display = $value;
	if ( $short && strlen( $value ) > 18 ) {
		$display = substr( $value, 0, 12 ) . '...' . substr( $value, -4 );
	}
	?>
	<div class="fu-copy">
		<code class="fu-copy__value<?php echo $short ? ' fu-copy__value--sm' : ''; ?>" title="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $display ); ?></code>
		<button type="button" class="fu-copy__btn" data-fu-copy="<?php echo esc_attr( $value ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s field */ __( 'Copy %s', 'future-ukraine' ), $label ) ); ?>">
			<?php echo fu_icon( 'copy', 'fu-copy__icon-copy' ); // phpcs:ignore ?>
			<?php echo fu_icon( 'check', 'fu-copy__icon-check' ); // phpcs:ignore ?>
		</button>
	</div>
	<?php
}

/**
 * Shortcode: [fu_section name="hero"] — any field can be passed as an attribute.
 *
 * @param array $atts Attributes.
 * @return string
 */
function fu_section_shortcode( $atts ) {
	$atts = (array) $atts;
	$name = isset( $atts['name'] ) ? sanitize_key( $atts['name'] ) : '';
	unset( $atts['name'] );
	return fu_render_section( $name, $atts, false );
}
add_shortcode( 'fu_section', 'fu_section_shortcode' );

/**
 * Shortcode: [fu_home] — all home sections in order.
 *
 * @return string
 */
function fu_home_shortcode() {
	$out = '';
	foreach ( fu_home_sections() as $s ) {
		$out .= fu_render_section( $s, array(), false );
	}
	return $out;
}
add_shortcode( 'fu_home', 'fu_home_shortcode' );

/**
 * Brand mark (logo or UA square + wordmark).
 *
 * @param string $context header|footer.
 */
function fu_brand( $context = 'header' ) {
	$line1 = fu_opt( 'brand_line_1' );
	$line2 = fu_opt( 'brand_line_2' );
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fu-brand fu-brand--<?php echo esc_attr( $context ); ?>" rel="home">
		<?php if ( has_custom_logo() ) : ?>
			<?php
			$logo_id = get_theme_mod( 'custom_logo' );
			echo wp_get_attachment_image( $logo_id, 'thumbnail', false, array( 'class' => 'fu-brand__logo', 'alt' => get_bloginfo( 'name' ) ) );
			?>
		<?php else : ?>
			<span class="fu-brand__mark"><?php echo esc_html( fu_opt( 'brand_mark' ) ); ?></span>
		<?php endif; ?>
		<span class="fu-brand__text">
			<span class="fu-brand__line1"><?php echo esc_html( $line1 ); ?></span>
			<span class="fu-brand__line2"><?php echo esc_html( $line2 ); ?></span>
		</span>
	</a>
	<?php
}

/**
 * Fallback list of links when no WP menu is assigned.
 *
 * @param array  $links label => url.
 * @param string $class ul class.
 */
function fu_links_fallback( $links, $class ) {
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $links as $label => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( fu_link( $url ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Primary nav.
 */
function fu_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'fu-nav__list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}
	fu_links_fallback( fu_default_nav(), 'fu-nav__list' );
}

/**
 * Make "#anchor" custom menu links work on inner pages too.
 *
 * @param array $atts Link attributes.
 * @return array
 */
function fu_menu_anchor_links( $atts ) {
	if ( ! empty( $atts['href'] ) && 0 === strpos( $atts['href'], '#' ) ) {
		$atts['href'] = fu_link( $atts['href'] );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fu_menu_anchor_links' );
