<?php
/**
 * The template for displaying all single posts.
 *
 * @package Sydney
 */

get_header();

$sidebar_pos = sydney_sidebar_position();
$width       = get_theme_mod( 'fullwidth_single' ) ? 'fullwidth' : 'col-md-9';
?>

	<?php do_action( 'sydney_before_content' ); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', $width ) ); ?>">

		<main id="main" class="post-wrap" role="main">

			<?php mfca_post_types_menu(); ?>

			<?php dimox_breadcrumbs(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>

				<?php echo mfca_related_posts_grid(); ?>

			<?php endwhile; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action( 'sydney_after_content' ); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
