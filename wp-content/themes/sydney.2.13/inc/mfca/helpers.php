<?php
/**
 * Shared MFCA helpers.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function mfca_language_slugs() {
	return array( 'az', 'kz', 'ka', 'kg', 'ce', 'ru', 'tj', 'tk', 'uz', 'ug' );
}

function mfca_content_types() {
	return array(
		'book'  => array(
			'label' => 'Book',
			'image' => 'Book-main-during-mfca-3475982.png',
		),
		'audio' => array(
			'label' => 'Audio',
			'image' => 'audio-main-during-mfca-3475982.png',
		),
		'video' => array(
			'label' => 'Video',
			'image' => 'video-main-during-mfca-3475982.png',
		),
	);
}

function mfca_get_category_language_slug( $category = null ) {
	$category = $category ?: get_queried_object();

	if ( ! $category instanceof WP_Term || 'category' !== $category->taxonomy ) {
		return '';
	}

	$languages = mfca_language_slugs();

	if ( in_array( $category->slug, $languages, true ) ) {
		return $category->slug;
	}

	if ( $category->parent ) {
		$parent = get_category( $category->parent );
		if ( $parent instanceof WP_Term && in_array( $parent->slug, $languages, true ) ) {
			return $parent->slug;
		}
	}

	foreach ( $languages as $language ) {
		if ( 0 === strpos( $category->slug, $language . '-' ) ) {
			return $language;
		}
	}

	return '';
}

function mfca_get_current_language_slug() {
	$category_language = mfca_get_category_language_slug();

	if ( $category_language ) {
		return $category_language;
	}

	if ( is_singular() ) {
		$categories = get_the_category();
		foreach ( $categories as $category ) {
			$category_language = mfca_get_category_language_slug( $category );
			if ( $category_language ) {
				return $category_language;
			}
		}
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );
	$segments = array_values( array_filter( explode( '/', $path ) ) );

	foreach ( $segments as $segment ) {
		if ( in_array( $segment, mfca_language_slugs(), true ) ) {
			return $segment;
		}
	}

	return 'uz';
}

function mfca_get_current_content_type() {
	$slugs = array_keys( mfca_content_types() );

	if ( is_category() ) {
		$category = get_queried_object();
		if ( $category instanceof WP_Term ) {
			foreach ( $slugs as $type ) {
				if ( false !== strpos( $category->slug, '-' . $type ) || $category->slug === $type ) {
					return $type;
				}
			}
		}
	}

	if ( is_singular() ) {
		foreach ( get_the_category() as $category ) {
			foreach ( $slugs as $type ) {
				if ( false !== strpos( $category->slug, '-' . $type ) || $category->slug === $type ) {
					return $type;
				}
			}
		}
	}

	return '';
}

function mfca_content_type_url( $language, $type ) {
	return home_url( sprintf( '/c/%1$s/%1$s-%2$s/', $language, $type ) );
}

function local_translate( $text, $lang ) {
	$translations = array(
		'book'  => array(
			'uz' => 'Kitoblar',
		),
		'audio' => array(
			'ru' => 'Аудиокниги',
			'uz' => 'Audio Kitoblar',
		),
	);

	return isset( $translations[ $text ][ $lang ] ) ? $translations[ $text ][ $lang ] : $text;
}
