<?php
/**
 * Template Name: Nosotros
 */
get_header();
while (have_posts()) : the_post();

$cover = get_the_post_thumbnail_url(get_the_ID(),'full')
      ?: get_theme_mod('oc_about_img','');
$has_content = !empty(trim(strip_tags(get_the_content())));
?>

<div class="about-page mt-nav">

    <?php if ($cover) : ?>
    <div class="about-hero">
        <img src="<?php echo esc_url($cover); ?>" alt="<?php bloginfo('name'); ?>">
        <div class="about-hero__overlay"></div>
        <div class="about-hero__content">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
    <?php else : ?>
    <div style="padding:80px var(--gutter) 0;text-align:center;">
        <h1><?php the_title(); ?></h1>
    </div>
    <?php endif; ?>

    <?php if ($has_content) : ?>
        <div class="wrap" style="max-width:900px;padding-top:80px;padding-bottom:40px;">
            <div style="font-size:14px;color:var(--c-muted);line-height:2;">
                <?php the_content(); ?>
            </div>
        </div>
    <?php else : ?>

    <!-- Contenido editorial por defecto -->
    <div class="about-quote reveal">
        <blockquote>"Cada prenda es un statement. Sin excusas."</blockquote>
        <cite>— OverszClub Studio</cite>
    </div>

    <div class="about-blocks wrap">
        <?php
        $blocks = [
            ['The Beginning', 'OverszClub nació en 2022 de una pregunta simple: ¿por qué el streetwear no recibe el mismo tratamiento editorial que la alta moda? Queríamos prendas que hablasen por sí solas.'],
            ['The Craft',     'Cada pieza se desarrolla durante meses. Empezamos por la tela: algodón orgánico de 380 gsm para hoodies, lino lavado para las piezas de verano. Producción en lotes de máximo 200 unidades por colorway.'],
            ['No Seasons',    'No creemos en el calendario de la moda. Lanzamos drops cuando el producto está terminado. Sin sprints. Sin rebajas artificiales. Solo piezas que duran una década en tu armario.'],
            ['The Future',    'Estamos construyendo un archivo. Cada colección documenta un momento en el tiempo. Una pieza de OverszClub debería seguir sintiéndose igual de relevante en diez años.'],
        ];
        foreach ($blocks as $b) : ?>
            <div class="about-block reveal">
                <h3><?php echo esc_html($b[0]); ?></h3>
                <p><?php echo esc_html($b[1]); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- CTA final -->
    <div style="background:var(--c-bg2);padding:80px var(--gutter);text-align:center;margin-top:80px;">
        <p style="font-size:9px;letter-spacing:5px;text-transform:uppercase;color:var(--c-accent);margin-bottom:16px;">— La Colección</p>
        <h2 style="margin-bottom:32px;">Shop the Archive</h2>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
            Ver todas las prendas
        </a>
    </div>

</div>

<?php endwhile; get_footer(); ?>
