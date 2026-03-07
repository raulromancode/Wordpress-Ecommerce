<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<?php if (is_page('sobre-nosotros') || is_page('about')) : ?>

    <!-- ══ PÁGINA SOBRE NOSOTROS ══════════════════════════════════ -->
    <div class="container mt-nav">
        <div class="about-page">
            <p class="eyebrow">— Nuestra historia</p>
            <h1 class="about-page__title">
                Sobre<br><em>nosotros</em>
            </h1>
            <?php
            $img = get_the_post_thumbnail_url(get_the_ID(),'full')
                ?: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=1400&q=80';
            ?>
            <img src="<?php echo esc_url($img); ?>" alt="Sobre nosotros" class="about-page__cover">

            <?php if (get_the_content()) : the_content();
            else : ?>
                <div class="about-page__block">
                    <h2>Empezamos con una idea simple.</h2>
                    <p>La moda no debería comprometer la calidad. Fundamos esta tienda con la convicción de que cada prenda debe ser duradera, versátil y honesta en cuanto a su fabricación y precio.</p>
                </div>
                <div class="about-page__block">
                    <h2>Materiales que importan.</h2>
                    <p>Trabajamos únicamente con tejidos de gramaje medio-alto: algodón orgánico, lino lavado y mezclas técnicas que mantienen la forma después de cientos de lavados.</p>
                </div>
                <div class="about-page__block">
                    <h2>Sin temporadas artificiales.</h2>
                    <p>Lanzamos cuando el producto está listo. Colecciones pequeñas, bien pensadas, sin el caos del fast fashion. Si te gusta algo, probablemente siga disponible el mes que viene.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else : ?>

    <!-- ══ PÁGINA GENÉRICA ════════════════════════════════════════ -->
    <main class="container mt-nav" style="padding:60px 0; max-width:900px; min-height:60vh;">
        <h1 style="font-size:clamp(36px,5vw,64px);margin-bottom:32px;"><?php the_title(); ?></h1>
        <div style="font-size:14px;color:var(--c-text-mute);line-height:1.9;">
            <?php the_content(); ?>
        </div>
    </main>

<?php endif; ?>

<?php endwhile; ?>
<?php get_footer(); ?>
