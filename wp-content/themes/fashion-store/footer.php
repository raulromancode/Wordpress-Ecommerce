<!-- ══ FOOTER ══════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Marca -->
            <div>
                <div class="footer-brand__logo">
                    <?php bloginfo('name'); ?>
                </div>
                <p class="footer-brand__desc">
                    Moda de calidad con diseño contemporáneo.<br>
                    Proyecto académico — sin uso comercial.
                </p>
            </div>

            <!-- Tienda -->
            <div class="footer-col">
                <p class="footer-col__title">Tienda</p>
                <ul>
                    <?php
                    $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'number'=>5]);
                    if (!is_wp_error($cats)) foreach ($cats as $cat) :
                    ?>
                        <li><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?php echo wc_get_page_permalink('shop'); ?>">Ver todo</a></li>
                </ul>
            </div>

            <!-- Ayuda -->
            <div class="footer-col">
                <p class="footer-col__title">Ayuda</p>
                <ul>
                    <li><a href="#">Envíos y devoluciones</a></li>
                    <li><a href="#">Guía de tallas</a></li>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>

            <!-- Redes -->
            <div class="footer-col">
                <p class="footer-col__title">Síguenos</p>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">Pinterest</a></li>
                    <li><a href="#">Newsletter</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> &mdash; Proyecto académico</span>
            <span>Hecho con WordPress + WooCommerce</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
