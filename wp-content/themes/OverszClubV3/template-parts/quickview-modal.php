<?php
/**
 * Modal quick view.
 *
 * @package OverszClub
 */

$product_id = isset( $args['product_id'] ) ? absint( $args['product_id'] ) : 0;
$product    = wc_get_product( $product_id );

if ( ! $product ) {
	return;
}

$sizes = overszclub_get_product_sizes( $product );
$requires_size = overszclub_product_requires_size( $product );
$badges = overszclub_get_product_badges( $product );
$category = overszclub_get_product_primary_category( $product_id );
?>
<div class="quickview-modal" data-quickview-modal>
	<div class="quickview-modal__overlay" data-quickview-close></div>
	<div class="quickview-modal__dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Vista rapida del producto', 'overszclub' ); ?>" tabindex="-1">
		<button class="quickview-modal__close" type="button" data-quickview-close aria-label="<?php echo esc_attr__( 'Cerrar vista rapida', 'overszclub' ); ?>">
			<span></span>
			<span></span>
		</button>
		<div class="quickview-modal__media">
			<?php echo wp_kses_post( $product->get_image( 'large' ) ); ?>
		</div>
		<div class="quickview-modal__content">
			<p class="eyebrow"><?php echo esc_html__( 'Vista rapida', 'overszclub' ); ?></p>
			<?php if ( $category ) : ?>
				<p class="product-card__category"><?php echo esc_html( $category ); ?></p>
			<?php endif; ?>
			<h2><?php echo esc_html( $product->get_name() ); ?></h2>
			<?php if ( ! empty( $badges ) ) : ?>
				<div class="product-card__badges">
					<?php foreach ( $badges as $badge ) : ?>
						<span class="product-badge"><?php echo esc_html( $badge ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<div class="quickview-modal__description">
				<?php echo wp_kses_post( wpautop( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ) ); ?>
			</div>
			<form class="ajax-add-to-cart" data-product-form>
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
				<input type="hidden" name="quantity" value="1">
				<?php if ( $requires_size ) : ?>
					<div class="size-selector">
						<?php foreach ( $sizes as $size ) : ?>
							<label class="size-chip">
								<input type="radio" name="size" value="<?php echo esc_attr( strtoupper( $size ) ); ?>">
								<span><?php echo esc_html( strtoupper( $size ) ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<button class="button button--primary button--full" type="submit">
					<?php echo esc_html__( 'ANADIR AL CARRITO', 'overszclub' ); ?>
				</button>
				<a class="product-card__link" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
					<?php echo esc_html__( 'Ir al producto', 'overszclub' ); ?>
				</a>
				<p class="product-feedback" data-product-feedback aria-live="polite"></p>
			</form>
		</div>
	</div>
</div>
