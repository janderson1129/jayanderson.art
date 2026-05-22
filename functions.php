<?php
/**
 * Jay Anderson Art — Theme Functions
 *
 * @package jay-anderson-art
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   CONSTANTS
   ============================================================ */
define( 'JAY_VERSION',   '1.0.0' );
define( 'JAY_DIR',       get_template_directory() );
define( 'JAY_URI',       get_template_directory_uri() );
define( 'JAY_ASSETS',    JAY_URI . '/assets' );

/* ============================================================
   THEME SETUP
   ============================================================ */
function jay_setup() {

    /* Translations */
    load_theme_textdomain( 'jay-anderson-art', JAY_DIR . '/languages' );

    /* HTML5 markup */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ) );

    /* Title tag managed by WordPress */
    add_theme_support( 'title-tag' );

    /* Post thumbnails */
    add_theme_support( 'post-thumbnails' );

    /* Custom logo */
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    /* Automatic feed links */
    add_theme_support( 'automatic-feed-links' );

    /* Block editor — wide & full alignment */
    add_theme_support( 'align-wide' );

    /* Block editor — responsive embeds */
    add_theme_support( 'responsive-embeds' );

    /* WooCommerce */
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    /* Register nav menus */
    register_nav_menus( array(
        'primary'  => __( 'Primary Navigation', 'jay-anderson-art' ),
        'footer'   => __( 'Footer Navigation',  'jay-anderson-art' ),
    ) );
}
add_action( 'after_setup_theme', 'jay_setup' );






/* ============================================================
   EXCLUDE PRINTS FROM ALL WORK AND AVAILABLE WORK TABS
   ============================================================ */
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    $queried = get_queried_object();
    if ( $queried instanceof WP_Term && $queried->slug === 'original-work' ) {
        /* Get all product IDs in print categories */
        $print_ids = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array( 'print', 'limited-edition', 'open-edition' ),
                    'operator' => 'IN',
                ),
            ),
        ) );
        if ( ! empty( $print_ids ) ) {
            $query->set( 'post__not_in', $print_ids );
        }
    }
} );

/* ============================================================
   CONTENT WIDTH
   ============================================================ */
function jay_content_width() {
    $GLOBALS['content_width'] = 1400;
}
add_action( 'after_setup_theme', 'jay_content_width', 0 );

/* ============================================================
   IMAGE SIZES
   ============================================================ */
function jay_image_sizes() {
    /* Hero — full-width hero image */
    add_image_size( 'jay-hero',      1600, 1000, true );

    /* Portfolio grid — square crop for grid view */
    add_image_size( 'jay-grid',       800,  900, true );

    /* Single product — tall portrait crop */
    add_image_size( 'jay-product',   1000, 1250, false );

    /* Thumbnail — small square for cart/related */
    add_image_size( 'jay-thumb',      400,  400, true );

    /* Wide — landscape for story/about sections */
    add_image_size( 'jay-wide',      1200,  700, true );
}
add_action( 'after_setup_theme', 'jay_image_sizes' );

/* Add custom sizes to media library picker */
function jay_add_image_sizes_to_admin( $sizes ) {
    return array_merge( $sizes, array(
        'jay-hero'    => __( 'Hero',          'jay-anderson-art' ),
        'jay-grid'    => __( 'Portfolio Grid','jay-anderson-art' ),
        'jay-product' => __( 'Product',       'jay-anderson-art' ),
        'jay-thumb'   => __( 'Thumbnail',     'jay-anderson-art' ),
        'jay-wide'    => __( 'Wide',          'jay-anderson-art' ),
    ) );
}
add_filter( 'image_size_names_choose', 'jay_add_image_sizes_to_admin' );

/* ============================================================
   ENQUEUE STYLES & SCRIPTS
   ============================================================ */
