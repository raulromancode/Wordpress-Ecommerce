<?php
// WooCommerce wrapper genérico
get_header();
echo '<div class="wrap mt-nav" style="padding-top:calc(var(--nav-h)+48px);padding-bottom:80px;">';
woocommerce_content();
echo '</div>';
get_footer();
