<?php
/**
 * Wrapper WooCommerce.
 *
 * @package OverszClub
 */

get_header();

$page_title = overszclub_get_current_shop_title();

if ( is_cart() ) {
	$page_title = __( 'Carrito', 'overszclub' );
} elseif ( is_checkout() ) {
	$page_title = __( 'Finalizar compra', 'overszclub' );
} elseif ( is_account_page() ) {
	$page_title = __( 'Mi cuenta', 'overszclub' );
}
?>
<main class="woocommerce-page">
	<div class="container">
		<header class="section-heading section-heading--page">
			<p class="eyebrow"><?php echo esc_html__( 'WooCommerce', 'overszclub' ); ?></p>
			<h1><?php echo esc_html( $page_title ); ?></h1>
		</header>
		<?php woocommerce_content(); ?>
	</div>
</main>
<?php
get_footer();
