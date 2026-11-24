<?php
/**
 * Shop section — WooCommerce products.
 *
 * @package FutureUkraine
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;
$a = $args;

if ( ! function_exists( 'wc_get_products' ) ) {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<section id="' . esc_attr( $a['anchor'] ) . '" class="fu-section"><div class="fu-container"><p class="fu-center fu-muted">' . esc_html__( 'Shop section: activate WooCommerce to show products.', 'future-ukraine' ) . '</p></div></section>';
	}
	return;
}

$qargs = array(
	'status'     => 'publish',
	'limit'      => max( 1, min( 24, absint( $a['count'] ) ) ),
	'visibility' => 'catalog',
	'orderby'    => $a['orderby'],
	'order'      => in_array( $a['orderby'], array( 'price', 'title' ), true ) ? 'ASC' : 'DESC',
);
if ( 'popularity' === $a['orderby'] ) {
	$qargs['orderby']  = 'meta_value_num';
	$qargs['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery
	$qargs['order']    = 'DESC';
}
if ( 'price' === $a['orderby'] ) {
	$qargs['orderby']  = 'meta_value_num';
	$qargs['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery
}
if ( ! empty( $a['category'] ) ) {
	$qargs['category'] = array_map( 'sanitize_title', array_map( 'trim', explode( ',', $a['category'] ) ) );
}
if ( 'yes' === $a['featured_only'] ) {
	$qargs['featured'] = true;
}
$products = wc_get_products( $qargs );
$view_all = $a['view_all_url'] ? $a['view_all_url'] : wc_get_page_permalink( 'shop' );
?>
<section id="<?php echo esc_attr( $a['anchor'] ); ?>" class="fu-section fu-shop">
	<div class="fu-bg-hero-gradient" style="opacity:.3"></div>
	<div class="fu-container">
		<?php fu_section_header( $a ); ?>

		<?php if ( $products ) : ?>
			<div class="fu-cards fu-cards--shop woocommerce">
				<?php
				foreach ( $products as $product ) :
					$link = $product->get_permalink();
					?>
					<article class="fu-card fu-card--flex fu-product">
						<a class="fu-product__img" href="<?php echo esc_url( $link ); ?>">
							<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore ?>
							<?php if ( $product->is_on_sale() ) : ?><span class="fu-tag fu-product__sale"><?php esc_html_e( 'Sale', 'future-ukraine' ); ?></span><?php endif; ?>
						</a>
						<h3 class="fu-card__title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
						<?php if ( $product->get_short_description() ) : ?>
							<p class="fu-card__text"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 16 ) ); ?></p>
						<?php endif; ?>
						<div class="fu-product__price fu-grow"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
						<?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
							<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo (int) $product->get_id(); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" class="fu-btn fu-btn--primary fu-btn--block add_to_cart_button ajax_add_to_cart product_type_simple" rel="nofollow" aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"><?php echo fu_icon( 'shopping-bag' ); // phpcs:ignore ?><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
						<?php else : ?>
							<a href="<?php echo esc_url( $link ); ?>" class="fu-btn fu-btn--outline fu-btn--block"><?php echo esc_html( $a['view_text'] ); ?></a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( $a['view_all_text'] && $view_all ) : ?>
				<div class="fu-center fu-mt-lg">
					<a class="fu-btn fu-btn--outline fu-btn--lg" href="<?php echo esc_url( fu_link( $view_all ) ); ?>"><?php echo esc_html( $a['view_all_text'] ); ?> <?php echo fu_icon( 'arrow-right' ); // phpcs:ignore ?></a>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p class="fu-center fu-muted"><?php echo esc_html( $a['empty_text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
