<?php
/**
 * Redirects.
 *
 * @package MFCA
 */

/**
 * Old category URLs (/category/...) live at /c/... now.
 */
function mfca_redirect_legacy_category_urls() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

	if ( 0 === strpos( $uri, '/category/' ) ) {
		wp_safe_redirect( '/c/' . substr( $uri, strlen( '/category/' ) ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mfca_redirect_legacy_category_urls' );
