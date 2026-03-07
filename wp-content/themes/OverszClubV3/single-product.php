<?php
/**
 * Producto individual.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_product' ) ) {
	get_header();
	?>
	<main class="single-product-page container">
		<p class="overszclub-empty-state"><?php echo esc_html__( 'Activa WooCommerce para mostrar este producto.', 'overszclub' ); ?></p>
	</main>
	<?php
	get_footer();
	return;
}

get_header();

global $product;

$product = wc_get_product( get_the_ID() );
$gallery = $product ? $product->get_gallery_image_ids() : array();
$sizes   = $product ? overszclub_get_product_sizes( $product ) : array( 'S', 'M', 'L', 'XL' );
$requires_size = $product ? overszclub_product_requires_size( $product ) : false;
$badges = $product ? overszclub_get_product_badges( $product ) : array();
$category = $product ? overszclub_get_product_primary_category( $product->get_id() ) : '';
?>
<main class="single-product-page container">
	<?php if ( $product ) : ?>
		<section class="product-layout">
			<div class="product-gallery" data-reveal>
				<div class="product-gallery__main">
					<?php echo wp_kses_post( $product->get_image( 'full' ) ); ?>
				</div>
				<?php if ( ! empty( $gallery ) ) : ?>
					<div class="product-gallery__thumbs">
						<?php foreach ( $gallery as $image_id ) : ?>
							<div class="product-gallery__thumb">
								<?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'medium_large' ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="product-summary" data-reveal>
				<p class="eyebrow"><?php echo esc_html__( 'Producto', 'overszclub' ); ?></p>
				<?php if ( $category ) : ?>
					<p class="product-card__category"><?php echo esc_html( $category ); ?></p>
				<?php endif; ?>
				<h1><?php echo esc_html( $product->get_name() ); ?></h1>
				<?php if ( ! empty( $badges ) ) : ?>
					<div class="product-card__badges">
						<?php foreach ( $badges as $badge ) : ?>
							<span class="product-badge"><?php echo esc_html( $badge ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div class="product-summary__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				<div class="product-summary__excerpt">
					<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
				</div>
				<form class="ajax-add-to-cart" data-product-form>
					<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
					<input type="hidden" name="quantity" value="1">
					<?php if ( $requires_size ) : ?>
						<div class="size-selector">
							<span><?php echo esc_html__( 'Talla', 'overszclub' ); ?></span>
							<div class="size-selector__options">
								<?php foreach ( $sizes as $size ) : ?>
									<label class="size-chip">
										<input type="radio" name="size" value="<?php echo esc_attr( strtoupper( $size ) ); ?>">
										<span><?php echo esc_html( strtoupper( $size ) ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
					<button class="button button--primary button--full" type="submit">
						<?php echo esc_html__( 'ANADIR AL CARRITO', 'overszclub' ); ?>
					</button>
					<a class="button button--secondary button--full" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
						<?php echo esc_html__( 'Comprar ahora', 'overszclub' ); ?>
					</a>
					<p class="product-feedback" data-product-feedback aria-live="polite"></p>
				</form>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
