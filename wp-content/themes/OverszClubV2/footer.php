<!-- ══ FOOTER ══════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-grid">

        <!-- Marca -->
        <div class="footer-brand">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-brand__logo">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <?php echo esc_html( get_theme_mod('oc_logo_text','OVERSZCLUB') ); ?>
                <?php endif; ?>
            </a>
            <p class="footer-tagline">
                <?php echo esc_html( get_theme_mod('oc_footer_tagline','Streetwear con identidad editorial.') ); ?>
            </p>

            <?php if ( oc_has_social() ) : ?>
            <div class="footer-social">
                <?php
                $nets = ['instagram'=>'IG','tiktok'=>'TK','twitter'=>'X','facebook'=>'FB','youtube'=>'YT','pinterest'=>'PT'];
                foreach ($nets as $net => $label) :
                    $url = oc_social($net);
                    if ($url) :
                ?>
                    <a href="<?php echo $url; ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr($net); ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endif; endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tienda -->
        <div class="footer-col">
            <h4>Tienda</h4>
            <ul>
                <?php
                $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'number'=>6]);
                if (!is_wp_error($cats)) {
                    foreach ($cats as $cat) {
                        echo '<li><a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
                    }
                }
                ?>
                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Ver todo</a></li>
            </ul>
        </div>

        <!-- Info -->
        <div class="footer-col">
            <h4>Información</h4>
            <ul>
                <?php wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => new OC_Walker(),
                    'depth'          => 1,
                    'fallback_cb'    => function() {
                        $links = ['Envíos y devoluciones'=>'#','Guía de tallas'=>'#','Política de privacidad'=>'#','Términos de uso'=>'#','Contacto'=>'#'];
                        foreach ($links as $label => $href) echo '<li><a href="' . esc_url($href) . '">' . esc_html($label) . '</a></li>';
                    },
                ]); ?>
            </ul>
        </div>

        <!-- Contacto -->
        <div class="footer-col">
            <h4>Contacto</h4>
            <ul>
                <li><a href="mailto:hola@overszclub.com">hola@overszclub.com</a></li>
                <li><a href="#">WhatsApp</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p><?php echo esc_html( get_theme_mod('oc_footer_copyright', '© ' . date('Y') . ' OverszClub. All rights reserved.') ); ?></p>
        <p>WordPress + WooCommerce</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
