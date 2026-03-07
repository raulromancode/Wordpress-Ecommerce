<?php
/**
 * Navegacion mi cuenta.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;
?>
<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e( 'Navegacion de cuenta', 'overszclub' ); ?>">
	<p class="eyebrow"><?php esc_html_e( 'Mi cuenta', 'overszclub' ); ?></p>
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
