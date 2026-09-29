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
