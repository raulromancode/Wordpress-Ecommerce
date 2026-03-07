<?php
/**
 * Plantilla para entradas de blog individuales.
 */
get_header();
while ( have_posts() ) : the_post();
?>
<main class="wrap mt-nav" style="padding:60px 0;min-height:60vh;max-width:800px;">

    <p class="eyebrow" style="margin-bottom:16px;">
        <?php echo esc_html( get_the_date() ); ?>
    </p>
    <h1 style="font-size:clamp(44px,6vw,80px);margin-bottom:32px;"><?php the_title(); ?></h1>

    <?php if ( has_post_thumbnail() ) : ?>
        <div style="margin-bottom:40px;">
            <?php the_post_thumbnail( 'full', [ 'style' => 'width:100%;aspect-ratio:16/9;object-fit:cover;' ] ); ?>
        </div>
    <?php endif; ?>

    <div style="font-size:15px;color:var(--muted);line-height:1.95;">
        <?php the_content(); ?>
    </div>

</main>
<?php endwhile; get_footer(); ?>
