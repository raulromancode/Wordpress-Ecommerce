<?php
/**
 * Plantilla de reserva.
 * WordPress la usa cuando no encuentra una más específica.
 */
get_header();
?>
<main class="wrap mt-nav" style="padding:60px 0;min-height:60vh;">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <h1 style="font-size:clamp(40px,5vw,72px);margin-bottom:24px;"><?php the_title(); ?></h1>
        <div style="font-size:14px;color:var(--muted);line-height:1.9;max-width:780px;"><?php the_content(); ?></div>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
