<?php
/**
 * Template Name: About
 *
 * Assign this template to your About page in WordPress:
 * Pages → About → Page Attributes → Template → About
 *
 * Sections:
 *  1. Hero        — full-width headline with featured image
 *  2. Bio         — two-column: photo + text
 *  3. Process     — how Jay works, materials, approach
 *  4. Details     — medium, location, commissions strip
 *  5. Works CTA   — link through to portfolio
 *
 * @package jay-anderson-art
 */

get_header();

while ( have_posts() ) :
    the_post();

    /* Page content — pulled from WordPress editor */
    $page_content = get_the_content();
    $page_excerpt = get_the_excerpt();

    /* Featured image for hero */
    $hero_img_id  = get_post_thumbnail_id();
    $hero_img_url = $hero_img_id
        ? wp_get_attachment_image_url( $hero_img_id, 'jay-hero' )
        : '';

    /* Shop / originals URL */
    $orig_url = get_term_link( 'original-work', 'product_cat' );
    $orig_url = is_wp_error( $orig_url ) ? get_permalink( wc_get_page_id( 'shop' ) ) : $orig_url;

    /* Split page content into blocks for the two bio columns.
       If the editor content has an <!--more--> tag, split there.
       Otherwise show everything in the main column. */
    $content_parts = get_extended( $page_content );
    $bio_main      = $content_parts['main']     ?: $page_content;
    $bio_extended  = $content_parts['extended'] ?: '';
?>

<?php /* ======================================================
   ABOUT HERO
   ====================================================== */ ?>
<section class="about-hero <?php echo $hero_img_url ? 'about-hero--has-image' : ''; ?>" style="padding-top: calc(var(--section-pad, 6rem) / 2);">

    <?php if ( $hero_img_url ) : ?>
        <div class="about-hero__image-wrap" aria-hidden="true">
            <img
                src="<?php echo esc_url( $hero_img_url ); ?>"
                alt=""
                class="about-hero__image"
                loading="eager"
            >
            <div class="about-hero__image-overlay"></div>
        </div>
    <?php endif; ?>

    <div class="about-hero__content container">
        <div class="about-hero__text" data-animate>
            <span class="eyebrow about-hero__eyebrow">
                <?php esc_html_e( 'The artist', 'jay-anderson-art' ); ?>
            </span>
            <h1 class="about-hero__title" style="font-size: clamp(2rem, 5vw, 3.5rem); white-space: nowrap;">
                <?php esc_html_e( 'Jay', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'Anderson', 'jay-anderson-art' ); ?></em>
            </h1>
            
                <p class="about-hero__tagline">
                    <?php esc_html_e( 'Contemporary fine artist · Royal Oak, Michigan', 'jay-anderson-art' ); ?>
                </p>
            
        </div>
    </div>

</section><!-- .about-hero -->


<?php /* ======================================================
   BIO — photo + text
   ====================================================== */ ?>
