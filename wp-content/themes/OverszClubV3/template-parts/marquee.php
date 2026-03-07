<?php
/**
 * Marquee animado.
 *
 * @package OverszClub
 */

$text = overszclub_get_theme_mod( 'marquee_text' );
?>
<section class="marquee" aria-label="<?php echo esc_attr__( 'Titulares de marca', 'overszclub' ); ?>">
	<div class="marquee__track" data-marquee-track>
		<?php for ( $i = 0; $i < 4; $i++ ) : ?>
			<span><?php echo esc_html( $text ); ?></span>
		<?php endfor; ?>
	</div>
</section>
