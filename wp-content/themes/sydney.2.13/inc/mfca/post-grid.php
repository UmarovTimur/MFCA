<?php
/**
 * Post grid used under single posts.
 *
 * @package MFCA
 */

/**
 * Grid of posts from the same top-level category as the current post (excluding it).
 *
 * @param int $limit Max posts.
 * @return string HTML, empty when there is nothing to show.
 */
function mfca_related_posts_grid( $limit = 10 ) {
	$main_cat = null;
	foreach ( get_the_category() as $cat ) {
		if ( 0 === (int) $cat->category_parent ) {
			$main_cat = $cat;
			break;
		}
	}

	if ( ! $main_cat ) {
		return '';
	}

	$posts = get_posts(
		array(
			'category__in'   => array( $main_cat->term_id ),
			'post__not_in'   => array( get_the_ID() ),
			'posts_per_page' => $limit,
		)
	);

	return mfca_render_post_grid( $posts );
}

/**
 * Render posts as a grid of cover cards.
 *
 * @param WP_Post[] $posts Posts.
 * @return string HTML.
 */
function mfca_render_post_grid( $posts ) {
	if ( empty( $posts ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="custom-grid">
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
