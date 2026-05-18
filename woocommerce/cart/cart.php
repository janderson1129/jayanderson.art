<?php
/**
 * Cart Page Template Override
 *
 * Wraps WooCommerce's default cart output in theme markup.
 * WooCommerce automatically uses templates in woocommerce/
 * within the active theme over its own defaults.
 *
 * This file handles the outer page structure only.
 * The cart table itself is rendered by WooCommerce core.
 *
 * @package jay-anderson-art
 */

get_header();
?>

<section class="woo-page-header">
    <div class="container">
        <div class="woo-page-header__inner">
            <span class="eyebrow"><?php esc_html_e( 'Your selection', 'jay-anderson-art' ); ?></span>
            <h1 class="woo-page-header__title">
                <?php esc_html_e( 'Cart', 'jay-anderson-art' ); ?>
            </h1>
        </div>
    </div>
</section>

<section class="woo-page-content section section--sm">
    <div class="container">
        <?php woocommerce_content(); ?>
    </div>
</section>

<?php get_footer(); ?>
