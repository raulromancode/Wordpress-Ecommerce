<?php
/**
 * Página genérica.
 *
 * @package OverszClub
 */

get_header();
?>
<main class="page-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<article class="editorial-page container">
			<header class="section-heading">
				<p class="eyebrow"><?php echo esc_html__( 'Pagina', 'overszclub' ); ?></p>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="editorial-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
