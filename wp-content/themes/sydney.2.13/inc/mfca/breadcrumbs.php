<?php
/**
 * Breadcrumbs.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function dimox_breadcrumbs() {
	if ( is_front_page() || is_home() ) {
		return;
	}

	$items = mfca_get_breadcrumb_items();

	if ( empty( $items ) ) {
		return;
	}

	$position = 1;
	?>
	<div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php if ( $index > 0 ) : ?>
				<span class="breadcrumbs__separator"> › </span>
			<?php endif; ?>

			<?php if ( ! empty( $item['url'] ) ) : ?>
				<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
					<a class="breadcrumbs__link" href="<?php echo esc_url( $item['url'] ); ?>" itemprop="item">
						<span itemprop="name"><?php echo esc_html( $item['label'] ); ?></span>
					</a>
					<meta itemprop="position" content="<?php echo esc_attr( $position ); ?>">
				</span>
			<?php else : ?>
				<span class="breadcrumbs__current"><?php echo esc_html( $item['label'] ); ?></span>
			<?php endif; ?>
			<?php $position++; ?>
		<?php endforeach; ?>
	</div>
	<?php
}

function mfca_get_breadcrumb_items() {
	if ( is_category() ) {
		return mfca_get_category_breadcrumb_items( get_queried_object() );
	}

	if ( is_single() && ! is_attachment() ) {
		return mfca_get_single_breadcrumb_items();
	}

	if ( is_page() ) {
		return mfca_get_page_breadcrumb_items();
	}

	if ( is_search() ) {
		return array(
			array(
				'label' => sprintf( __( 'Search Results for: %s', 'sydney' ), get_search_query() ),
				'url'   => '',
			),
		);
	}

	if ( is_404() ) {
		return array(
			array(
				'label' => __( 'Ошибка 404', 'sydney' ),
				'url'   => '',
			),
		);
	}

	if ( is_post_type_archive() ) {
		$post_type = get_queried_object();

		return array(
			array(
				'label' => $post_type ? $post_type->label : get_the_archive_title(),
				'url'   => '',
			),
		);
	}

	return array(
		array(
			'label' => get_the_archive_title(),
			'url'   => '',
		),
	);
}

function mfca_get_category_breadcrumb_items( $category ) {
	if ( ! $category instanceof WP_Term ) {
		return array();
	}

	$items = array();

	foreach ( array_reverse( get_ancestors( $category->term_id, 'category' ) ) as $parent_id ) {
		$items[] = array(
			'label' => get_cat_name( $parent_id ),
			'url'   => get_category_link( $parent_id ),
		);
	}

	$items[] = array(
		'label' => single_cat_title( '', false ),
		'url'   => '',
	);

	return $items;
}

function mfca_get_single_breadcrumb_items() {
	$items      = array();
	$categories = get_the_category();

	if ( ! empty( $categories ) ) {
		$category = $categories[0];
		foreach ( array_reverse( get_ancestors( $category->term_id, 'category' ) ) as $parent_id ) {
			$items[] = array(
				'label' => get_cat_name( $parent_id ),
				'url'   => get_category_link( $parent_id ),
			);
		}

		$items[] = array(
			'label' => $category->name,
			'url'   => get_category_link( $category->term_id ),
		);
	}

	$items[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);

	return $items;
}

function mfca_get_page_breadcrumb_items() {
	$items = array();

	foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $page_id ) {
		$items[] = array(
			'label' => get_the_title( $page_id ),
			'url'   => get_page_link( $page_id ),
		);
	}

	$items[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);

	return $items;
}

