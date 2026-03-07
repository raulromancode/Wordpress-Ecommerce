<!-- ══ FOOTER ══════════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
    <div class="wrap">
        <div class="footer-grid">

            <!-- Marca -->
            <div>
                <div class="footer-logo">STREET<span>—</span>EDITORIAL</div>
                <p class="footer-desc">
                    Ropa streetwear con identidad editorial.<br>
                    Proyecto académico — sin uso comercial.
                </p>
            </div>

            <!-- Tienda -->
            <div class="footer-col">
                <p class="footer-col-title">Shop</p>
                <ul>
                    <?php
                    $cats = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 5 ] );
                    if ( ! is_wp_error( $cats ) ) {
                        foreach ( $cats as $cat ) {
                            echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
                        }
                    }
                    ?>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">View All</a></li>
                </ul>
            </div>

            <!-- Info -->
            <div class="footer-col">
                <p class="footer-col-title">Info</p>
                <ul>
                    <li><a href="#">Shipping & Returns</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Social -->
            <div class="footer-col">
                <p class="footer-col-title">Follow</p>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">Pinterest</a></li>
                    <li><a href="#">Twitter / X</a></li>
                </ul>
            </div>

        </div><!-- /footer-grid -->

        <div class="footer-bottom">
            <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Street Editorial — Proyecto académico</span>
            <span>WordPress + WooCommerce</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
