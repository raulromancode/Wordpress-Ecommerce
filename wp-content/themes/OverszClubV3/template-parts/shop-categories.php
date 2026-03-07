<?php
/**
 * Categorias curadas para la tienda principal.
 *
 * @package OverszClub
 */

$category_ids = array();

if ( is_tax( 'product_cat' ) ) {
	$current_term = get_queried_object();

	if ( $current_term instanceof WP_Term ) {
		$child_terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'parent'     => $current_term->term_id,
				'fields'     => 'ids',
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! is_wp_error( $child_terms ) && ! empty( $child_terms ) ) {
			$category_ids = array_map( 'absint', $child_terms );
		}
	}
}

if ( empty( $category_ids ) ) {
	$category_ids = overszclub_get_shop_category_ids();
}

if ( empty( $category_ids ) ) {
	return;
}
?>
<section class="shop-categories" data-reveal>
	<div class="section-heading">
		<p class="eyebrow"><?php echo esc_html__( 'Categorias', 'overszclub' ); ?></p>
		<h2><?php echo esc_html( overszclub_get_theme_mod( 'shop_categories_title' ) ); ?></h2>
		<p><?php echo esc_html( overszclub_get_theme_mod( 'shop_categories_intro' ) ); ?></p>
	</div>
	<div class="shop-categories__grid">
		<?php foreach ( $category_ids as $category_id ) : ?>
			<?php
			$term = get_term( $category_id, 'product_cat' );

			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$image_html   = $thumbnail_id ? wp_get_attachment_image( $thumbnail_id, 'large' ) : '';
			$link         = get_term_link( $term );
			$count        = (int) $term->count;
			?>
			<?php if ( ! is_wp_error( $link ) ) : ?>
				<article class="shop-category-card">
					<a href="<?php echo esc_url( $link ); ?>">
						<div class="shop-category-card__media">
							<?php if ( $image_html ) : ?>
								<?php echo wp_kses_post( $image_html ); ?>
							<?php else : ?>
								<div class="shop-category-card__placeholder"></div>
							<?php endif; ?>
						</div>
						<div class="shop-category-card__body">
							<p class="eyebrow"><?php echo esc_html__( 'Categoria', 'overszclub' ); ?></p>
							<h3><?php echo esc_html( $term->name ); ?></h3>
							<p><?php echo esc_html( sprintf( _n( '%d producto', '%d productos', $count, 'overszclub' ), $count ) ); ?></p>
						</div>
					</a>
				</article>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
