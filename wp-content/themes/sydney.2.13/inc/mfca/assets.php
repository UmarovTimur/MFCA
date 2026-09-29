<?php
/**
 * MFCA styles and scripts.
 *
 * @package MFCA
 */

/**
 * Enqueue MFCA assets. Loaded after the Sydney stylesheets so it can override them.
 */
function mfca_enqueue_assets() {
	$uri  = get_template_directory_uri();
	$path = get_template_directory();
	$deps = array( 'sydney-style-min', 'sydney-style' );

	$style = function ( $name ) use ( $uri, $path, $deps ) {
		$file = "/css/mfca/{$name}.css";
		wp_enqueue_style( "mfca-{$name}", $uri . $file, $deps, filemtime( $path . $file ) );
	};

	$style( 'common' );

	if ( is_single() ) {
		$style( 'single' );
	}

	if ( is_category() ) {
		$style( 'archive' );
	}

	if ( is_page_template( 'page-templates/home-page.php' ) ) {
		$style( 'home' );
	}

	wp_enqueue_script( 'mfca-main', $uri . '/js/mfca/main.js', array(), filemtime( $path . '/js/mfca/main.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'mfca_enqueue_assets', 20 );
