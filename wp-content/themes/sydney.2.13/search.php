<?php
/**
 * The template for displaying search results pages.
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
				<h3>
					<?php
					printf(
						esc_html__( 'Search Results for: %s', 'sydney' ),
						'<span>' . esc_html( get_search_query() ) . '</span>'
					);
					?>
				</h3>
			</header><!-- .page-header -->

			<div class="posts-layout search-layout-custom">
				<div class="row" <?php sydney_masonry_data(); ?> <?php echo esc_attr( apply_filters( 'sydney_posts_layout_row', '' ) ); ?>>
					<?php
					$current_category = '';
					while ( have_posts() ) :
						the_post();

						$categories    = get_the_category();
						$post_category = ! empty( $categories ) ? $categories[0]->name : '';

						if ( $post_category && $post_category !== $current_category ) {
							echo '<h2>' . esc_html( $post_category ) . '</h2>';
							$current_category = $post_category;
						}

						mfca_render_book_post_item();
					endwhile;
					?>
					<?php the_posts_pagination(); ?>
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
