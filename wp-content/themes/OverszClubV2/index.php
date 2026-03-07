<?php
// Fallback — WordPress lo require
get_header();
if (have_posts()) : while (have_posts()) : the_post();
    echo '<article class="wrap mt-nav" style="padding-top:calc(var(--nav-h)+48px);padding-bottom:80px;">';
    the_title('<h1 style="margin-bottom:24px;">','</h1>');
    the_content();
    echo '</article>';
endwhile; endif;
get_footer();
