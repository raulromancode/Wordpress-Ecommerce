<?php get_header(); ?>
<main class="container mt-nav" style="padding:60px 0;min-height:60vh;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h1 style="font-size:48px;margin-bottom:24px;"><?php the_title(); ?></h1>
        <div style="font-size:14px;color:var(--c-text-mute);line-height:1.9;"><?php the_content(); ?></div>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
