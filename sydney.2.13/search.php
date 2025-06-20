<?php
/**
 * The template for displaying search results pages.
 *
 * @package Sydney
 */

get_header();

$layout 		= sydney_blog_layout();
$sidebar_pos 	= sydney_sidebar_position();
$archive_title_layout = get_theme_mod( 'archive_title_layout', 'layout1' );
?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( $layout ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>
			<style>
				.mfca-main {
					display:none;
				}
                h3 a {
                    font-size: 28px;
                    color:var(--sydney-headings-color);
                }
                h3 a:hover {
                    opacity: 0.8;
                }
                @media screen and (max-width:550px) {
                    .posts-layout {
                        padding: 0 15px;
                    }
                }
                .post__book {
                    display: flex;
                    margin-bottom: 25px;
                    /* column-gap: 25px; */
                }
                .post__book-image {
                    flex: 1 0 auto;
                    text-align: center;
                    position: relative;
                    margin-right: 30px;

                }
                .post__book-image::before {
                    content:"";
                    position: absolute;
                    top:0;
                    left:0;
                    width: 100%;
                    height: 100%;
                    opacity:0;
                    background-color:#fff;
                    transition: all .3s ease 0s;
                }
                .post__book-image:hover::before {
                        opacity: .3;
                }
                .post__book-image img {
                    max-width: 170px;
                    height: auto;
                }
                .post__book-body {
                    flex: 1 1 auto;
                }
                @media (max-width:550px) {
                    .post__book {
                        flex-direction:column;
                        margin-bottom: 15px;
                    }
                    .post__book-image {
                        margin-right: 0px;
                    }
                    .post__book-image img {
                            max-width: 300px;
                        }
                }
            </style>
			<header class="page-header">
				<h3><?php printf( __( 'Search Results for: %s', 'sydney' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
			</header><!-- .page-header -->

			<div class="posts-layout search-layout-custom">
				<div class="row" <?php sydney_masonry_data(); ?> <?php echo esc_attr( apply_filters( 'sydney_posts_layout_row', '' ) ); ?>>
					<?php $current_category = '';
					while ( have_posts() ) : the_post();
					$post_category = get_the_category()[0]->name;
					if ( $post_category != $current_category ) {
					    // Display the category title for a new group
					    echo '<h2>' . $post_category . '</h2>';
					    $current_category = $post_category;
					}

					// Display the post content for this group
					// You can customize this to display whatever post information you want
					?>
					<?php if (has_post_thumbnail()) {?>
							<article class="post__book">
								<a href="<?php the_permalink() ?>" class="post__book-image">
									<?php the_post_thumbnail(); ?>
								</a>
								<div class="post__book-body">
									<h3>
										<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
									</h3>
									<?php the_excerpt(); ?>
								</div>
							</article>
						<?php
						} else {
						?>
							<div style="display:block;" class="post__book">
								<?php the_content();?>
							</div>
						<?php
						} 
						endwhile; ?>
					<?php the_posts_pagination(); ?>
				</div>
			</div>
			
			<style>
				.row {
					/* display:flex; */
					/* flex-wrap:wrap; */
					margin:0
				}
				.post__book {
					/* flex: 1 0 250px; */
				}
				.no-sidebar .layout1 {
					max-width:100%;			
				}
			</style>
	
			<?php sydney_posts_navigation(); ?>	

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>

