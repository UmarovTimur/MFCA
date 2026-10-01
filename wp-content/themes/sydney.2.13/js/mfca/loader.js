/**
 * MFCA loaders: spinner/shimmer behind images until they load,
 * and a progress bar while navigating between pages.
 */
( function () {
	'use strict';

	var SKIP = '.site-logo, .mfca-menu-flag, .mfca-audiobook img';

	function watchImage( img ) {
		if ( img.complete || img.matches( SKIP ) ) {
			return;
		}
		var holder = img.parentElement;
		if ( ! holder ) {
			return;
		}
		holder.classList.add( 'mfca-loading' );
		var done = function () {
			holder.classList.remove( 'mfca-loading' );
		};
		img.addEventListener( 'load', done, { once: true } );
		img.addEventListener( 'error', done, { once: true } );
	}

	function initImageLoaders() {
		var root = document.getElementById( 'content' ) || document.body;
		root.querySelectorAll( 'img' ).forEach( watchImage );
	}

	function initProgressBar() {
		var bar = document.createElement( 'div' );
		bar.className = 'mfca-progress';
		document.body.appendChild( bar );

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest && event.target.closest( 'a[href]' );
			if ( ! link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey ) {
				return;
			}
			if ( link.target === '_blank' || link.origin !== window.location.origin ) {
				return;
			}
			var href = link.getAttribute( 'href' );
			if ( ! href || href.charAt( 0 ) === '#' || link.hasAttribute( 'download' ) ) {
				return;
			}
			bar.classList.add( 'is-active' );
		} );

		// Back/forward cache restores the page with the bar still showing.
		window.addEventListener( 'pageshow', function () {
			bar.classList.remove( 'is-active' );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initImageLoaders();
		initProgressBar();
	} );
}() );
