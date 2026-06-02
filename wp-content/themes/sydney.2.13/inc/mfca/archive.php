<?php
/**
 * Archive helpers for language/category pages.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function mfca_is_language_category( $category = null ) {
	$category = $category ?: get_queried_object();

	return $category instanceof WP_Term
		&& 'category' === $category->taxonomy
		&& in_array( $category->slug, mfca_language_slugs(), true );
}

function mfca_parent_is_language_category( $category = null ) {
	$category = $category ?: get_queried_object();

	if ( ! $category instanceof WP_Term || ! $category->parent ) {
		return false;
	}

	$parent = get_category( $category->parent );

	return $parent instanceof WP_Term && mfca_is_language_category( $parent );
}

