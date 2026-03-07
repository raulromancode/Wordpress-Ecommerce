<?php
/**
 * Dashboard personalizado de cuenta.
 *
 * @package OverszClub
 */

defined( 'ABSPATH' ) || exit;

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>
<section class="overszclub-account-dashboard">
	<header class="overszclub-panel-heading">
		<p class="eyebrow"><?php esc_html_e( 'Resumen', 'overszclub' ); ?></p>
		<h2><?php esc_html_e( 'Tu espacio OverszClub', 'overszclub' ); ?></h2>
	</header>

	<p>
		<?php
		printf(
			wp_kses( __( 'Hola %1$s. Desde aqui puedes revisar tus %2$spedidos%3$s, editar tus %4$sdirecciones%5$s y actualizar la informacion de tu cuenta.', 'overszclub' ), $allowed_html ),
			'<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>',
			'<a href="' . esc_url( wc_get_account_endpoint_url( 'orders' ) ) . '">',
			'</a>',
			'<a href="' . esc_url( wc_get_account_endpoint_url( 'edit-address' ) ) . '">',
			'</a>'
		);
		?>
	</p>

	<div class="overszclub-account-dashboard__grid">
		<a class="overszclub-account-card" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
			<span class="eyebrow"><?php esc_html_e( 'Pedidos', 'overszclub' ); ?></span>
			<strong><?php esc_html_e( 'Ver historial y estado', 'overszclub' ); ?></strong>
		</a>
		<a class="overszclub-account-card" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>">
			<span class="eyebrow"><?php esc_html_e( 'Direcciones', 'overszclub' ); ?></span>
			<strong><?php esc_html_e( 'Gestionar envio y facturacion', 'overszclub' ); ?></strong>
		</a>
		<a class="overszclub-account-card" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>">
			<span class="eyebrow"><?php esc_html_e( 'Perfil', 'overszclub' ); ?></span>
			<strong><?php esc_html_e( 'Actualizar tus datos', 'overszclub' ); ?></strong>
		</a>
	</div>

	<?php do_action( 'woocommerce_account_dashboard' ); ?>
</section>

