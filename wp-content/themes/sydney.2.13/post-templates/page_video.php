<?php
/*
Template Name: Full width video
Template Post Type: post, projects, employees
*/

get_header(); ?>

	<div id="primary" class="content-area <?php echo esc_attr( apply_filters( 'sydney_content_area_class', '' ) ); ?>">
		<?php sydney_yoast_seo_breadcrumbs(); ?>

		<main id="main" class="site-main" role="main">

			<?php mfca_post_types_menu(); ?>

			<?php dimox_breadcrumbs(); ?>

			<br>

			<div class="entry-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div><!-- .entry-content -->

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

			<?php echo mfca_related_posts_grid(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
