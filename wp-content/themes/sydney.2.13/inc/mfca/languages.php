<?php
/**
 * Site languages and category label translations.
 *
 * Each language is a top-level category whose slug is the language code.
 *
 * @package MFCA
 */

/**
 * Language code => native name shown on the home page.
 * A null name keeps the language out of the home page grid.
 *
 * @return array<string, string|null>
 */
function mfca_languages() {
	return array(
		'az' => 'Azərbaycan',
		'kz' => 'Казах',
		'ka' => 'Қорақалпоқ',
		'kg' => 'Кыргыз',
		'ce' => null,
		'ru' => 'Русский',
		'tj' => 'Тоҷик',
		'tk' => 'Türkmen',
		'uz' => 'Ўзбек',
		'ug' => null,
	);
}

/**
 * Category slug of a top-level (language) category?
 *
 * @param string $slug Category slug.
 * @return bool
 */
function mfca_is_language( $slug ) {
	return array_key_exists( $slug, mfca_languages() );
}

/**
 * Translate a subcategory label for a given language; falls back to the text itself.
 *
 * @param string $text Label or slug (e.g. "book", "audio").
 * @param string $lang Language code.
 * @return string
 */
function mfca_translate( $text, $lang ) {
	static $translations = array(
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

/**
 * Language tags for a language category slug.
 *
 * Note the slugs are site-specific: "ka" is Karakalpak (not Georgian),
 * "kz" is Kazakh, "kg" Kyrgyz, "tj" Tajik.
 *
 * @param string $code Category slug.
 * @return array|null array( 'lang' => BCP 47 tag, 'og' => Open Graph locale ) or null.
 */
function mfca_language_tags( $code ) {
	static $tags = array(
		'az' => array( 'lang' => 'az', 'og' => 'az_AZ' ),
		'kz' => array( 'lang' => 'kk', 'og' => 'kk_KZ' ),
		'ka' => array( 'lang' => 'kaa', 'og' => 'kaa_UZ' ),
		'kg' => array( 'lang' => 'ky', 'og' => 'ky_KG' ),
		'ce' => array( 'lang' => 'ce', 'og' => 'ce_RU' ),
		'ru' => array( 'lang' => 'ru', 'og' => 'ru_RU' ),
		'tj' => array( 'lang' => 'tg', 'og' => 'tg_TJ' ),
		'tk' => array( 'lang' => 'tk', 'og' => 'tk_TM' ),
		'uz' => array( 'lang' => 'uz', 'og' => 'uz_UZ' ),
		'ug' => array( 'lang' => 'ug', 'og' => 'ug_CN' ),
	);

	return isset( $tags[ $code ] ) ? $tags[ $code ] : null;
}

/**
 * Language code (category slug) of the content being viewed, or null.
 *
 * A post or category belongs to the language whose top-level category it sits under.
 *
 * @return string|null
 */
function mfca_current_language() {
	static $cache = array();

	$key = is_singular() ? 'p' . get_queried_object_id() : ( is_category() ? 'c' . get_queried_object_id() : '' );
	if ( '' === $key ) {
		return null;
	}
	if ( array_key_exists( $key, $cache ) ) {
		return $cache[ $key ];
	}

	$term_ids = array();
	if ( is_singular() ) {
		$term_ids = wp_list_pluck( get_the_category(), 'term_id' );
	} else {
		$term_ids = array( get_queried_object_id() );
	}

	$found = null;
	foreach ( $term_ids as $term_id ) {
		$ancestors = array_reverse( get_ancestors( $term_id, 'category' ) );
		$root      = $ancestors ? get_category( $ancestors[0] ) : get_category( $term_id );
		if ( $root && ! is_wp_error( $root ) && mfca_is_language( $root->slug ) ) {
			$found = $root->slug;
			break;
		}
	}

	return $cache[ $key ] = $found;
}
