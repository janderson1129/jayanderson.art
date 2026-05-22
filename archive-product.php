<?php
/**
 * WooCommerce Product Archive Template
 *
 * Used for:
 *  - /shop/                        (all products)
 *  - /product-category/original-work/   (Originals)
 *  - /product-category/print/           (Prints)
 *
 * Sections:
 *  1. Archive header  — category title, description, product count
 *  2. Filter bar      — sort + category switcher
 *  3. Product grid    — responsive masonry-style grid
 *  4. Pagination
 *
 * @package jay-anderson-art
 */

get_header();

/* --------------------------------------------------------
   CONTEXT — figure out which category we're on
-------------------------------------------------------- */
$current_cat     = get_queried_object();
$is_originals    = ( $current_cat instanceof WP_Term && $current_cat->slug === 'original-work' );
$is_prints       = ( $current_cat instanceof WP_Term && $current_cat->slug === 'print' );
$is_shop         = is_shop();

/* Category URLs */
$shop_url           = get_permalink( wc_get_page_id( 'shop' ) );
$orig_url           = get_term_link( 'original-work', 'product_cat' );
$prints_url         = get_term_link( 'print',         'product_cat' );
$limited_url        = get_term_link( 'limited-edition', 'product_cat' );
$open_url           = get_term_link( 'open-edition',    'product_cat' );
$orig_url           = is_wp_error( $orig_url )    ? $shop_url : $orig_url;
$prints_url         = is_wp_error( $prints_url )  ? $shop_url : $prints_url;
$limited_url        = is_wp_error( $limited_url ) ? $prints_url : $limited_url;
$open_url           = is_wp_error( $open_url )    ? $prints_url : $open_url;

/* Available Work tab URL — originals filtered to in-stock only */
$available_url      = add_query_arg( 'filter_stock', 'instock', $orig_url );
$is_available       = ( $is_originals && isset( $_GET['filter_stock'] ) && $_GET['filter_stock'] === 'instock' );
$is_all_work        = ( $is_originals && ! $is_available );
$is_limited         = ( $current_cat instanceof WP_Term && $current_cat->slug === 'limited-edition' );
$is_open            = ( $current_cat instanceof WP_Term && $current_cat->slug === 'open-edition' );


