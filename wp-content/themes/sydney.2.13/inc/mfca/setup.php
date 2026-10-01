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
