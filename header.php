<?php
/**
 * Site Header
 *
 * @package jay-anderson-art
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="sr-only" href="#main-content"><?php esc_html_e( 'Skip to content', 'jay-anderson-art' ); ?></a>

<header class="site-header" id="site-header" role="banner">
    <div class="site-header__inner">

        <?php jay_logo(); ?>

        <nav class="primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'jay-anderson-art' ); ?>">

            <?php
            wp_nav_menu( array(
                'theme_location'  => 'primary',
                'menu_class'      => 'primary-nav__list',
                'container'       => false,
                'depth'           => 2,
                'fallback_cb'     => 'jay_fallback_nav',
            ) );
            ?>

        </nav>

        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="nav-cta">
            <?php esc_html_e( 'View Originals', 'jay-anderson-art' ); ?>
        </a>

        <button
            class="menu-toggle"
            aria-controls="mobile-nav"
            aria-expanded="false"
            aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'jay-anderson-art' ); ?>"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</header>

<nav class="mobile-nav" id="mobile-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'jay-anderson-art' ); ?>" aria-hidden="true">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => 'mobile-nav__list',
        'container'      => false,
        'depth'          => 1,
        'fallback_cb'    => false,
    ) );
    ?>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn--primary" style="margin-top: auto;">
        <?php esc_html_e( 'View Originals', 'jay-anderson-art' ); ?>
    </a>
</nav>

<div class="mobile-nav-overlay" id="mobile-nav-overlay" aria-hidden="true"></div>

<main class="site-main" id="main-content" role="main">
<?php
