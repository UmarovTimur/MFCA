<?php
/**
 * Post grid used under single posts.
 *
 * @package MFCA
 */

/**
 * Grid of posts of the same language and content type as the current post (excluding it).
 *
 * A book gets other books, a video other videos, an audiobook other audiobooks.
 * Posts without a type subcategory fall back to the whole language category.
 *
 * @param int $limit Max posts.
 * @return string HTML, empty when there is nothing to show.
 */
function mfca_related_posts_grid( $limit = 10 ) {
	$type_cat = null;
	$main_cat = null;

	foreach ( get_the_category() as $cat ) {
		if ( 0 === (int) $cat->category_parent ) {
			$main_cat = $cat;
		} elseif ( ! $type_cat && preg_match( '/-(book|audio|video|story)$/', $cat->slug ) ) {
			$type_cat = $cat;
		}
	}

	$term = $type_cat ? $type_cat : $main_cat;

	if ( ! $term ) {
		return '';
	}

	$posts = get_posts(
		array(
			'category__in'   => array( $term->term_id ),
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
