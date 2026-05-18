<?php
/**
 * Site Footer
 *
 * @package jay-anderson-art
 */
?>

</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">

    <div class="site-footer__main">

        <!-- Brand column -->
        <div class="footer-brand">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand__logo">Jay Anderson</a>
            <p class="footer-brand__tagline">
                <?php esc_html_e( 'Contemporary fine art from Royal Oak, Michigan. Original portrait paintings and graphite drawings crafted with archival materials.', 'jay-anderson-art' ); ?>
            </p>
            <div class="footer-social">
                <a href="https://www.instagram.com/jayanderson.art" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e( 'Instagram', 'jay-anderson-art' ); ?>
                </a>
            </div>
        </div>

        <!-- Work column -->
        <div class="footer-col">
            <h4 class="footer-col__title"><?php esc_html_e( 'Work', 'jay-anderson-art' ); ?></h4>
            <?php
            $shop_url   = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
            $orig_url   = get_term_link( 'original-work', 'product_cat' );
            $print_url  = get_term_link( 'print', 'product_cat' );
            ?>
            <ul class="footer-col__list">
                <li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'All Work', 'jay-anderson-art' ); ?></a></li>
                <li>
                    <a href="<?php echo esc_url( is_wp_error( $orig_url ) ? $shop_url : $orig_url ); ?>">
                        <?php esc_html_e( 'Original Paintings', 'jay-anderson-art' ); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url( is_wp_error( $print_url ) ? $shop_url : $print_url ); ?>">
                        <?php esc_html_e( 'Limited Prints', 'jay-anderson-art' ); ?>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Info column -->
        <div class="footer-col">
            <h4 class="footer-col__title"><?php esc_html_e( 'Info', 'jay-anderson-art' ); ?></h4>
            <ul class="footer-col__list">
                <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Jay', 'jay-anderson-art' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'jay-anderson-art' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/commissions/' ) ); ?>"><?php esc_html_e( 'Commissions', 'jay-anderson-art' ); ?></a></li>
            </ul>
        </div>

        <!-- Shop / Account column -->
        <div class="footer-col">
            <h4 class="footer-col__title"><?php esc_html_e( 'Collect', 'jay-anderson-art' ); ?></h4>
            <ul class="footer-col__list">
                <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                            <?php esc_html_e( 'Cart', 'jay-anderson-art' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
                            <?php esc_html_e( 'Checkout', 'jay-anderson-art' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>">
                            <?php esc_html_e( 'My Account', 'jay-anderson-art' ); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">
                        <?php esc_html_e( 'Privacy Policy', 'jay-anderson-art' ); ?>
                    </a>
                </li>
            </ul>
        </div>

    </div><!-- .site-footer__main -->

    <div class="site-footer__bottom">
        <p class="footer-copy">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
            <?php esc_html_e( 'James Anderson · All rights reserved · Royal Oak, Michigan', 'jay-anderson-art' ); ?>
        </p>
        <p class="footer-copy">
            <?php esc_html_e( 'Original art crafted with archival materials', 'jay-anderson-art' ); ?>
        </p>
    </div>

</footer><!-- .site-footer -->

<?php wp_footer(); ?>

</body>
</html>
