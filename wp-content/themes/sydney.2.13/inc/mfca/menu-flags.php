<?php
/**
 * Flag icons in navigation menus.
 *
 * Any menu item that points at a language category (see mfca_languages())
 * shows the matching images/flags/{code}.png instead of its label. The label
 * stays in the markup as screen-reader text.
 *
 * @package MFCA
 */

/**
 * Replace the label of menu items linking to a language category.
 *
 * @param string   $title Menu item title.
 * @param WP_Post  $item  Menu item.
 * @return string
 */
function mfca_menu_item_flag( $title, $item ) {
	if ( 'taxonomy' !== $item->type || 'category' !== $item->object ) {
		return $title;
	}

	$term = get_term( (int) $item->object_id, 'category' );

	if ( ! $term || is_wp_error( $term ) || ! mfca_is_language( $term->slug ) ) {
		return $title;
	}

	$file = "images/flags/{$term->slug}.png";

	if ( ! file_exists( get_theme_file_path( $file ) ) ) {
		return $title;
	}

	$flag = sprintf(
		'<img class="mfca-menu-flag" src="%s" alt="" width="24" height="16" loading="lazy">',
		esc_url( get_theme_file_uri( $file ) )
	);

	// Old menu titles may hold an <img>; keep the text only, falling back to the category name.
	$label = trim( wp_strip_all_tags( $title ) );
	if ( '' === $label ) {
		$label = $term->name;
	}

	return $flag . '<span class="screen-reader-text">' . esc_html( $label ) . '</span>';
}
add_filter( 'nav_menu_item_title', 'mfca_menu_item_flag', 10, 2 );

/**
 * Make sure every language shows up in the primary menu, in the order of
 * mfca_languages(), even if it was not added in Appearance > Menus.
 * Languages with a null name (no content yet) are left out.
 *
 * @param array    $items Sorted menu item objects.
 * @param stdClass $args  wp_nav_menu() arguments.
 * @return array
 */
function mfca_menu_all_languages( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$other   = array();
	$langs   = array();

	foreach ( $items as $item ) {
		$term = ( 'taxonomy' === $item->type && 'category' === $item->object && ! $item->menu_item_parent )
			? get_term( (int) $item->object_id, 'category' )
			: null;

		if ( $term && ! is_wp_error( $term ) && mfca_is_language( $term->slug ) ) {
			$langs[ $term->slug ] = $item;
		} else {
			$other[] = $item;
		}
	}

	$position = array_flip( array_keys( mfca_languages() ) );
	$next_id  = -1;

	foreach ( mfca_languages() as $code => $name ) {
		if ( isset( $langs[ $code ] ) || null === $name ) {
			continue;
		}

		$term = get_term_by( 'slug', $code, 'category' );
		if ( ! $term ) {
			continue;
		}

		$langs[ $code ] = (object) array(
			'ID'                => $next_id,
			'db_id'             => $next_id,
			'menu_item_parent'  => 0,
			'object_id'         => $term->term_id,
			'object'            => 'category',
			'type'              => 'taxonomy',
			'type_label'        => __( 'Category' ),
			'title'             => $term->name,
			'url'               => get_term_link( $term ),
			'target'            => '',
			'attr_title'        => '',
			'description'       => '',
			'classes'           => array( 'menu-item', 'menu-item-type-taxonomy', 'menu-item-object-category' ),
			'xfn'               => '',
			'current'           => false,
			'current_item_ancestor' => false,
			'current_item_parent'   => false,
		);
		--$next_id;
	}

	uksort(
		$langs,
		function ( $a, $b ) use ( $position ) {
			return $position[ $a ] - $position[ $b ];
		}
	);

	$items = array_merge( $other, array_values( $langs ) );

	foreach ( $items as $index => $item ) {
		$item->menu_order = $index + 1;
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'mfca_menu_all_languages', 10, 2 );
