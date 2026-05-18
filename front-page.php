<?php
/**
 * Homepage Template (front-page.php)
 *
 * Sections:
 *  1. Hero         — headline, intro, 3-up image stack from featured products
 *  2. Portfolio    — masonry-style grid of Originals category products
 *  3. Story        — featured single piece with full narrative
 *  4. About        — artist bio strip
 *  5. Prints       — 3-up preview of Prints category
 *  6. Contact CTA  — email + commission prompt
 *
 * @package jay-anderson-art
 */

get_header();

/* --------------------------------------------------------
   FETCH PRODUCTS FOR HOMEPAGE
   We query once per category and reuse throughout.
-------------------------------------------------------- */

/* Originals — up to 6 for the portfolio grid */
$originals_query = new WP_Query( array(
    'post_type'      => 'product',
    'posts_per_page' => 6,
    'tax_query'      => array( array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => 'original-work',
    ) ),
    'meta_query' => array( array(
        'key'     => '_visibility',
        'value'   => array( 'catalog', 'visible' ),
        'compare' => 'IN',
    ) ),
    'orderby' => 'menu_order',
    'order'   => 'ASC',
) );

/* Prints — up to 3 for the preview strip */
$prints_query = new WP_Query( array(
    'post_type'      => 'product',
    'posts_per_page' => 3,
    'tax_query'      => array( array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => 'print',
    ) ),
    'orderby' => 'menu_order',
    'order'   => 'ASC',
) );

/* Featured product — first original, used for Story section */
$featured_product = null;
if ( $originals_query->have_posts() ) {
    $originals_query->the_post();
    $featured_product = wc_get_product( get_the_ID() );
    rewind_posts();
    $originals_query->rewind_posts();
}

/* Shop & category URLs */
$shop_url    = get_permalink( wc_get_page_id( 'shop' ) );
$orig_url    = get_term_link( 'original-work', 'product_cat' );
$prints_url  = get_term_link( 'print', 'product_cat' );
$orig_url    = is_wp_error( $orig_url )   ? $shop_url : $orig_url;
$prints_url  = is_wp_error( $prints_url ) ? $shop_url : $prints_url;

/* Count originals for stats */
$orig_count = wp_count_posts( 'product' );
?>

<?php /* ======================================================
   SECTION 1 — HERO
   ====================================================== */ ?>
