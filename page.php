<?php
/**
 * Generic Page Template
 *
 * Used for any WordPress page that doesn't have a specific
 * template assigned (About, Contact, etc. use their own).
 *
 * @package jay-anderson-art
 */

get_header();

while ( have_posts() ) :
    the_post();
?>

<section class="generic-page-header">
    <div class="container">
        <div class="generic-page-header__inner" data-animate>
            <h1 class="generic-page-header__title"><?php the_title(); ?></h1>
        </div>
    </div>
</section>

<section class="generic-page-content section section--sm">
    <div class="container container--text">

        <?php if ( has_post_thumbnail() ) : ?>
            <div class="generic-page-hero-image" data-animate>
                <?php the_post_thumbnail( 'jay-wide', array( 'class' => 'generic-page-hero-image__img' ) ); ?>
            </div>
        <?php endif; ?>

        <div class="generic-page-body" data-animate>
            <?php the_content(); ?>
        </div>

        <?php
        wp_link_pages( array(
            'before' => '<nav class="page-links"><span class="page-links__label">' . esc_html__( 'Pages:', 'jay-anderson-art' ) . '</span>',
            'after'  => '</nav>',
        ) );
        ?>

    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
