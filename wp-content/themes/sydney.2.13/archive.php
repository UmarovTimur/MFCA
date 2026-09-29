<?php
/**
 * The template for displaying archive pages.
 *
 * Language categories (see mfca_languages()) list a slider per subcategory;
 * every other archive is a plain list of posts.
 *
 * @package Sydney
 */

get_header();

$sidebar_pos = sydney_sidebar_position();
$term        = get_queried_object();
$part        = ( is_category() && mfca_is_language( $term->slug ) ) ? 'main-category' : 'book-list';
?>

	<?php do_action( 'sydney_before_content' ); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?><?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
		<?php get_template_part( 'part-templates/archive', $part ); ?>
	</div><!-- #primary -->

	<?php do_action( 'sydney_after_content' ); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
