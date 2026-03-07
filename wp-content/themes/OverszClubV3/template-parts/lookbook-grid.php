<?php
/**
 * Grid de lookbook.
 *
 * @package OverszClub
 */
?>
<section class="lookbook-grid">
	<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
		<?php
		$image_id = absint( get_theme_mod( 'lookbook_image_' . $i ) );
		$image    = $image_id ? wp_get_attachment_image( $image_id, 'full' ) : '';
		?>
		<?php if ( $image ) : ?>
			<figure class="lookbook-card" data-reveal>
				<div class="lookbook-card__image">
					<?php echo wp_kses_post( $image ); ?>
				</div>
			</figure>
		<?php endif; ?>
	<?php endfor; ?>
</section>
