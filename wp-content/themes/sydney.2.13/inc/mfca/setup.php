<?php
/**
 * Theme setup specific to MFCA.
 *
 * @package MFCA
 */

/**
 * Post formats used to tell books, audio and video apart.
 */
function mfca_post_formats() {
	add_theme_support( 'post-formats', array( 'audio', 'video', 'aside', 'image' ) );
}
add_action( 'after_setup_theme', 'mfca_post_formats', 20 );

/**
 * Expose the FIFU (Featured Image From URL) meta in the REST API.
 */
function mfca_register_fifu_meta() {
	register_post_meta(
		'post',
		'fifu_image_url',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'mfca_register_fifu_meta' );

/**
 * Hide comments on the front end (existing comments stay in the database).
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'get_comments_number', '__return_zero', 20 );
add_filter( 'comments_array', '__return_empty_array', 20 );

/**
 * External cover URL (FIFU meta) of a post.
 *
 * Posts brought in by a WordPress import carry the fifu_image_url meta but no FIFU
 * attachment, so FIFU/WordPress would show the site's default image instead of the cover.
 *
 * @param int $post_id Post ID.
 * @return string URL or empty string.
 */
function mfca_fifu_url( $post_id ) {
	return (string) get_post_meta( $post_id, 'fifu_image_url', true );
}

/**
 * Show the external cover (FIFU meta) instead of whatever image is attached.
 *
 * @param string       $html    Thumbnail markup.
 * @param int          $post_id Post ID.
 * @param int          $thumb   Thumbnail ID.
 * @param string|int[] $size    Image size.
 * @param string|array $attr    Image attributes.
 * @return string
 */
function mfca_fifu_thumbnail_html( $html, $post_id, $thumb, $size, $attr ) {
	$url = mfca_fifu_url( $post_id );

	if ( ! $url ) {
		return $html;
	}

	$attr = wp_parse_args(
		$attr,
		array(
			'class'    => 'attachment-post-thumbnail size-post-thumbnail wp-post-image',
			'alt'      => get_the_title( $post_id ),
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);

	$out = '<img src="' . esc_url( $url ) . '"';
	foreach ( array( 'class', 'alt', 'loading', 'decoding' ) as $name ) {
		$out .= ' ' . $name . '="' . esc_attr( $attr[ $name ] ) . '"';
	}

	return $out . '>';
}
add_filter( 'post_thumbnail_html', 'mfca_fifu_thumbnail_html', 20, 5 );
