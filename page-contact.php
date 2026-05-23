<?php
/**
 * Template Name: Contact
 *
 * Assign this template to your Contact page in WordPress:
 * Pages → Contact → Page Attributes → Template → Contact
 *
 * Sections:
 *  1. Header      — page title + intro
 *  2. Contact grid — form left, info right
 *  3. Commission   — what to expect from a commission inquiry
 *
 * The contact form is rendered by whatever plugin is active.
 * Place the form shortcode in the WordPress page editor and
 * it will output in the form column automatically.
 *
 * Supports: WPForms, Contact Form 7, Gravity Forms, Ninja Forms
 *
 * @package jay-anderson-art
 */

get_header();

while ( have_posts() ) :
    the_post();

    $page_content = get_the_content();
    $orig_url     = get_term_link( 'original-work', 'product_cat' );
    $orig_url     = is_wp_error( $orig_url ) ? get_permalink( wc_get_page_id( 'shop' ) ) : $orig_url;
?>

<?php /* ======================================================
   CONTACT HEADER
   ====================================================== */ ?>
<section class="contact-header">
    <div class="container2">
        <div class="contact-header__inner" data-animate>
            <span class="eyebrow"><?php esc_html_e( 'Get in touch', 'jay-anderson-art' ); ?></span>
            <h1 class="contact-header__title">
                <?php esc_html_e( 'Let\'s', 'jay-anderson-art' ); ?> <em><?php esc_html_e( 'talk', 'jay-anderson-art' ); ?></em>
            </h1>
            <p class="contact-header__desc">
                <?php esc_html_e( 'Whether you\'re interested in an existing piece, looking for a custom portrait, or just want to say hello — I\'d love to hear from you. Every message gets a personal reply.', 'jay-anderson-art' ); ?>
            </p>
        </div>
    </div>
</section>


<?php /* ======================================================
   CONTACT GRID — form + info
   ====================================================== */ ?>
<section class="contact-grid section section--sm">
    <div class="container2">
        <div class="contact-grid__inner">

            <?php /* Left — contact form from WP editor */ ?>
            <div class="contact-form-col" data-animate>
                <h2 class="contact-form-col__title">
                    <?php esc_html_e( 'Send a message', 'jay-anderson-art' ); ?>
                </h2>

                <?php if ( $page_content ) : ?>
                    <div class="contact-form-wrap">
                        <?php echo apply_filters( 'the_content', $page_content ); ?>
                    </div>
                <?php else : ?>
                    <?php /* Fallback if no shortcode is placed — plain mailto */ ?>
                    <div class="contact-form-fallback">
                        <p class="contact-form-fallback__text">
                            <?php esc_html_e( 'Place your contact form shortcode in the page editor and it will appear here.', 'jay-anderson-art' ); ?>
                        </p>
                        <a href="mailto:jay@jayanderson.art" class="btn btn--primary">
                            <?php esc_html_e( 'Email Jay directly', 'jay-anderson-art' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php /* Right — contact info */ ?>
            <div class="contact-info-col" data-animate>

            

                <div class="contact-info-block">
                    <h3 class="contact-info-block__title">
                        <?php esc_html_e( 'Studio', 'jay-anderson-art' ); ?>
                    </h3>
                    <p class="contact-info-block__address">
                        <?php esc_html_e( 'Royal Oak, Michigan', 'jay-anderson-art' ); ?><br>
                        <?php esc_html_e( 'United States', 'jay-anderson-art' ); ?>
                    </p>
                </div>

                <div class="contact-info-block">
                    <h3 class="contact-info-block__title">
                        <?php esc_html_e( 'Follow the work', 'jay-anderson-art' ); ?>
                    </h3>
                    <div class="contact-info-social">
                        <a
                            href="https://www.instagram.com/jayanderson.art"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="contact-info-social__link"
                        >
                            Instagram → @jayanderson.art
                        </a>
                    </div>
                </div>

                <div class="contact-info-block contact-info-block--highlight">
                    <h3 class="contact-info-block__title">
                        <?php esc_html_e( 'Collecting', 'jay-anderson-art' ); ?>
                    </h3>
                    <p class="contact-info-block__note">
                        <?php esc_html_e( 'All originals ship professionally packaged and insured. International shipping available on request.', 'jay-anderson-art' ); ?>
                    </p>
                    <a href="<?php echo esc_url( $orig_url ); ?>" class="btn btn--outline">
                        <?php esc_html_e( 'Browse originals', 'jay-anderson-art' ); ?>
                    </a>
                </div>

            </div><!-- .contact-info-col -->

        </div>
    </div>
</section><!-- .contact-grid -->


<?php /* ======================================================
   COMMISSION SECTION
   ====================================================== */ ?>
<section class="commission-section section">
    <div class="container2">

        <div class="commission-section__header" data-animate>
            <span class="eyebrow"><?php esc_html_e( 'Custom portraits', 'jay-anderson-art' ); ?></span>
            <h2 class="commission-section__title">
                <?php esc_html_e( 'Commission', 'jay-anderson-art' ); ?><br>
                <em><?php esc_html_e( 'a portrait', 'jay-anderson-art' ); ?></em>
            </h2>
            <p class="commission-section__intro">
                <?php esc_html_e( 'A limited number of portrait commissions are accepted each year. Here\'s what the process typically looks like.', 'jay-anderson-art' ); ?>
            </p>
        </div>

        <div class="commission-steps">

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">01</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'Get in touch', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'Send a message describing who the portrait is of, any reference photos you have in mind, and your preferred medium (graphite, oil, or mixed media). If you are a local client, arrangements can be made for a photo session.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">02</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'Discuss & quote', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'I will respond with questions about size, reference material, and timeline, followed by a quote. The signed contract and a 50% deposit secures your spot.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">03</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'Progress updates', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'You\'ll receive progress updates per contract agreement and have an opportunity to review before the piece is finalized.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

            <div class="commission-step" data-animate>
                <span class="commission-step__num" aria-hidden="true">04</span>
                <div class="commission-step__content">
                    <h3 class="commission-step__title"><?php esc_html_e( 'Delivery', 'jay-anderson-art' ); ?></h3>
                    <p class="commission-step__body">
                        <?php esc_html_e( 'The remaining balance is due before shipping. Your art arrives professionally packaged, insured, and accompanied by a certificate of authenticity.', 'jay-anderson-art' ); ?>
                    </p>
                </div>
            </div>

        </div><!-- .commission-steps -->

</section><!-- .commission-section -->

<?php endwhile; ?>

<?php get_footer(); ?>
