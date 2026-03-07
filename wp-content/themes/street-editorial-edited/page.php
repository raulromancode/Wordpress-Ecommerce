<?php
/**
 * Plantilla para páginas genéricas de WordPress.
 * WordPress la usa cuando no hay una plantilla más específica (page-{slug}.php).
 */
get_header();
while ( have_posts() ) : the_post();
?>
<main class="wrap mt-nav" style="padding:60px 0;min-height:60vh;">
    <h1 style="font-size:clamp(40px,5vw,72px);margin-bottom:28px;"><?php the_title(); ?></h1>
    <div class="page-content" style="font-size:14px;color:var(--muted);line-height:1.9;max-width:800px;">
        <?php the_content(); ?>
    </div>
</main>
<?php endwhile; get_footer(); ?>
