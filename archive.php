<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sydney
 */

get_header();

$layout 		= sydney_blog_layout();
$sidebar_pos 	= sydney_sidebar_position();
?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( $layout ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					// the_archive_title( '<h1 class="archive-title">', '</h1>' );
					// the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
            <style>
                h3 {
                    font-size: 32px;
                }
                .post__book {
                    display: flex;
                    /* column-gap: 25px; */
                }
                .post__book-image  {
                    flex: 1 0 auto;
                }
                .post__book-image img {
                    max-width: 250px;
                    height: auto;
                    margin-right: 30px;
                }
                .post__book-body {
                    flex: 1 1 auto;
                }
                @media (max-width:768px) {
                    .post__book {
                        flex-direction:column;
                        align-items:
                    }
                }
            </style>
			<div class="posts-layout">
				<div class="row" <?php sydney_masonry_data(); ?>>
					<?php while ( have_posts() ) : the_post(); ?>

						<!-- get_template_part( 'content', get_post_format() ); -->
                        
						<div class="post__book">
                            <a href="<?php the_permalink() ?>" class="post__book-image">
                                <?php the_post_thumbnail(); ?>
                            </a>
                            <div class="post__book-body">
                                <p><?php the_content(); ?></p>
                            </div>
                        </div>

					<?php endwhile; ?>
				</div>
			</div>
			
			<?php sydney_posts_navigation(); ?>	

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
