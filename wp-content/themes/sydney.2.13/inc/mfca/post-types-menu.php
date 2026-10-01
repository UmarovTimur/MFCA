<?php
/**
 * Book / Audio / Video switcher shown above posts and category archives.
 *
 * Link targets and the active item are set by js/mfca.js from the current URL.
 *
 * @package MFCA
 */

/**
 * Content types: slug => array( label, icon ).
 *
 * @return array
 */
function mfca_content_types() {
	$icons = 'https://mfca.uzlatin.com/wp-content/uploads/2023/03/';

	return array(
		'book'  => array( 'Book', $icons . 'Book-main-during-mfca-3475982.png' ),
		'audio' => array( 'Audio', $icons . 'audio-main-during-mfca-3475982.png' ),
		'video' => array( 'Video', $icons . 'video-main-during-mfca-3475982.png' ),
	);
}

/**
 * Does the {lang}-{type} subcategory have any published posts?
 *
 * @param string $lang Language code.
 * @param string $type Content type slug.
 * @return bool
 */
function mfca_type_has_posts( $lang, $type ) {
	$term = get_term_by( 'slug', "{$lang}-{$type}", 'category' );

	return $term && $term->count > 0;
}

/**
 * Print the content type switcher. Types without posts in the current language are hidden.
 */
function mfca_post_types_menu() {
	$lang  = mfca_current_language();
	$types = mfca_content_types();

	if ( $lang ) {
		$types = array_filter(
			$types,
			function ( $type ) use ( $lang ) {
				return mfca_type_has_posts( $lang, $type );
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	if ( ! $types ) {
		return;
	}
	?>
	<header class="page-header">
		<div class="post-header _container">
			<?php foreach ( $types as $type => $item ) : ?>
				<?php $label = $lang ? mfca_translate( $type, $lang ) : $item[0]; ?>
				<a class="post-header__item" data-type="<?php echo esc_attr( $type ); ?>">
					<div class="post-header__link">
						<div class="post-header__img">
							<img src="<?php echo esc_url( $item[1] ); ?>" alt="<?php echo esc_attr( $label ); ?>">
						</div>
						<div class="post-header__text"><?php echo esc_html( $label ); ?></div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</header>
	<?php
}

/**
 * Is this a video category ({code}-video)? Video covers are landscape, not portrait.
 *
 * @param WP_Term|null $term Category; defaults to the queried object.
 * @return bool
 */
function mfca_is_video_category( $term = null ) {
	$term = $term ? $term : get_queried_object();

	return $term instanceof WP_Term && '-video' === substr( $term->slug, -6 );
}
