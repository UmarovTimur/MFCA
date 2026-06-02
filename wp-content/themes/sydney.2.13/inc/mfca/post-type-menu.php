<?php
/**
 * Content type navigation used on posts and language archives.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function post_types_menu_header() {
	$language     = mfca_get_current_language_slug();
	$current_type = mfca_get_current_content_type();
	$image_base   = get_template_directory_uri() . '/images/main-rubrik-in-lang/';
	?>
	<header class="page-header mfca-type-nav-wrap">
		<nav class="post-header _container" aria-label="<?php esc_attr_e( 'Content types', 'sydney' ); ?>">
			<?php foreach ( mfca_content_types() as $type => $item ) : ?>
				<a
					id="<?php echo esc_attr( '_link-' . $type ); ?>"
					class="post-header__item <?php echo esc_attr( $current_type === $type ? '_active' : '' ); ?>"
					href="<?php echo esc_url( mfca_content_type_url( $language, $type ) ); ?>"
				>
					<span class="post-header__link">
						<span class="post-header__img">
							<img src="<?php echo esc_url( $image_base . $item['image'] ); ?>" alt="">
						</span>
						<span class="post-header__text"><?php echo esc_html( $item['label'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</nav>
	</header>
	<?php
}