<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'jay-anderson-art' ); ?>">

    <div class="hero__left">

        <p class="eyebrow animate-fade-up">
            <?php esc_html_e( 'Contemporary Fine Art · Royal Oak, Michigan', 'jay-anderson-art' ); ?>
        </p>

        <h1 class="hero__title animate-fade-up">
            <?php esc_html_e( 'The human', 'jay-anderson-art' ); ?><br>
            <?php esc_html_e( 'face ', 'jay-anderson-art' ); ?><em><?php esc_html_e( 'holds', 'jay-anderson-art' ); ?></em><br>
            <?php esc_html_e( 'everything.', 'jay-anderson-art' ); ?>
        </h1>

        <p class="hero__subtitle animate-fade-up">
            <?php esc_html_e( 'Portraits in graphite, oil & mixed media', 'jay-anderson-art' ); ?>
        </p>

        <p class="hero__desc animate-fade-up">
            <?php esc_html_e( 'Original paintings and drawings exploring emotion, identity, and the fleeting moments that define us. Each piece crafted with archival materials using classical technique.', 'jay-anderson-art' ); ?>
        </p>

        <div class="hero__actions animate-fade-up">
            <a href="<?php echo esc_url( $orig_url ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Explore Originals', 'jay-anderson-art' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn--ghost">
                <?php esc_html_e( 'Meet Jay', 'jay-anderson-art' ); ?>
            </a>
        </div>

        <div class="hero__stats animate-fade-up">
            <?php
            $total_originals = $originals_query->found_posts ?: 10;
            ?>
            <div class="hero__stat">
                <span class="hero__stat-num"><?php echo esc_html( $total_originals ); ?></span>
                <span class="hero__stat-label"><?php esc_html_e( 'Original works', 'jay-anderson-art' ); ?></span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-num">∞</span>
                <span class="hero__stat-label"><?php esc_html_e( 'Limited prints', 'jay-anderson-art' ); ?></span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-num">MI</span>
                <span class="hero__stat-label"><?php esc_html_e( 'Royal Oak', 'jay-anderson-art' ); ?></span>
            </div>
        </div>

    </div><!-- .hero__left -->

    <div class="hero__right" aria-hidden="true">
        <div class="hero__image-stack">

            <?php
            /* Pull first 3 originals for the hero image stack */
            $hero_count = 0;
            if ( $originals_query->have_posts() ) :
                while ( $originals_query->have_posts() && $hero_count < 3 ) :
                    $originals_query->the_post();
                    $product     = wc_get_product( get_the_ID() );
                    $img_id      = $product->get_image_id();
                    $img_url     = $img_id ? wp_get_attachment_image_url( $img_id, 'jay-grid' ) : '';
                    $is_featured = ( $hero_count === 0 );
                    ?>
                    <div class="hero__image-item <?php echo $is_featured ? 'hero__image-item--featured' : ''; ?>">
                        <?php if ( $img_url ) : ?>
                            <img
                                src="<?php echo esc_url( $img_url ); ?>"
                                alt="<?php echo esc_attr( get_the_title() ); ?>"
                                <?php echo $is_featured ? 'loading="eager"' : 'loading="lazy"'; ?>
                            >
                        <?php else : ?>
                            <div class="hero__image-placeholder"></div>
                        <?php endif; ?>
                        <?php if ( $is_featured ) : ?>
                            <div class="hero__image-badge">
                                <span><?php echo esc_html( get_the_title() ); ?></span>
                                <span><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                    $hero_count++;
                endwhile;
                wp_reset_postdata();
                $originals_query->rewind_posts();
            endif;
            ?>

        </div><!-- .hero__image-stack -->
    </div><!-- .hero__right -->

</section><!-- .hero -->


<?php /* ======================================================
   SECTION 2 — PORTFOLIO GRID
   ====================================================== */ ?>
<section class="portfolio-section section section--sm" id="portfolio">
    <div class="container">

        <div class="section-header">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Original works', 'jay-anderson-art' ); ?></span>
                <h2 class="section-header__title">
                    <?php esc_html_e( 'Paintings &', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'Drawings', 'jay-anderson-art' ); ?></em>
                </h2>
            </div>
            <a href="<?php echo esc_url( $orig_url ); ?>" class="section-header__link">
                <?php
                printf(
                    /* translators: %d: number of original works */
                    esc_html__( 'View all %d originals', 'jay-anderson-art' ),
                    esc_html( $originals_query->found_posts )
                );
                ?>
            </a>
        </div>

        <?php if ( $originals_query->have_posts() ) : ?>
        <div class="portfolio-grid">
            <?php
            $grid_count = 0;
            while ( $originals_query->have_posts() ) :
                $originals_query->the_post();
                $product    = wc_get_product( get_the_ID() );
                $img_id     = $product->get_image_id();
                $img_url    = $img_id ? wp_get_attachment_image_url( $img_id, 'jay-grid' ) : '';
                $artwork    = jay_get_artwork_meta( get_the_ID() );
                $in_stock   = $product->is_in_stock();
                $grid_count++;
                ?>
                <article class="portfolio-item <?php echo $grid_count === 1 ? 'portfolio-item--featured' : ''; ?>" data-animate>
                    <a href="<?php the_permalink(); ?>" class="portfolio-item__link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">

                        <?php if ( $img_url ) : ?>
                            <img
                                src="<?php echo esc_url( $img_url ); ?>"
                                alt="<?php echo esc_attr( get_the_title() ); ?>"
                                class="portfolio-item__image"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <div class="portfolio-item__image portfolio-item__image--placeholder"></div>
                        <?php endif; ?>

                        <?php if ( $product->get_price() ) : ?>
                            <span class="portfolio-item__price">
                                <?php echo wp_kses_post( $product->get_price_html() ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! $in_stock ) : ?>
                            <span class="badge badge--sold"><?php esc_html_e( 'Sold', 'jay-anderson-art' ); ?></span>
                        <?php endif; ?>

                        <div class="portfolio-item__overlay">
                            <h3 class="portfolio-item__title"><?php the_title(); ?></h3>
                            <?php if ( $artwork['medium'] || $artwork['dimensions'] ) : ?>
                                <p class="portfolio-item__meta">
                                    <?php
                                    $meta_parts = array_filter( array( $artwork['dimensions'], $artwork['medium'] ) );
                                    echo esc_html( implode( ' · ', $meta_parts ) );
                                    ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </a>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- .portfolio-grid -->
        <?php endif; ?>

    </div>
