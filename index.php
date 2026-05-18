<?php
/**
 * Index — generic fallback template.
 *
 * WordPress uses this when no more specific template is found.
 * In practice, front-page.php, archive.php, and single.php
 * will handle all real page types for this theme.
 *
 * @package jay-anderson-art
 */

get_header();
?>

<div class="container section">

    <?php if ( have_posts() ) : ?>

        <div class="section-header">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Latest', 'jay-anderson-art' ); ?></span>
                <h1 class="section-header__title">
                    <?php
                    if ( is_home() && ! is_front_page() ) {
                        single_post_title();
                    } elseif ( is_search() ) {
                        printf(
                            /* translators: %s: search query */
                            esc_html__( 'Search results for: %s', 'jay-anderson-art' ),
                            '<em>' . get_search_query() . '</em>'
                        );
                    } else {
                        bloginfo( 'name' );
                    }
                    ?>
                </h1>
            </div>
        </div>

        <div class="posts-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="post-card__image">
                            <?php the_post_thumbnail( 'jay-grid' ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="post-card__content">
                        <h2 class="post-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="post-card__excerpt"><?php the_excerpt(); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_navigation(); ?>

    <?php else : ?>

        <div class="no-results">
            <span class="eyebrow"><?php esc_html_e( 'Nothing found', 'jay-anderson-art' ); ?></span>
            <h1><?php esc_html_e( 'No results', 'jay-anderson-art' ); ?></h1>
            <p>
                <?php esc_html_e( 'Try a different search, or browse the portfolio.', 'jay-anderson-art' ); ?>
            </p>
            <a href="<?php echo esc_url( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'View All Work', 'jay-anderson-art' ); ?>
            </a>
        </div>

    <?php endif; ?>

</div>

<?php get_footer(); ?>
