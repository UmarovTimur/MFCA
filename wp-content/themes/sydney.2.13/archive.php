<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sydney
 */

get_header();

$sidebar_pos 	= sydney_sidebar_position();
?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
	
		<?php

		$current_category = get_queried_object();

		if ( mfca_is_language_category( $current_category ) ) {
			get_template_part( 'part-templates/archive', 'main-category' );
		} elseif ( mfca_parent_is_language_category( $current_category ) ) {
			get_template_part( 'part-templates/archive', 'subcategory' );
		} else {
			get_template_part( 'part-templates/archive', 'default' );
		}

		?>
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
