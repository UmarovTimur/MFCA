<?php
/*
Template Name: Full width video
Template Post Type: post, projects, employees

*/

get_header(); ?>

<?php
$sidebar_pos = sydney_sidebar_position();
$width       = 'fullwidth';
?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', $width ) ); ?>">
		<?php sydney_yoast_seo_breadcrumbs(); ?>
		
		<main id="main" class="site-main" role="main">

			<?php post_types_menu_header(); ?>
			<?php if ( function_exists( 'dimox_breadcrumbs' ) ) dimox_breadcrumbs(); ?>


				<br>

			<div class="entry-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div><!-- .entry-content -->


			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

			<?php echo render_post_grid( mfca_get_related_posts_for_current_post() ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
