<?php
/**
 * Reusable post card renderers.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function render_post_slider( $posts, $slider_id = 'customPostSlider' ) {
	if ( empty( $posts ) || ! is_array( $posts ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="custom-slider" id="<?php echo esc_attr( $slider_id ); ?>">
		<?php foreach ( $posts as $post ) : ?>
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="slider-card">
				<div class="slider-thumb">
					<?php echo get_the_post_thumbnail( $post, 'medium' ); ?>
				</div>
				<div class="slider-title"><?php echo esc_html( get_the_title( $post ) ); ?></div>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

function render_post_grid( $posts, $grid_id = 'customPostGrid' ) {
	if ( empty( $posts ) || ! is_array( $posts ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="custom-grid" id="<?php echo esc_attr( $grid_id ); ?>">
		<?php foreach ( $posts as $post ) : ?>
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="grid-card">
				<div class="grid-thumb">
					<?php echo get_the_post_thumbnail( $post, 'medium' ); ?>
				</div>
				<div class="grid-title"><?php echo esc_html( get_the_title( $post ) ); ?></div>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

function mfca_render_book_post_item() {
	?>
	<article class="post__book">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="post__book-image">
				<?php the_post_thumbnail(); ?>
			</a>
			<div class="post__book-body">
				<h3>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<?php the_excerpt(); ?>
			</div>
		<?php else : ?>
			<div class="post__book-body post__book-body--full">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>
	</article>
	<?php
}

function mfca_get_related_posts_for_current_post( $posts_per_page = 10 ) {
	$main_category = null;

	foreach ( get_the_category() as $category ) {
		if ( 0 === (int) $category->category_parent ) {
			$main_category = $category;
			break;
		}
	}

	if ( ! $main_category ) {
		return array();
	}

	return get_posts(
		array(
			'category__in'    => array( $main_category->term_id ),
			'post__not_in'    => array( get_the_ID() ),
			'posts_per_page'  => $posts_per_page,
			'suppress_filters' => false,
		)
	);
}