</section><!-- .portfolio-section -->


<?php /* ======================================================
   SECTION 3 — FEATURED STORY
   The first (featured) original gets the full narrative treatment.
   ====================================================== */ ?>
<?php if ( $featured_product ) :
    $feat_id      = $featured_product->get_id();
    $feat_img_id  = $featured_product->get_image_id();
    $feat_img_url = $feat_img_id ? wp_get_attachment_image_url( $feat_img_id, 'jay-product' ) : '';
    $feat_artwork = jay_get_artwork_meta( $feat_id );
    $feat_content = get_post_field( 'post_content', $feat_id );
    $feat_excerpt = get_post_field( 'post_excerpt', $feat_id );
    $feat_story   = $feat_excerpt ?: wp_trim_words( wp_strip_all_tags( $feat_content ), 60 );
?>
<section class="story-section section" id="featured-story">
    <div class="container">

        <div class="section-header">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'The story behind the work', 'jay-anderson-art' ); ?></span>
                <h2 class="section-header__title">
                    <?php esc_html_e( 'A moment', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'made permanent', 'jay-anderson-art' ); ?></em>
                </h2>
            </div>
        </div>

        <div class="story-grid">

            <div class="story-grid__image-wrap" data-animate>
                <span class="story-grid__piece-num" aria-hidden="true">01</span>
                <?php if ( $feat_img_url ) : ?>
                    <img
                        src="<?php echo esc_url( $feat_img_url ); ?>"
                        alt="<?php echo esc_attr( $featured_product->get_name() ); ?>"
                        class="story-grid__image"
                        loading="lazy"
                    >
                <?php else : ?>
                    <div class="story-grid__image story-grid__image--placeholder"></div>
                <?php endif; ?>
            </div>

            <div class="story-grid__content" data-animate>

                <?php if ( $feat_artwork['medium'] ) : ?>
                    <p class="story-grid__medium eyebrow">
                        <?php
                        $meta_parts = array_filter( array(
                            $feat_artwork['dimensions'],
                            $feat_artwork['medium'],
                        ) );
                        echo esc_html( implode( ' · ', $meta_parts ) );
                        ?>
                    </p>
                <?php endif; ?>

                <h3 class="story-grid__title">
                    <?php echo esc_html( $featured_product->get_name() ); ?>
                </h3>

                <?php if ( $feat_story ) : ?>
                    <div class="story-grid__body">
                        <?php echo wp_kses_post( wpautop( $feat_story ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="story-grid__specs">
                    <?php if ( $feat_artwork['medium'] ) : ?>
                        <div class="story-spec">
                            <span class="story-spec__label"><?php esc_html_e( 'Medium', 'jay-anderson-art' ); ?></span>
                            <span class="story-spec__value"><?php echo esc_html( $feat_artwork['medium'] ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ( $feat_artwork['dimensions'] ) : ?>
                        <div class="story-spec">
                            <span class="story-spec__label"><?php esc_html_e( 'Size', 'jay-anderson-art' ); ?></span>
                            <span class="story-spec__value"><?php echo esc_html( $feat_artwork['dimensions'] ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="story-spec">
                        <span class="story-spec__label"><?php esc_html_e( 'Finish', 'jay-anderson-art' ); ?></span>
                        <span class="story-spec__value">
                            <?php echo $feat_artwork['ready_to_hang']
                                ? esc_html__( 'Ready to hang, no glass', 'jay-anderson-art' )
                                : esc_html__( 'Ask about framing', 'jay-anderson-art' );
                            ?>
                        </span>
                    </div>
                    <div class="story-spec">
                        <span class="story-spec__label"><?php esc_html_e( 'Materials', 'jay-anderson-art' ); ?></span>
                        <span class="story-spec__value"><?php esc_html_e( 'Archival, museum-grade', 'jay-anderson-art' ); ?></span>
                    </div>
                </div>

                <p class="story-grid__price">
                    <?php echo wp_kses_post( $featured_product->get_price_html() ); ?>
                </p>

                <div class="story-grid__actions">
                    <?php if ( $featured_product->is_in_stock() ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $feat_id ) ); ?>" class="btn btn--primary">
                            <?php esc_html_e( 'Acquire This Piece', 'jay-anderson-art' ); ?>
                        </a>
                    <?php else : ?>
                        <span class="btn btn--primary btn--disabled" aria-disabled="true">
                            <?php esc_html_e( 'Sold', 'jay-anderson-art' ); ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( $orig_url ); ?>" class="btn btn--ghost">
                        <?php esc_html_e( 'More originals', 'jay-anderson-art' ); ?>
                    </a>
                </div>

            </div><!-- .story-grid__content -->

        </div><!-- .story-grid -->

    </div>
</section><!-- .story-section -->
<?php endif; ?>


<?php /* ======================================================
   SECTION 4 — ABOUT STRIP
   ====================================================== */ ?>
<section class="about-section section" id="about">

    <div class="about-section__image" aria-hidden="true">
        <?php
        /* Use a page featured image if an About page exists, otherwise placeholder */
        $about_page = get_page_by_path( 'about' );
        if ( $about_page && has_post_thumbnail( $about_page->ID ) ) {
            echo get_the_post_thumbnail( $about_page->ID, 'jay-wide', array( 'class' => 'about-section__img' ) );
        } else {
            /* Fallback — custom logo or text placeholder */
            echo '<div class="about-section__img-placeholder"></div>';
        }
        ?>
    </div>

    <div class="about-section__content" data-animate>

        <span class="eyebrow"><?php esc_html_e( 'About the artist', 'jay-anderson-art' ); ?></span>
        <div class="rule"></div>

        <h2 class="about-section__title">
            <?php esc_html_e( 'James', 'jay-anderson-art' ); ?><br>
            <em><?php esc_html_e( 'Anderson', 'jay-anderson-art' ); ?></em>
        </h2>

        <?php
        /* Pull excerpt from About page if it exists */
        $about_excerpt = $about_page ? get_the_excerpt( $about_page ) : '';
        if ( $about_excerpt ) :
            echo '<p class="about-section__text">' . esc_html( $about_excerpt ) . '</p>';
        else :
        ?>
        <p class="about-section__text">
            <?php esc_html_e( 'I am a contemporary fine artist based in Royal Oak, Michigan, specialising in portrait paintings and drawings that explore the human form, emotion, and identity.', 'jay-anderson-art' ); ?>
        </p>
        <p class="about-section__text">
            <?php esc_html_e( 'Influenced by classical and contemporary masters, my work uses archival materials and traditional technique to capture something true about the people I portray.', 'jay-anderson-art' ); ?>
        </p>
        <?php endif; ?>

        <dl class="about-section__details">
            <div class="about-detail">
                <dt class="about-detail__label"><?php esc_html_e( 'Based in', 'jay-anderson-art' ); ?></dt>
                <dd class="about-detail__value"><?php esc_html_e( 'Royal Oak, Michigan', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-detail">
                <dt class="about-detail__label"><?php esc_html_e( 'Medium', 'jay-anderson-art' ); ?></dt>
                <dd class="about-detail__value"><?php esc_html_e( 'Graphite, oil, mixed media', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-detail">
                <dt class="about-detail__label"><?php esc_html_e( 'Materials', 'jay-anderson-art' ); ?></dt>
                <dd class="about-detail__value"><?php esc_html_e( 'Archival, museum-grade', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-detail">
                <dt class="about-detail__label"><?php esc_html_e( 'Commissions', 'jay-anderson-art' ); ?></dt>
                <dd class="about-detail__value"><?php esc_html_e( 'Available on inquiry', 'jay-anderson-art' ); ?></dd>
            </div>
        </dl>

        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--outline-light">
            <?php esc_html_e( 'Commission a portrait', 'jay-anderson-art' ); ?> →
        </a>

    </div><!-- .about-section__content -->

</section><!-- .about-section -->


<?php /* ======================================================
   SECTION 5 — PRINTS PREVIEW
   ====================================================== */ ?>
<?php if ( $prints_query->have_posts() ) : ?>
<section class="prints-section section section--sm" id="prints">
    <div class="container">

        <div class="section-header">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'For every collector', 'jay-anderson-art' ); ?></span>
                <h2 class="section-header__title">
                    <?php esc_html_e( 'Limited', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'Prints', 'jay-anderson-art' ); ?></em>
                </h2>
            </div>
            <a href="<?php echo esc_url( $prints_url ); ?>" class="section-header__link">
                <?php esc_html_e( 'Shop all prints', 'jay-anderson-art' ); ?>
            </a>
        </div>

        <div class="prints-grid">
            <?php while ( $prints_query->have_posts() ) : $prints_query->the_post();
                $product   = wc_get_product( get_the_ID() );
                $img_id    = $product->get_image_id();
                $img_url   = $img_id ? wp_get_attachment_image_url( $img_id, 'jay-grid' ) : '';
                $in_stock  = $product->is_in_stock();
            ?>
            <article class="print-card" data-animate>
                <a href="<?php the_permalink(); ?>" class="print-card__link">

                    <div class="print-card__image-wrap">
                        <?php if ( $img_url ) : ?>
                            <img
                                src="<?php echo esc_url( $img_url ); ?>"
                                alt="<?php echo esc_attr( get_the_title() ); ?>"
                                class="print-card__image"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <div class="print-card__image print-card__image--placeholder"></div>
                        <?php endif; ?>
                        <?php if ( ! $in_stock ) : ?>
                            <span class="badge badge--sold"><?php esc_html_e( 'Sold out', 'jay-anderson-art' ); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="print-card__info">
                        <h3 class="print-card__title"><?php the_title(); ?></h3>
                        <?php
                        $artwork = jay_get_artwork_meta( get_the_ID() );
                        if ( $artwork['dimensions'] || $artwork['medium'] ) :
                        ?>
                            <p class="print-card__meta">
                                <?php
                                $parts = array_filter( array( 'Archival giclée', $artwork['dimensions'] ) );
                                echo esc_html( implode( ' · ', $parts ) );
                                ?>
                            </p>
                        <?php else : ?>
                            <p class="print-card__meta"><?php esc_html_e( 'Archival giclée · Multiple sizes', 'jay-anderson-art' ); ?></p>
                        <?php endif; ?>
                        <p class="print-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
                    </div>

                </a>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- .prints-grid -->

    </div>
</section><!-- .prints-section -->
<?php endif; ?>


<?php /* ======================================================
   SECTION 6 — CONTACT CTA
   ====================================================== */ ?>
<section class="contact-cta" id="contact-cta" aria-labelledby="contact-cta-title">

    <div class="contact-cta__inner">
        <span class="eyebrow"><?php esc_html_e( 'Let\'s talk', 'jay-anderson-art' ); ?></span>
        <h2 class="contact-cta__title" id="contact-cta-title">
            <?php esc_html_e( 'Interested in a piece', 'jay-anderson-art' ); ?><br>
            <?php esc_html_e( 'or a ', 'jay-anderson-art' ); ?><em><?php esc_html_e( 'commission?', 'jay-anderson-art' ); ?></em>
        </h2>
        <p class="contact-cta__desc">
            <?php esc_html_e( 'Whether you\'re a first-time collector or looking for a custom portrait, I\'d love to hear from you. Every inquiry gets a personal response.', 'jay-anderson-art' ); ?>
        </p>
        <a href="mailto:jay@jayanderson.art" class="contact-cta__email">
            jay@jayanderson.art
        </a>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--outline-light">
            <?php esc_html_e( 'Send a message', 'jay-anderson-art' ); ?>
        </a>
        <div class="contact-cta__social">
            <a href="https://www.instagram.com/jayanderson.art" target="_blank" rel="noopener noreferrer">Instagram</a>
        </div>
    </div>

</section><!-- .contact-cta -->

<?php get_footer(); ?>