/* Archive title & description */
if ( $is_available ) {
    $archive_eyebrow = __( 'Ready to collect', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'Available', 'jay-anderson-art' ), __( 'Originals', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Original paintings and drawings available to purchase today. Each piece is unique and crafted with archival materials.', 'jay-anderson-art' );
} elseif ( $is_all_work ) {
    $archive_eyebrow = __( 'Complete portfolio', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'All', 'jay-anderson-art' ), __( 'Originals', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Every original painting and graphite drawing — available and sold — crafted with archival materials in Royal Oak, Michigan.', 'jay-anderson-art' );
} elseif ( $is_limited ) {
    $archive_eyebrow = __( 'Premium prints', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'Limited', 'jay-anderson-art' ), __( 'Edition Prints', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Individually numbered and signed archival giclée prints on museum-quality paper. Each edition is strictly limited.', 'jay-anderson-art' );
} elseif ( $is_open ) {
    $archive_eyebrow = __( 'For every collector', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'Open', 'jay-anderson-art' ), __( 'Edition Prints', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Archival giclée prints on museum-quality paper. A more accessible way to bring Jay\'s work into your space.', 'jay-anderson-art' );
} elseif ( $is_prints ) {
    $archive_eyebrow = __( 'For every collector', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'Limited', 'jay-anderson-art' ), __( 'Edition Prints', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Archival giclée prints on museum-quality paper. A more accessible way to bring Jay\'s work into your space.', 'jay-anderson-art' );
} else {
    $archive_eyebrow = __( 'Complete portfolio', 'jay-anderson-art' );
    $archive_title   = sprintf( '%s <em>%s</em>', __( 'All', 'jay-anderson-art' ), __( 'Works', 'jay-anderson-art' ) );
    $archive_desc    = __( 'Original paintings, graphite drawings, and limited edition prints — all crafted with archival materials in Royal Oak, Michigan.', 'jay-anderson-art' );
}

/* Product count */
$total_products = wc_get_loop_prop( 'total' ) ?: $GLOBALS['woocommerce']->query->get_main_query()->found_posts;
?>

<?php /* ======================================================
   ARCHIVE HEADER
   ====================================================== */ ?>
<section class="archive-header">
    <div class="container2">

        <div class="archive-header__inner">

            <div class="archive-header__text" data-animate>
                <span class="eyebrow"><?php echo esc_html( $archive_eyebrow ); ?></span>
                <h1 class="archive-header__title">
                    <?php echo wp_kses( $archive_title, array( 'em' => array() ) ); ?>
                </h1>
                <?php if ( $archive_desc ) : ?>
                    <p class="archive-header__desc"><?php echo esc_html( $archive_desc ); ?></p>
                <?php endif; ?>
            </div>

            <?php /* Category switcher tabs */ ?>
            <nav class="archive-header__tabs" aria-label="<?php esc_attr_e( 'Browse by category', 'jay-anderson-art' ); ?>">
                <a
                    href="<?php echo esc_url( $available_url ); ?>"
                    class="archive-tab <?php echo $is_available ? 'archive-tab--active' : ''; ?>"
                >
                    <?php esc_html_e( 'Available Originals', 'jay-anderson-art' ); ?>
                </a>
                <a
                    href="<?php echo esc_url( $orig_url ); ?>"
                    class="archive-tab <?php echo $is_all_work ? 'archive-tab--active' : ''; ?>"
                >
                    <?php esc_html_e( 'All Originals', 'jay-anderson-art' ); ?>
                </a>
                <a
                    href="<?php echo esc_url( $limited_url ); ?>"
                    class="archive-tab <?php echo $is_limited ? 'archive-tab--active' : ''; ?>"
                >
                    <?php esc_html_e( 'Limited Prints', 'jay-anderson-art' ); ?>
                </a>
                <a
                    href="<?php echo esc_url( $open_url ); ?>"
                    class="archive-tab <?php echo $is_open ? 'archive-tab--active' : ''; ?>"
                >
                    <?php esc_html_e( 'Open Prints', 'jay-anderson-art' ); ?>
                </a>
            </nav>

        </div>

    </div>
</section><!-- .archive-header -->


<?php /* ======================================================
   FILTER BAR
   ====================================================== */ ?>
<div class="archive-bar">
    <div class="container2">
        <div class="archive-bar__inner">

            <?php if ( $total_products ) : ?>
                <p class="archive-bar__count">
                    <?php
                    printf(
                        /* translators: %d: number of works */
                        esc_html( _n( '%d work', '%d works', $total_products, 'jay-anderson-art' ) ),
                        esc_html( $total_products )
                    );
                    ?>
                </p>
            <?php endif; ?>

            <?php
            /* WooCommerce native sorting dropdown */
            if ( wc_get_loop_prop( 'is_filterable' ) || woocommerce_catalog_ordering() ) :
                woocommerce_catalog_ordering();
            endif;
            ?>

        </div>
    </div>
</div><!-- .archive-bar -->


<?php /* ======================================================
   PRODUCT GRID
   ====================================================== */ ?>
<section class="archive-section section section--sm">
    <div class="container2">

        <?php if ( woocommerce_product_loop() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

            <div class="archive-grid <?php echo $is_prints ? 'archive-grid--prints' : 'archive-grid--originals'; ?>">

                <?php
                $loop_index = 0;
                    while ( have_posts() ) :
                        the_post();
                        $product = wc_get_product( get_the_ID() );
                        if ( ! $product ) continue;

                        /* Skip prints on All Work and Available Work tabs */
                        if ( $is_all_work || $is_available ) {
                            $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) );
                            if ( in_array( 'print', $cats, true ) || in_array( 'limited-edition', $cats, true ) || in_array( 'open-edition', $cats, true ) ) {
                                continue;
                            }
                        }

                        $img_id     = $product->get_image_id();
                        $img_url    = $img_id ? wp_get_attachment_image_url( $img_id, 'jay-grid' ) : '';
                        $img_srcset = $img_id ? wp_get_attachment_image_srcset( $img_id, 'jay-grid' ) : '';
                        $artwork    = jay_get_artwork_meta( get_the_ID() );
                        $in_stock   = $product->is_in_stock();
                        $is_feat    = ( $loop_index === 0 && ! $is_prints );
                        $loop_index++;
                ?>

                <article
                    id="product-<?php the_ID(); ?>"
                    <?php post_class( 'archive-item' . ( $is_feat ? ' archive-item--featured' : '' ) ); ?>
                    data-animate
                    style="--delay: <?php echo esc_attr( min( $loop_index * 0.08, 0.5 ) ); ?>s"
                >
                    <a href="<?php the_permalink(); ?>" class="archive-item__link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">

                        <div class="archive-item__image-wrap">
                            <?php if ( $img_url ) : ?>
                                <img
                                    src="<?php echo esc_url( $img_url ); ?>"
                                    <?php if ( $img_srcset ) echo 'srcset="' . esc_attr( $img_srcset ) . '"'; ?>
                                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                    alt="<?php echo esc_attr( get_the_title() ); ?>"
                                    class="archive-item__image"
                                    loading="<?php echo $loop_index <= 3 ? 'eager' : 'lazy'; ?>"
                                >
                            <?php else : ?>
                                <div class="archive-item__image archive-item__image--placeholder"></div>
                            <?php endif; ?>

                            <?php /* Availability badge */ ?>
                            <?php if ( ! $in_stock ) : ?>
                                <span class="badge badge--sold"><?php esc_html_e( 'Sold', 'jay-anderson-art' ); ?></span>
                            <?php endif; ?>

                            <?php /* Price — visible on hover */ ?>
                            <?php if ( $product->get_price() ) : ?>
                                <span class="archive-item__price">
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                </span>
                            <?php endif; ?>

                            <?php /* Hover overlay */ ?>
                            <div class="archive-item__overlay">
                                <div class="archive-item__overlay-content">
                                    <h2 class="archive-item__title"><?php the_title(); ?></h2>
                                    <?php
                                    $meta_parts = array_filter( array(
                                        $artwork['dimensions'],
                                        $artwork['medium'],
                                    ) );
                                    if ( $meta_parts ) :
                                    ?>
                                        <p class="archive-item__meta">
                                            <?php echo esc_html( implode( ' · ', $meta_parts ) ); ?>
                                        </p>
                                    <?php endif; ?>
                                    <span class="archive-item__cta">
                                        <?php echo $in_stock
                                            ? esc_html__( 'View piece →', 'jay-anderson-art' )
                                            : esc_html__( 'View details →', 'jay-anderson-art' );
                                        ?>
                                    </span>
                                </div>
                            </div>

                        </div><!-- .archive-item__image-wrap -->

                        <?php /* Card info below image — prints only */ ?>
                        <?php if ( $is_prints ) : ?>
                            <div class="archive-item__info">
                                <h2 class="archive-item__info-title"><?php the_title(); ?></h2>
                                <?php if ( $artwork['medium'] || $artwork['dimensions'] ) : ?>
                                    <p class="archive-item__info-meta">
                                        <?php echo esc_html( implode( ' · ', array_filter( array( 'Archival giclée', $artwork['dimensions'] ) ) ) ); ?>
                                    </p>
                                <?php else : ?>
                                    <p class="archive-item__info-meta"><?php esc_html_e( 'Archival giclée', 'jay-anderson-art' ); ?></p>
                                <?php endif; ?>
                                <p class="archive-item__info-price">
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                    </a>
                </article>

                <?php endwhile; ?>

            </div><!-- .archive-grid -->

            <?php woocommerce_product_loop_end(); ?>

            <?php /* Pagination */ ?>
            <div class="archive-pagination">
                <?php woocommerce_pagination(); ?>
            </div>

        <?php else : ?>

         <?php /* Empty state */ ?>
<div class="archive-empty" data-animate>
    <span class="eyebrow"><?php esc_html_e( 'Nothing here now', 'jay-anderson-art' ); ?></span>
    <h2><?php esc_html_e( 'Be the first to know', 'jay-anderson-art' ); ?></h2>
    <p><?php esc_html_e( 'No works in this category are available right now — Sign up to be notified when new pieces drop.', 'jay-anderson-art' ); ?></p>
    <div class="archive-empty__form">
        <?php echo do_shortcode( '[YOUR_SHORTCODE_HERE]' ); ?>
    </div>
    <a href="<?php echo esc_url( $available_url ); ?>" class="btn btn--ghost">
        <?php esc_html_e( 'Browse available originals', 'jay-anderson-art' ); ?>
    </a>
</div>
        <?php endif; ?>

     

    </div>
</section><!-- .archive-section -->


<?php /* ======================================================
   COMMISSION CTA — strip at bottom of originals page
   ====================================================== */ ?>
<?php if ( $is_originals ) : ?>
<section class="commission-cta">
    <div class="container2">
        <div class="commission-cta__inner" data-animate>
            <div class="commission-cta__text">
                <span class="eyebrow"><?php esc_html_e( 'Custom portraits', 'jay-anderson-art' ); ?></span>
                <h2 class="commission-cta__title">
                    <?php esc_html_e( 'Looking for something', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'personal?', 'jay-anderson-art' ); ?></em>
                </h2>
                <p class="commission-cta__desc">
                    <?php esc_html_e( 'Jay accepts a limited number of portrait commissions each year. Get in touch to discuss your vision.', 'jay-anderson-art' ); ?>
                </p>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Inquire about a commission', 'jay-anderson-art' ); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
