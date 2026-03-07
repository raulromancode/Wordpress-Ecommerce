<?php
/**
 * Footer del tema.
 *
 * @package OverszClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="container site-footer__inner">
		<div>
			<p class="eyebrow"><?php echo esc_html__( 'Editorial streetwear', 'overszclub' ); ?></p>
			<p class="site-footer__title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		</div>
		<div class="site-footer__menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => 'overszclub_footer_menu_fallback',
				)
			);
			?>
		</div>
		<div class="site-footer__socials">
			<a href="<?php echo esc_url( overszclub_get_theme_mod( 'social_instagram' ) ); ?>">Instagram</a>
			<a href="<?php echo esc_url( overszclub_get_theme_mod( 'social_tiktok' ) ); ?>">TikTok</a>
			<a href="<?php echo esc_url( overszclub_get_theme_mod( 'social_pinterest' ) ); ?>">Pinterest</a>
		</div>
	</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
