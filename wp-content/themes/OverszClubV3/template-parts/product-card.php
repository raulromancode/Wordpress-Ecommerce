<?php
/**
 * Tarjeta de producto.
 *
 * @package OverszClub
 */

$product_id = isset( $args['product_id'] ) ? absint( $args['product_id'] ) : get_the_ID();
$product    = wc_get_product( $product_id );

if ( ! $product ) {
	return;
}

$sizes = overszclub_get_product_sizes( $product );
$requires_size = overszclub_product_requires_size( $product );
$badges = overszclub_get_product_badges( $product );
$category = overszclub_get_product_primary_category( $product_id );
?>
<article class="product-card" data-reveal>
	<a class="product-card__media" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
		<?php echo wp_kses_post( $product->get_image( 'large' ) ); ?>
		<?php if ( ! empty( $badges ) ) : ?>
			<div class="product-card__badges">
				<?php foreach ( $badges as $badge ) : ?>
					<span class="product-badge"><?php echo esc_html( $badge ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<span class="product-card__quickview" data-quickview-open="<?php echo esc_attr( $product_id ); ?>">
			<?php echo esc_html__( 'Vista rapida', 'overszclub' ); ?>
		</span>
	</a>
	<div class="product-card__body">
		<div>
			<?php if ( $category ) : ?>
				<p class="product-card__category"><?php echo esc_html( $category ); ?></p>
			<?php endif; ?>
			<h3><a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
			<p class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
		</div>
		<form class="ajax-add-to-cart product-card__form" data-product-form>
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
			<input type="hidden" name="quantity" value="1">
			<?php if ( $requires_size ) : ?>
				<div class="size-selector size-selector--compact">
					<?php foreach ( $sizes as $size ) : ?>
						<label class="size-chip">
							<input type="radio" name="size" value="<?php echo esc_attr( strtoupper( $size ) ); ?>">
							<span><?php echo esc_html( strtoupper( $size ) ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<button class="button button--ghost button--full" type="submit">
				<?php echo esc_html__( 'ANADIR AL CARRITO', 'overszclub' ); ?>
			</button>
			<a class="product-card__link" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
				<?php echo esc_html__( 'Ver producto', 'overszclub' ); ?>
			</a>
			<p class="product-feedback" data-product-feedback aria-live="polite"></p>
		</form>
	</div>
</article>
