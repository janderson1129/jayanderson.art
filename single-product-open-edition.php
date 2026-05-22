<?php
/**
 * WooCommerce Single Product Template — Open Edition Print
 *
 * Layout:
 *  1. Breadcrumb nav
 *  2. Product hero   — large image left, details right
 *       - Title, open edition badge, price
 *       - Artist story / description
 *       - Specs table
 *       - Add to cart state
 *       - Shipping & archival note
 *  3. Gallery        — additional product images
 *  4. Related works  — 3-up grid from same category
 *  5. Commission CTA — bottom strip
 *
 * @package jay-anderson-art
 */

get_header();

while ( have_posts() ) :
    the_post();

    global $product;
    if ( ! $product ) {
        $product = wc_get_product( get_the_ID() );
    }

    /* --------------------------------------------------------
       PRODUCT DATA
    -------------------------------------------------------- */
    $product_id     = $product->get_id();
    $artwork        = jay_get_artwork_meta( $product_id );
    $in_stock       = $product->is_in_stock();
    $is_original    = has_term( 'original-work', 'product_cat', $product_id );
    $is_print       = has_term( 'print',          'product_cat', $product_id );

    /* Images */
    $main_img_id    = $product->get_image_id();
    $main_img_url   = $main_img_id
        ? wp_get_attachment_image_url( $main_img_id, 'jay-product' )
        : wc_placeholder_img_src( 'jay-product' );
    $main_img_full  = $main_img_id
        ? wp_get_attachment_image_url( $main_img_id, 'full' )
        : $main_img_url;

    /* Edition data */
    $edition_type   = get_field( 'edition_type' );

    /* Gallery images */
    $gallery_ids    = $product->get_gallery_image_ids();

    /* Category for breadcrumb & related */
    $categories     = wc_get_product_category_list( $product_id, ', ', '<span>', '</span>' );
    $cat_terms      = wp_get_post_terms( $product_id, 'product_cat' );
    $primary_cat    = ! empty( $cat_terms ) ? $cat_terms[0] : null;
    $cat_url        = $primary_cat ? get_term_link( $primary_cat ) : get_permalink( wc_get_page_id( 'shop' ) );
    $cat_url        = is_wp_error( $cat_url ) ? get_permalink( wc_get_page_id( 'shop' ) ) : $cat_url;
    $cat_label      = $primary_cat ? $primary_cat->name : __( 'Work', 'jay-anderson-art' );

    /* Short description / excerpt for story */
    $story          = $product->get_short_description();
    $full_desc      = $product->get_description();
?>

<?php /* ======================================================
   BREADCRUMB
   ====================================================== */ ?>
<nav class="product-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'jay-anderson-art' ); ?>">
    <div class="container">
        <ol class="product-breadcrumb__list">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'jay-anderson-art' ); ?></a></li>
            <li aria-hidden="true" class="product-breadcrumb__sep">·</li>
            <li><a href="<?php echo esc_url( $cat_url ); ?>"><?php echo esc_html( $cat_label ); ?></a></li>
            <li aria-hidden="true" class="product-breadcrumb__sep">·</li>
            <li aria-current="page"><?php the_title(); ?></li>
        </ol>
    </div>
</nav>


<?php /* ======================================================
   PRODUCT HERO — image + details
   ====================================================== */ ?>
