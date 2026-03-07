<!-- ══ FOOTER ══════════════════════════════════════════════════ -->
<!--
    Textos y redes sociales editables desde:
    Apariencia → Personalizar → Street Editorial Theme → Footer / Redes sociales
-->
<footer class="site-footer" role="contentinfo">
    <div class="wrap">
        <div class="footer-grid">

            <!-- Marca -->
            <div>
                <div class="footer-logo">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <?php echo esc_html( get_theme_mod( 'se_logo_text', 'STREET—EDITORIAL' ) ); ?>
                    <?php endif; ?>
                </div>
                <p class="footer-desc footer-tagline">
                    <?php echo esc_html( get_theme_mod( 'se_footer_tagline', 'Ropa streetwear con identidad editorial.' ) ); ?>
                </p>

                <!-- Redes sociales -->
                <?php if ( function_exists( 'se_has_social' ) && se_has_social() ) : ?>
                <div class="footer-social">
                    <?php
                    $social_icons = [
                        'instagram' => 'Instagram',
                        'tiktok'    => 'TikTok',
                        'twitter'   => 'X',
                        'facebook'  => 'Facebook',
                        'youtube'   => 'YouTube',
                        'pinterest' => 'Pinterest',
                    ];
                    foreach ( $social_icons as $net => $label ) :
                        $url = se_social_url( $net );
                        if ( $url ) : ?>
                            <a href="<?php echo $url; ?>"
                               class="footer-social__link"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="<?php echo esc_attr( $label ); ?>">
                                <?php echo esc_html( $label ); ?>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </div>
                <?php endif; ?>
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
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new SE_Walker(),
                        'depth'          => 1,
                        'fallback_cb'    => function() {
                            $links = [
                                '#' => 'Shipping & Returns',
                                '#' => 'Size Guide',
                                '#' => 'Privacy Policy',
                                '#' => 'Contact',
                            ];
                            foreach ( $links as $href => $label ) {
                                echo '<li><a href="' . esc_url( $href ) . '">' . esc_html( $label ) . '</a></li>';
                            }
                        },
                    ] );
                    ?>
                </ul>
            </div>

            <!-- Social (columna adicional en footer) -->
            <div class="footer-col">
                <p class="footer-col-title">Follow</p>
                <ul>
                    <?php
                    $social_list = [
                        'instagram' => 'Instagram',
                        'tiktok'    => 'TikTok',
                        'twitter'   => 'X / Twitter',
                        'facebook'  => 'Facebook',
                        'youtube'   => 'YouTube',
                        'pinterest' => 'Pinterest',
                    ];
                    $has_any = false;
                    foreach ( $social_list as $net => $label ) {
                        $url = se_social_url( $net );
                        if ( $url ) {
                            $has_any = true;
                            echo '<li><a href="' . $url . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a></li>';
                        }
                    }
                    if ( ! $has_any ) {
                        // Placeholder si no hay redes configuradas
                        echo '<li style="color:var(--dim);font-size:11px;letter-spacing:2px;">Configura tus redes en<br>Apariencia → Personalizar</li>';
                    }
                    ?>
                </ul>
            </div>

        </div><!-- /footer-grid -->

        <div class="footer-bottom">
            <span class="footer-copy">
                <?php echo esc_html( get_theme_mod( 'se_footer_copyright', '© ' . date( 'Y' ) . ' Street Editorial. All rights reserved.' ) ); ?>
            </span>
            <span>WordPress + WooCommerce</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