<section class="about-bio section" style="align-items: flex-start; overflow: visible;">

    <div class="about-bio__image-col" aria-hidden="true" style="background: transparent; align-self: flex-start; position: relative; overflow: visible; padding-top: 0; margin-top: 0;">
        <?php
        /* Build a gallery of up to 6 images attached to this page for the 3×2 grid.
           Falls back gracefully: uses the featured image if fewer than 6 are attached. */
        $gallery_ids = array();

        /* 1. Try page-attached images first */
        $attached = get_posts( array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_parent'    => get_the_ID(),
            'numberposts'    => 6,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ) );
        foreach ( $attached as $att ) {
            $gallery_ids[] = $att->ID;
        }

        /* 2. Pad with the featured image if needed */
        if ( $hero_img_id && ! in_array( $hero_img_id, $gallery_ids ) ) {
            array_unshift( $gallery_ids, $hero_img_id );
        }

        /* 3. Trim to 6 */
        $gallery_ids = array_slice( $gallery_ids, 0, 6 );
        ?>

        <div class="about-bio__grid" style="
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 0.5rem;
            width: 100%;
            aspect-ratio: 3 / 2;
            margin-top: 0;
            padding-top: 0;
        ">
            <?php if ( ! empty( $gallery_ids ) ) : ?>
                <?php foreach ( $gallery_ids as $img_id ) : ?>
                    <div class="about-bio__grid-cell" style="overflow: hidden; border-radius: 2px;">
                        <?php echo wp_get_attachment_image( $img_id, 'medium', false, array(
                            'class'   => 'about-bio__grid-img',
                            'loading' => 'lazy',
                            'style'   => 'width:100%;height:100%;object-fit:cover;display:block;',
                        ) ); ?>
                    </div>
                <?php endforeach; ?>
                <?php /* Fill any empty cells so the grid always shows 6 slots */ ?>
                <?php for ( $i = count( $gallery_ids ); $i < 6; $i++ ) : ?>
                    <div class="about-bio__grid-cell about-bio__grid-cell--empty" style="background: var(--color-surface-2, #f0ece6); border-radius: 2px;"></div>
                <?php endfor; ?>
            <?php else : ?>
                <?php for ( $i = 0; $i < 6; $i++ ) : ?>
                    <div class="about-bio__grid-cell about-bio__grid-cell--empty" style="background: var(--color-surface-2, #f0ece6); border-radius: 2px;"></div>
                <?php endfor; ?>
            <?php endif; ?>
        </div><!-- .about-bio__grid -->

    </div>

    <div class="about-bio__content" style="align-self: flex-start; padding-top: 0;">

        <h2 class="about-bio__title" style="margin-top: 0; padding-top: 0;">
            <?php esc_html_e( 'Portraits that hold', 'jay-anderson-art' ); ?>
            <em><?php esc_html_e( 'time still', 'jay-anderson-art' ); ?></em>
        </h2>

        <?php
        /* Strip only gallery/image blocks from the content, preserving all text.
           Parse by block type so we never accidentally touch paragraph content. */
        $blocks = parse_blocks( $bio_main );
        $text_blocks = array_filter( $blocks, function( $block ) {
            return ! in_array( $block['blockName'], array( 'core/gallery', 'core/image' ), true );
        } );
        $bio_main_clean = '';
        foreach ( $text_blocks as $block ) {
            $bio_main_clean .= render_block( $block );
        }
        ?>
        <?php if ( trim( $bio_main_clean ) ) : ?>
            <div class="about-bio__body">
                <?php echo wp_kses_post( apply_filters( 'the_content', $bio_main_clean ) ); ?>
            </div>
        <?php endif; ?>

        <dl class="about-bio__details" style="display: grid; grid-template-columns: auto auto; gap: 0.25rem 1rem;">
            <div class="about-bio__detail" style="display: contents;">
                <dt style="text-align: right;"><?php esc_html_e( 'Based in', 'jay-anderson-art' ); ?></dt>
                <dd style="text-align: left; margin: 0;"><?php esc_html_e( 'Royal Oak, Michigan', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail" style="display: contents;">
                <dt style="text-align: right;"><?php esc_html_e( 'Medium', 'jay-anderson-art' ); ?></dt>
                <dd style="text-align: left; margin: 0;"><?php esc_html_e( 'Graphite, oil, mixed media', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail" style="display: contents;">
                <dt style="text-align: right;"><?php esc_html_e( 'Materials', 'jay-anderson-art' ); ?></dt>
                <dd style="text-align: left; margin: 0;"><?php esc_html_e( 'Archival, museum-grade', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail" style="display: contents;">
                <dt style="text-align: right;"><?php esc_html_e( 'Commissions', 'jay-anderson-art' ); ?></dt>
                <dd style="text-align: left; margin: 0;"><?php esc_html_e( 'Limited availability', 'jay-anderson-art' ); ?></dd>
            </div>
        </dl>

    </div><!-- .about-bio__content -->

</section><!-- .about-bio -->


<?php /* ======================================================
   EXTENDED CONTENT — if page has a <!--more--> split
   ====================================================== */ ?>
<?php if ( $bio_extended ) : ?>
<section class="about-extended section section--sm">
    <div class="container container--text">
        <div class="about-extended__body" data-animate>
            <?php echo wp_kses_post( apply_filters( 'the_content', $bio_extended ) ); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php /* ======================================================
   PROCESS — how Jay works
   ====================================================== */ ?>
<section class="commission-section section">
    <div class="container2">

        <div class="commission-section__header" data-animate>
            <span class="eyebrow"><?php esc_html_e( 'The work', 'jay-anderson-art' ); ?></span>
            <h2 class="commission-section__title">
                <?php esc_html_e( 'Craft &', 'jay-anderson-art' ); ?><br>
                <em><?php esc_html_e( 'process', 'jay-anderson-art' ); ?></em>
            </h2>
        </div>

        <div class="commission-steps">

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">01</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'The reference', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'Every portrait begins with a photograph — usually a candid moment rather than a posed portrait. The goal is to find the instant where the subject forgot the camera was there.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">02</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'The drawing', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'Graphite work begins with light gesture lines, gradually building tone through layered mark-making. For mixed media pieces, a graphite underpainting is established before oil or other media is introduced.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">03</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'The materials', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'All works are created on archival cradled panels using museum-grade materials selected for longevity. Most originals are designed to hang without glass, allowing direct engagement with the surface.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">04</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'The finish', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'Each piece is sealed and inspected before leaving the studio. Originals ship professionally packaged with a certificate of authenticity and a note from Jay about the work.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

        </div><!-- .commission-steps -->

    </div>
</section><!-- .commission-section -->


<?php /* ======================================================
   WORK CTA — link to portfolio
   ====================================================== */ ?>
<section class="about-cta">
    <div class="container2">
        <div class="about-cta__inner" data-animate>

            <div class="about-cta__text">
                <span class="eyebrow"><?php esc_html_e( 'Collect', 'jay-anderson-art' ); ?></span>
                <h2 class="about-cta__title">
                    <?php esc_html_e( 'Explore the', 'jay-anderson-art' ); ?><br>
                    <em><?php esc_html_e( 'portfolio', 'jay-anderson-art' ); ?></em>
                </h2>
                <p class="about-cta__desc">
                    <?php esc_html_e( 'Original paintings, graphite drawings, and limited edition prints — all available to collect.', 'jay-anderson-art' ); ?>
                </p>
            </div>

            <div class="about-cta__actions">
                <a href="<?php echo esc_url( $orig_url ); ?>" class="btn btn--primary">
                    <?php esc_html_e( 'View originals', 'jay-anderson-art' ); ?>
                </a>
                
            </div>

        </div>
    </div>
</section><!-- .about-cta -->

<?php endwhile; ?>

<?php get_footer(); ?>
