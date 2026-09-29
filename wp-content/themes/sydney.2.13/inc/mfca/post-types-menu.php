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
 * Print the content type switcher.
 */
function mfca_post_types_menu() {
	?>
	<header class="page-header">
		<div class="post-header _container">
			<?php foreach ( mfca_content_types() as $type => $item ) : ?>
				<a class="post-header__item" data-type="<?php echo esc_attr( $type ); ?>">
					<div class="post-header__link">
						<div class="post-header__img">
							<img src="<?php echo esc_url( $item[1] ); ?>" alt="">
						</div>
						<div class="post-header__text"><?php echo esc_html( $item[0] ); ?></div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</header>
	<?php
}
