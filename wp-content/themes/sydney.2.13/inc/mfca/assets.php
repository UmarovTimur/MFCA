<?php
/**
 * MFCA assets.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function mfca_enqueue_assets() {
	wp_enqueue_style(
		'mfca-theme',
		get_template_directory_uri() . '/assets/mfca/css/mfca.css',
		array( 'sydney-style' ),
		'20260602'
	);

	wp_enqueue_script(
		'mfca-theme',
		get_template_directory_uri() . '/assets/mfca/js/mfca.js',
		array(),
		'20260602',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mfca_enqueue_assets', 20 );

function add_post_formats() {
	add_theme_support( 'post-formats', array( 'audio', 'video', 'aside', 'image' ) );
}
add_action( 'after_setup_theme', 'add_post_formats', 20 );

function add_fifu_meta_to_rest() {
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
add_action( 'init', 'add_fifu_meta_to_rest' );