function jay_enqueue_assets() {

    /* Google Fonts — Cormorant Garamond + DM Sans */
    wp_enqueue_style(
        'jay-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap',
        array(),
        null
    );

    /* Theme stylesheet */
    wp_enqueue_style(
        'jay-style',
        get_stylesheet_uri(),
        array( 'jay-fonts' ),
        JAY_VERSION
    );

    /* Theme JavaScript */
    wp_enqueue_script(
        'jay-main',
        JAY_ASSETS . '/js/main.js',
        array(),
        JAY_VERSION,
        true /* load in footer */
    );

    /* Pass data to JS */
    wp_localize_script( 'jay-main', 'jayData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'jay_nonce' ),
        'cartUrl' => wc_get_cart_url(),
    ) );

    /* Homepage styles */
    if ( is_front_page() ) {
        wp_enqueue_style(
            'jay-front-page',
            JAY_ASSETS . '/css/front-page.css',
            array( 'jay-style' ),
            JAY_VERSION
        );
    }

    /* Product archive styles — shop, category pages */
    if ( is_shop() || is_product_category() ) {
        wp_enqueue_style(
            'jay-archive-product',
            JAY_ASSETS . '/css/archive-product.css',
            array( 'jay-style' ),
            JAY_VERSION
        );
    }

    /* About & Contact page styles */
    if ( is_page() || is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
        wp_enqueue_style(
            'jay-pages',
            JAY_ASSETS . '/css/pages.css',
            array( 'jay-style' ),
            JAY_VERSION
        );
    }

    /* Single product page styles & JS */
    if ( is_singular( 'product' ) ) {
        wp_enqueue_style(
            'jay-single-product',
            JAY_ASSETS . '/css/single-product.css',
            array( 'jay-style' ),
            JAY_VERSION
        );
        wp_enqueue_script(
            'jay-product',
            JAY_ASSETS . '/js/product.js',
            array(),
            JAY_VERSION,
            true
        );
    }




    /* WooCommerce pages: enqueue shop styles */
    if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
        wp_enqueue_style(
            'jay-woocommerce',
            JAY_ASSETS . '/css/woocommerce.css',
            array( 'jay-style' ),
            JAY_VERSION
        );
    }

    /* Comment reply script */
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'jay_enqueue_assets' );

/* Preconnect to Google Fonts for performance */
function jay_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'jay_resource_hints', 10, 2 );



/* ============================================================
   AVAILABLE WORK — STOCK FILTER
   Excludes out of stock products when filter_stock=instock
   ============================================================ */
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( isset( $_GET['filter_stock'] ) && $_GET['filter_stock'] === 'instock' ) {
        $meta_query   = $query->get( 'meta_query' ) ?: array();
        $meta_query[] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        );
        $query->set( 'meta_query', $meta_query );
    }
} );

/* ============================================================
   WIDGETS / SIDEBARS
   ============================================================ */