<section class="product-hero">

    <?php /* Left — main image */ ?>
    <div class="product-hero__image-col">

        <div class="product-hero__image-wrap" id="product-main-image">
            <?php if ( $main_img_url ) : ?>
                <a
                    href="<?php echo esc_url( $main_img_full ); ?>"
                    class="product-hero__zoom-link"
                    aria-label="<?php esc_attr_e( 'View full size image', 'jay-anderson-art' ); ?>"
                    data-zoom
                >
                    <img
                        src="<?php echo esc_url( $main_img_url ); ?>"
                        alt="<?php echo esc_attr( get_the_title() ); ?>"
                        class="product-hero__image"
                        loading="eager"
                        id="product-main-img"
                    >
                    <span class="product-hero__zoom-hint" aria-hidden="true">
                        <?php esc_html_e( 'Click to enlarge', 'jay-anderson-art' ); ?>
                    </span>
                </a>
            <?php else : ?>
                <div class="product-hero__image product-hero__image--placeholder"></div>
            <?php endif; ?>

            <?php if ( ! $in_stock ) : ?>
                <span class="badge badge--sold badge--large"><?php esc_html_e( 'Sold', 'jay-anderson-art' ); ?></span>
            <?php endif; ?>
        </div>

        <?php /* Thumbnail strip — if gallery images exist */ ?>
        <?php if ( ! empty( $gallery_ids ) ) : ?>
        <div class="product-thumbnails" role="list" aria-label="<?php esc_attr_e( 'Product image gallery', 'jay-anderson-art' ); ?>">

            <?php /* Main image thumb */ ?>
            <button
                class="product-thumb product-thumb--active"
                data-full="<?php echo esc_url( $main_img_full ); ?>"
                data-src="<?php echo esc_url( $main_img_url ); ?>"
                data-alt="<?php echo esc_attr( get_the_title() ); ?>"
                aria-label="<?php esc_attr_e( 'View main image', 'jay-anderson-art' ); ?>"
                role="listitem"
            >
                <img
                    src="<?php echo esc_url( wp_get_attachment_image_url( $main_img_id, 'jay-thumb' ) ); ?>"
                    alt=""
                    loading="lazy"
                >
            </button>

            <?php foreach ( $gallery_ids as $gal_id ) :
                $gal_src  = wp_get_attachment_image_url( $gal_id, 'jay-product' );
                $gal_full = wp_get_attachment_image_url( $gal_id, 'full' );
                $gal_alt  = get_post_meta( $gal_id, '_wp_attachment_image_alt', true );
            ?>
            <button
                class="product-thumb"
                data-full="<?php echo esc_url( $gal_full ); ?>"
                data-src="<?php echo esc_url( $gal_src ); ?>"
                data-alt="<?php echo esc_attr( $gal_alt ?: get_the_title() ); ?>"
                aria-label="<?php esc_attr_e( 'View gallery image', 'jay-anderson-art' ); ?>"
                role="listitem"
            >
                <img
                    src="<?php echo esc_url( wp_get_attachment_image_url( $gal_id, 'jay-thumb' ) ); ?>"
                    alt=""
                    loading="lazy"
                >
            </button>
            <?php endforeach; ?>

        </div><!-- .product-thumbnails -->
        <?php endif; ?>

    </div><!-- .product-hero__image-col -->


    <?php /* Right — product details */ ?>
    <div class="product-hero__details">

        <?php /* Category label */ ?>
        <span class="eyebrow"><?php echo esc_html( $cat_label ); ?></span>

        <?php /* Title */ ?>
        <h1 class="product-hero__title"><?php the_title(); ?></h1>

        <?php /* Open edition badge */ ?>
        <div class="product-edition-badge product-edition-badge--open">
            <span class="product-edition-badge__label">
                <?php esc_html_e( 'Open Edition', 'jay-anderson-art' ); ?>
            </span>
        </div>

        <?php /* Year if set */ ?>
        <?php if ( $artwork['year'] ) : ?>
            <p class="product-hero__year"><?php echo esc_html( $artwork['year'] ); ?></p>
        <?php endif; ?>

        <?php /* Price */ ?>
        <div class="product-hero__price">
            <?php echo wp_kses_post( $product->get_price_html() ); ?>
        </div>

        <?php /* Story — short description */ ?>
        <?php if ( $story ) : ?>
        <div class="product-hero__story">
            <?php echo wp_kses_post( $story ); ?>
        </div>
        <?php endif; ?>

        <?php /* Specs table */ ?>
        <?php
        $specs = array_filter( array(
            __( 'Medium',     'jay-anderson-art' ) => $artwork['medium'],
            __( 'Dimensions', 'jay-anderson-art' ) => $artwork['dimensions'],
            __( 'Year',       'jay-anderson-art' ) => $artwork['year'],
            __( 'Edition',    'jay-anderson-art' ) => __( 'Open edition print', 'jay-anderson-art' ),
            __( 'Print type', 'jay-anderson-art' ) => __( 'Archival giclée', 'jay-anderson-art' ),
            __( 'Materials',  'jay-anderson-art' ) => __( 'Archival, museum-grade paper', 'jay-anderson-art' ),
        ) );
        if ( $specs ) :
        ?>
        <dl class="product-specs">
            <?php foreach ( $specs as $label => $value ) : ?>
                <div class="product-spec">
                    <dt class="product-spec__label"><?php echo esc_html( $label ); ?></dt>
                    <dd class="product-spec__value"><?php echo esc_html( $value ); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
        <?php endif; ?>

        <?php /* Add to cart / sold */ ?>
        <div class="product-hero__purchase">
            <?php if ( $in_stock ) : ?>
                <?php
                woocommerce_template_single_add_to_cart();
                ?>
            <?php else : ?>
                <div class="product-sold-state">
                    <span class="product-sold-state__label">
                        <?php esc_html_e( 'This piece has found its home.', 'jay-anderson-art' ); ?>
                    </span>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--outline">
                        <?php esc_html_e( 'Inquire about similar works', 'jay-anderson-art' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php /* Archival / shipping reassurance */ ?>
        <ul class="product-assurances">
            <li class="product-assurance">
                <span class="product-assurance__icon" aria-hidden="true">✦</span>
                <?php esc_html_e( 'Archival giclée print on museum-grade paper', 'jay-anderson-art' ); ?>
            </li>
            <li class="product-assurance">
                <span class="product-assurance__icon" aria-hidden="true">✦</span>
                <?php esc_html_e( 'Professionally packaged and insured shipping', 'jay-anderson-art' ); ?>
            </li>
            <li class="product-assurance">
                <span class="product-assurance__icon" aria-hidden="true">✦</span>
                <?php esc_html_e( 'Questions? Email jay@jayanderson.art', 'jay-anderson-art' ); ?>
            </li>
        </ul>

    </div><!-- .product-hero__details -->

</section><!-- .product-hero -->


<?php /* ======================================================
   FULL DESCRIPTION — if longer story exists
   ====================================================== */ ?>
<?php if ( $full_desc ) : ?>
<section class="product-description section section--sm">
    <div class="container container--text">
        <span class="eyebrow"><?php esc_html_e( 'About this piece', 'jay-anderson-art' ); ?></span>
        <div class="rule"></div>
        <div class="product-description__body">
            <?php echo wp_kses_post( $full_desc ); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php /* ======================================================
   RELATED WORKS
   ====================================================== */ ?>
<?php
$related_ids = wc_get_related_products( $product_id, 3 );
if ( ! empty( $related_ids ) ) :
    $related_query = new WP_Query( array(
        'post_type'      => 'product',
        'post__in'       => $related_ids,
        'posts_per_page' => 3,
        'orderby'        => 'post__in',
    ) );
?>
<section class="related-section section section--sm">
    <div class="container">

        <div class="section-header">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Continue exploring', 'jay-anderson-art' ); ?></span>
                <h2 class="section-header__title">
                    <?php esc_html_e( 'Related', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'Works', 'jay-anderson-art' ); ?></em>
                </h2>
            </div>
            <a href="<?php echo esc_url( $cat_url ); ?>" class="section-header__link">
                <?php esc_html_e( 'View all', 'jay-anderson-art' ); ?>
            </a>
        </div>

        <div class="related-grid">
            <?php while ( $related_query->have_posts() ) : $related_query->the_post();
                $rel_product  = wc_get_product( get_the_ID() );
                $rel_img_id   = $rel_product->get_image_id();
                $rel_img_url  = $rel_img_id ? wp_get_attachment_image_url( $rel_img_id, 'jay-grid' ) : '';
                $rel_artwork  = jay_get_artwork_meta( get_the_ID() );
                $rel_in_stock = $rel_product->is_in_stock();
            ?>
            <article class="related-item" data-animate>
                <a href="<?php the_permalink(); ?>" class="related-item__link">

                    <div class="related-item__image-wrap">
                        <?php if ( $rel_img_url ) : ?>
                            <img
                                src="<?php echo esc_url( $rel_img_url ); ?>"
                                alt="<?php echo esc_attr( get_the_title() ); ?>"
                                class="related-item__image"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <div class="related-item__image related-item__image--placeholder"></div>
                        <?php endif; ?>
                        <?php if ( ! $rel_in_stock ) : ?>
                            <span class="badge badge--sold"><?php esc_html_e( 'Sold', 'jay-anderson-art' ); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="related-item__info">
                        <h3 class="related-item__title"><?php the_title(); ?></h3>
                        <?php if ( $rel_artwork['medium'] ) : ?>
                            <p class="related-item__meta"><?php echo esc_html( $rel_artwork['medium'] ); ?></p>
                        <?php endif; ?>
                        <p class="related-item__price"><?php echo wp_kses_post( $rel_product->get_price_html() ); ?></p>
                    </div>

                </a>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
<?php endif; ?>


<?php /* ======================================================
   COMMISSION CTA — bottom strip
   ====================================================== */ ?>
<section class="product-commission-cta">
    <div class="container">
        <div class="product-commission-cta__inner" data-animate>
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Something personal', 'jay-anderson-art' ); ?></span>
                <h2 class="product-commission-cta__title">
                    <?php esc_html_e( 'Commission a', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'portrait', 'jay-anderson-art' ); ?></em>
                </h2>
                <p class="product-commission-cta__desc">
                    <?php esc_html_e( 'A limited number of commissions are accepted each year. Graphite, oil, or mixed media — your vision, Jay\'s hand.', 'jay-anderson-art' ); ?>
                </p>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Start a conversation', 'jay-anderson-art' ); ?>
            </a>
        </div>
    </div>
</section>

<?php /* Lightbox overlay for full-size image zoom */ ?>
<div class="product-lightbox" id="product-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Image lightbox', 'jay-anderson-art' ); ?>" hidden>
    <button class="product-lightbox__close" aria-label="<?php esc_attr_e( 'Close lightbox', 'jay-anderson-art' ); ?>">✕</button>
    <img src="" alt="" class="product-lightbox__image" id="lightbox-img">
</div>
<div class="product-lightbox-backdrop" id="lightbox-backdrop" hidden></div>

<?php endwhile; ?>

<?php get_footer(); ?>
