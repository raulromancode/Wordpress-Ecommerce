<?php
/**
 * Template Name: Lookbook
 */
get_header(); ?>

<div class="mt-nav">
    <div class="lookbook-header">
        <h1>Look<em style="font-style:normal;color:var(--c-accent);">book</em></h1>
        <p>Colección editorial — <?php echo date('Y'); ?></p>
    </div>
    <?php get_template_part('template-parts/lookbook-grid'); ?>
</div>

<?php get_footer(); ?>
