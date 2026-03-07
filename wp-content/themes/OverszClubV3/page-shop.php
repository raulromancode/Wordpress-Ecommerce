<?php
/**
 * Página tienda.
 *
 * @package OverszClub
 */

get_header();

$shop_heading         = overszclub_get_current_shop_title();
$shop_intro           = is_tax() ? term_description() : '';
$is_category_archive = is_tax( 'product_cat' ) || is_tax( 'product_tag' );
$display_mode         = overszclub_get_shop_display_mode();
$show_categories      = in_array( $display_mode, array( 'subcategories', 'both' ), true );
$show_products        = in_array( $display_mode, array( 'products', 'both' ), true );

if ( ! $is_category_archive && ! $show_categories && ! $show_products ) {
	$show_categories = true;
}

$current_term_query = array();

if ( $is_category_archive ) {
	$current_term = get_queried_object();

	if ( $current_term instanceof WP_Term ) {
		$current_term_query[] = array(
			'taxonomy' => $current_term->taxonomy,
			'field'    => 'term_id',
			'terms'    => array( $current_term->term_id ),
		);
	}
}
?>
<main class="shop-page container">
	<header class="section-heading section-heading--page">
		<p class="eyebrow"><?php echo esc_html__( 'Shop', 'overszclub' ); ?></p>
		<h1><?php echo esc_html( $shop_heading ); ?></h1>
		<?php if ( $shop_intro ) : ?>
			<div class="shop-page__intro"><?php echo wp_kses_post( wpautop( $shop_intro ) ); ?></div>
		<?php endif; ?>
	</header>
	<?php if ( ! overszclub_has_woocommerce() ) : ?>
		<p class="overszclub-empty-state"><?php echo esc_html__( 'Activa WooCommerce para mostrar la tienda.', 'overszclub' ); ?></p>
	<?php else : ?>
		<?php if ( $is_category_archive ) : ?>
			<?php if ( $show_categories ) : ?>
				<?php get_template_part( 'template-parts/shop-categories' ); ?>
			<?php endif; ?>

			<?php if ( $show_products ) : ?>
				<section class="shop-layout">
					<aside class="shop-filters" data-reveal>
						<form class="shop-filters__form" data-shop-filters>
							<div class="filter-group">
								<label for="shop-category"><?php echo esc_html__( 'Categoria', 'overszclub' ); ?></label>
								<select id="shop-category" name="category">
									<option value=""><?php echo esc_html__( 'Todas', 'overszclub' ); ?></option>
									<?php $product_terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) ); ?>
									<?php if ( ! is_wp_error( $product_terms ) ) : ?>
										<?php foreach ( $product_terms as $term ) : ?>
										<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( is_tax( 'product_cat', $term->slug ) ); ?>><?php echo esc_html( $term->name ); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div class="filter-group">
								<label for="shop-size"><?php echo esc_html__( 'Talla', 'overszclub' ); ?></label>
								<select id="shop-size" name="size">
									<option value=""><?php echo esc_html__( 'Todas', 'overszclub' ); ?></option>
									<?php foreach ( array( 's', 'm', 'l', 'xl' ) as $size ) : ?>
										<option value="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( strtoupper( $size ) ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="filter-group">
								<label for="shop-min-price"><?php echo esc_html__( 'Precio minimo', 'overszclub' ); ?></label>
								<input id="shop-min-price" type="number" name="min_price" min="0" step="1" placeholder="0">
							</div>
							<div class="filter-group">
								<label for="shop-max-price"><?php echo esc_html__( 'Precio maximo', 'overszclub' ); ?></label>
								<input id="shop-max-price" type="number" name="max_price" min="0" step="1" placeholder="500">
							</div>
							<button class="button button--secondary button--full" type="reset" data-shop-reset>
								<?php echo esc_html__( 'Limpiar filtros', 'overszclub' ); ?>
							</button>
						</form>
					</aside>
					<div class="shop-results" data-reveal>
						<div class="shop-results__meta">
							<p data-shop-count><?php echo esc_html__( 'Mostrando seleccion editorial', 'overszclub' ); ?></p>
						</div>
						<div data-shop-results>
							<?php
							echo overszclub_render_products_grid(
								array(
									'posts_per_page' => 12,
									'tax_query'      => $current_term_query,
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
					</div>
				</section>
			<?php endif; ?>
		<?php else : ?>
			<?php if ( $show_categories ) : ?>
				<?php get_template_part( 'template-parts/shop-categories' ); ?>
			<?php endif; ?>

			<?php if ( $show_products ) : ?>
				<section class="section shop-page__catalog-preview" data-reveal>
					<div class="section-heading">
						<p class="eyebrow"><?php echo esc_html__( 'Productos', 'overszclub' ); ?></p>
						<h2><?php echo esc_html__( 'Seleccion destacada', 'overszclub' ); ?></h2>
						<p><?php echo esc_html__( 'Explora una muestra editorial de la coleccion completa antes de entrar en cada categoria.', 'overszclub' ); ?></p>
					</div>
					<div data-shop-results>
						<?php echo overszclub_render_products_grid( array( 'posts_per_page' => 6 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</section>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