function jay_widgets_init() {

    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'jay-anderson-art' ),
        'id'            => 'shop-sidebar',
        'description'   => __( 'Widgets in the WooCommerce shop sidebar.', 'jay-anderson-art' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Column 3', 'jay-anderson-art' ),
        'id'            => 'footer-3',
        'description'   => __( 'Optional footer column 3 widgets.', 'jay-anderson-art' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-col__title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'jay_widgets_init' );

/* ============================================================
   WOOCOMMERCE CUSTOMISATION
   ============================================================ */

/* Remove default WooCommerce sidebar on shop/product pages */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/* Remove default WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* Remove default WooCommerce page title on archive (we render our own) */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/* Change number of products per row */
function jay_woo_products_per_row( $columns ) {
    return 3;
}
add_filter( 'loop_shop_columns', 'jay_woo_products_per_row' );

/* Number of products per page */
function jay_woo_products_per_page( $cols ) {
    return 12;
}
add_filter( 'loop_shop_per_page', 'jay_woo_products_per_page' );

/* Related products — show 3 */
function jay_woo_related_products( $args ) {
    $args['posts_per_page'] = 3;
    $args['columns']        = 3;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jay_woo_related_products' );

/* Move product thumbnails below the summary on single product */
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

/* Custom "Add to Cart" text for original artworks */
function jay_woo_add_to_cart_text( $text, $product ) {
    if ( $product->is_type( 'simple' ) ) {
        $categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
        if ( in_array( 'original-work', $categories, true ) ) {
            return __( 'Acquire This Piece', 'jay-anderson-art' );
        }
    }
    return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text',        'jay_woo_add_to_cart_text', 10, 2 );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'jay_woo_add_to_cart_text', 10, 2 );

/* Add custom product field: Medium */
function jay_add_product_fields() {
    echo '<div class="options_group">';

    woocommerce_wp_text_input( array(
        'id'          => '_artwork_medium',
        'label'       => __( 'Medium', 'jay-anderson-art' ),
        'placeholder' => __( 'e.g. Graphite on cradled panel', 'jay-anderson-art' ),
        'desc_tip'    => true,
        'description' => __( 'The materials and technique used for this piece.', 'jay-anderson-art' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_artwork_dimensions',
        'label'       => __( 'Dimensions', 'jay-anderson-art' ),
        'placeholder' => __( 'e.g. 16 × 20 inches', 'jay-anderson-art' ),
        'desc_tip'    => true,
        'description' => __( 'Width × height of the artwork.', 'jay-anderson-art' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_artwork_year',
        'label'       => __( 'Year', 'jay-anderson-art' ),
        'placeholder' => __( 'e.g. 2024', 'jay-anderson-art' ),
        'desc_tip'    => true,
        'description' => __( 'Year the piece was created.', 'jay-anderson-art' ),
    ) );

    woocommerce_wp_checkbox( array(
        'id'          => '_artwork_ready_to_hang',
        'label'       => __( 'Ready to hang (no glass required)', 'jay-anderson-art' ),
        'description' => __( 'Check if this piece is ready to hang without framing or glass.', 'jay-anderson-art' ),
    ) );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'jay_add_product_fields' );

/* Save custom product fields */
function jay_save_product_fields( $post_id ) {
    $text_fields = array( '_artwork_medium', '_artwork_dimensions', '_artwork_year' );

    foreach ( $text_fields as $field ) {
        $value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
        update_post_meta( $post_id, $field, $value );
    }

    $ready = isset( $_POST['_artwork_ready_to_hang'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_artwork_ready_to_hang', $ready );
}
add_action( 'woocommerce_process_product_meta', 'jay_save_product_fields' );

/* ============================================================
   HELPER FUNCTIONS
   ============================================================ */

/**
 * Get artwork meta for a product.
 *
 * @param  int $product_id  WooCommerce product ID.
 * @return array            Associative array of artwork meta fields.
 */
function jay_get_artwork_meta( $product_id ) {
    return array(
        'medium'         => get_post_meta( $product_id, '_artwork_medium',      true ),
        'dimensions'     => get_post_meta( $product_id, '_artwork_dimensions',  true ),
        'year'           => get_post_meta( $product_id, '_artwork_year',        true ),
        'ready_to_hang'  => get_post_meta( $product_id, '_artwork_ready_to_hang', true ) === 'yes',
    );
}

/**
 * Output the site logo — custom logo if set, otherwise text fallback.
 */
function jay_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
        return;
    }
    $site_name = get_bloginfo( 'name' );
    $parts     = explode( ' ', $site_name, 2 );
    $first     = esc_html( $parts[0] ?? $site_name );
    $rest      = isset( $parts[1] ) ? ' <em>' . esc_html( $parts[1] ) . '</em>' : '';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-logo" rel="home">' . $first . $rest . '</a>';
}

/**
 * Check if current page is any WooCommerce page.
 *
 * @return bool
 */
function jay_is_woo_page() {
    return function_exists( 'is_woocommerce' ) && (
        is_woocommerce() || is_cart() || is_checkout() || is_account_page()
    );
}

/**
 * Output formatted product price with label.
 *
 * @param WC_Product $product
 */
function jay_product_price( $product ) {
    echo '<span class="product-price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
}

/**
 * Output availability badge for a product.
 *
 * @param WC_Product $product
 */
function jay_availability_badge( $product ) {
    if ( ! $product->is_in_stock() ) {
        echo '<span class="badge badge--sold">' . esc_html__( 'Sold', 'jay-anderson-art' ) . '</span>';
    }
}

/* ============================================================
   PRINT EDITION TEMPLATE ROUTING
   Routes print products to the correct single template
   based on the WooCommerce product category.
   ============================================================ */
add_filter( 'template_include', function( $template ) {
    if ( is_singular( 'product' ) ) {
        $post_id = get_queried_object_id();
        if ( has_term( 'limited-edition', 'product_cat', $post_id ) ) {
            $custom = locate_template( 'single-product-limited-edition.php' );
            return $custom ?: $template;
        }
        if ( has_term( 'open-edition', 'product_cat', $post_id ) ) {
            $custom = locate_template( 'single-product-open-edition.php' );
            return $custom ?: $template;
        }
    }
    return $template;
}, 99 );

/* ============================================================
   BODY CLASSES
   ============================================================ */
function jay_body_classes( $classes ) {
    if ( jay_is_woo_page() ) {
        $classes[] = 'is-woo-page';
    }
    if ( is_singular( 'product' ) ) {
        $categories = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) );
        if ( in_array( 'original-work', $categories, true ) ) {
            $classes[] = 'is-original-work';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'jay_body_classes' );

/* ============================================================
   DOCUMENT TITLE SEPARATOR
   ============================================================ */
add_filter( 'document_title_separator', function() { return '·'; } );

/* ============================================================
   EXCERPT LENGTH
   ============================================================ */
function jay_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'jay_excerpt_length' );

function jay_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'jay_excerpt_more' );

/* ============================================================
   REMOVE EMOJI SCRIPTS (performance)
   ============================================================ */
remove_action( 'wp_head',             'print_emoji_detection_script', 7  );
remove_action( 'wp_print_styles',     'print_emoji_styles'               );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script'     );
remove_action( 'admin_print_styles',  'print_emoji_styles'               );

/* ============================================================
   SECURITY — REMOVE GENERATOR TAG
   ============================================================ */
remove_action( 'wp_head', 'wp_generator' );

/* ============================================================
   NAV BUG FIX — remove any menu items with empty/duplicate titles
   This cleans up the "jayanderson.art jayanderson.art" issue
   caused by a logo link appearing twice in the nav menu.
   ============================================================ */
function jay_clean_nav_items( $items, $args ) {
    if ( 'primary' !== $args->theme_location ) {
        return $items;
    }
    foreach ( $items as $key => $item ) {
        /* Remove items with blank titles or titles that are just the site URL */
        $title = trim( $item->title );
        $home  = trim( home_url( '/' ) );
        if ( empty( $title ) || $title === $home || strpos( $title, get_bloginfo( 'url' ) ) !== false ) {
            unset( $items[ $key ] );
        }
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'jay_clean_nav_items', 10, 2 );

/* ============================================================
   WOOCOMMERCE — ensure generic page template CSS loads
   on WooCommerce shortcode pages (cart, checkout, account)
   ============================================================ */
function jay_woo_body_class( $classes ) {
    if ( is_cart() )     $classes[] = 'woocommerce-cart';
    if ( is_checkout() ) $classes[] = 'woocommerce-checkout';
    return $classes;
}
add_filter( 'body_class', 'jay_woo_body_class' );

/* ============================================================
   UNNAMED PRODUCT FIX
   If a product has no title, display a placeholder instead
   of leaving a blank heading in the shop grid.
   ============================================================ */
function jay_product_title_fallback( $title, $id ) {
    if ( get_post_type( $id ) === 'product' && empty( trim( $title ) ) ) {
        return __( 'Untitled Work', 'jay-anderson-art' );
    }
    return $title;
}
add_filter( 'the_title', 'jay_product_title_fallback', 10, 2 );
