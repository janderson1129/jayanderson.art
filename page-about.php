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
<section class="about-hero <?php echo $hero_img_url ? 'about-hero--has-image' : ''; ?>">

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
            <h1 class="about-hero__title">
                <?php esc_html_e( 'James', 'jay-anderson-art' ); ?><br>
                <em><?php esc_html_e( 'Anderson', 'jay-anderson-art' ); ?></em>
            </h1>
            <?php if ( $page_excerpt ) : ?>
                <p class="about-hero__tagline"><?php echo esc_html( $page_excerpt ); ?></p>
            <?php else : ?>
                <p class="about-hero__tagline">
                    <?php esc_html_e( 'Contemporary fine artist · Royal Oak, Michigan', 'jay-anderson-art' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

</section><!-- .about-hero -->


<?php /* ======================================================
   BIO — photo + text
   ====================================================== */ ?>
<section class="about-bio section">

    <div class="about-bio__image-col" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'jay-product', array(
                'class'   => 'about-bio__image',
                'loading' => 'lazy',
            ) ); ?>
        <?php else : ?>
            <div class="about-bio__image-placeholder"></div>
        <?php endif; ?>
    </div>

    <div class="about-bio__content">

        <span class="eyebrow"><?php esc_html_e( 'About Jay', 'jay-anderson-art' ); ?></span>
        <div class="rule"></div>

        <h2 class="about-bio__title">
            <?php esc_html_e( 'Portraits that hold', 'jay-anderson-art' ); ?><br>
            <em><?php esc_html_e( 'time still', 'jay-anderson-art' ); ?></em>
        </h2>

        <?php if ( $bio_main ) : ?>
            <div class="about-bio__body">
                <?php echo wp_kses_post( apply_filters( 'the_content', $bio_main ) ); ?>
            </div>
        <?php else : ?>
            <div class="about-bio__body">
                <p>
                    <?php esc_html_e( 'I am a contemporary fine artist based in Royal Oak, Michigan, specialising in portrait paintings and drawings that explore the human form, emotion, and identity.', 'jay-anderson-art' ); ?>
                </p>
                <p>
                    <?php esc_html_e( 'Influenced by classical and contemporary masters, my work uses archival materials and traditional technique to capture something true about the people I portray — the quiet moments, the unguarded expressions, the emotions that words can\'t reach.', 'jay-anderson-art' ); ?>
                </p>
                <p>
                    <?php esc_html_e( 'Each piece begins with a photograph — often a casual moment, a fleeting expression — and through weeks of careful work becomes something permanent. Something that will outlast us all.', 'jay-anderson-art' ); ?>
                </p>
            </div>
        <?php endif; ?>

        <dl class="about-bio__details">
            <div class="about-bio__detail">
                <dt><?php esc_html_e( 'Based in', 'jay-anderson-art' ); ?></dt>
                <dd><?php esc_html_e( 'Royal Oak, Michigan', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail">
                <dt><?php esc_html_e( 'Medium', 'jay-anderson-art' ); ?></dt>
                <dd><?php esc_html_e( 'Graphite, oil, mixed media', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail">
                <dt><?php esc_html_e( 'Materials', 'jay-anderson-art' ); ?></dt>
                <dd><?php esc_html_e( 'Archival, museum-grade', 'jay-anderson-art' ); ?></dd>
            </div>
            <div class="about-bio__detail">
                <dt><?php esc_html_e( 'Commissions', 'jay-anderson-art' ); ?></dt>
                <dd><?php esc_html_e( 'Limited availability', 'jay-anderson-art' ); ?></dd>
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
<section class="about-process section">
    <div class="container">

        <div class="about-process__header" data-animate>
            <span class="eyebrow"><?php esc_html_e( 'The work', 'jay-anderson-art' ); ?></span>
            <h2 class="about-process__title">
                <?php esc_html_e( 'Craft &', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'process', 'jay-anderson-art' ); ?></em>
            </h2>
        </div>

        <div class="about-process__grid">

            <div class="about-process__item" data-animate>
                <span class="about-process__num" aria-hidden="true">01</span>
                <h3 class="about-process__item-title"><?php esc_html_e( 'The reference', 'jay-anderson-art' ); ?></h3>
                <p class="about-process__item-body">
                    <?php esc_html_e( 'Every portrait begins with a photograph — usually a candid moment rather than a posed portrait. The goal is to find the instant where the subject forgot the camera was there.', 'jay-anderson-art' ); ?>
                </p>
            </div>

            <div class="about-process__item" data-animate>
                <span class="about-process__num" aria-hidden="true">02</span>
                <h3 class="about-process__item-title"><?php esc_html_e( 'The drawing', 'jay-anderson-art' ); ?></h3>
                <p class="about-process__item-body">
                    <?php esc_html_e( 'Graphite work begins with light gesture lines, gradually building tone through layered mark-making. For mixed media pieces, a graphite underpainting is established before oil or other media is introduced.', 'jay-anderson-art' ); ?>
                </p>
            </div>

            <div class="about-process__item" data-animate>
                <span class="about-process__num" aria-hidden="true">03</span>
                <h3 class="about-process__item-title"><?php esc_html_e( 'The materials', 'jay-anderson-art' ); ?></h3>
                <p class="about-process__item-body">
                    <?php esc_html_e( 'All works are created on archival cradled panels using museum-grade materials selected for longevity. Most originals are designed to hang without glass, allowing direct engagement with the surface.', 'jay-anderson-art' ); ?>
                </p>
            </div>

            <div class="about-process__item" data-animate>
                <span class="about-process__num" aria-hidden="true">04</span>
                <h3 class="about-process__item-title"><?php esc_html_e( 'The finish', 'jay-anderson-art' ); ?></h3>
                <p class="about-process__item-body">
                    <?php esc_html_e( 'Each piece is sealed and inspected before leaving the studio. Originals ship professionally packaged with a certificate of authenticity and a note from Jay about the work.', 'jay-anderson-art' ); ?>
                </p>
            </div>

        </div><!-- .about-process__grid -->

    </div>
</section><!-- .about-process -->


<?php /* ======================================================
   WORK CTA — link to portfolio
   ====================================================== */ ?>
<section class="about-cta">
    <div class="container">
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
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--ghost">
                    <?php esc_html_e( 'Commission a portrait', 'jay-anderson-art' ); ?>
                </a>
            </div>

        </div>
    </div>
</section><!-- .about-cta -->

<?php endwhile; ?>

<?php get_footer(); ?>
