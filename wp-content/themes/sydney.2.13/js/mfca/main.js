/**
 * MFCA front-end behaviour.
 *
 * URL scheme: /c/{lang}/{lang}-{type}/ lists a content type for a language,
 * e.g. /c/uz/uz-book/. Single posts live at /{lang}/{id}/.
 */
( function () {
	'use strict';

	var TYPES = [ 'book', 'audio', 'video', 'story' ];

	/** Content type ("book", "audio", ...) taken from the end of a category path. */
	function typeFromPath( pathname ) {
		var path = pathname.replace( /page\/\d+\//, '' );
		var last = path.slice( -5, -1 ); // "book", "udio", "ideo", "tory"
		return TYPES.filter( function ( type ) {
			return type.slice( -4 ) === last;
		} )[ 0 ] || null;
	}

	/** Language code: first segment of a single post URL, or the code after /c/ in archives. */
	function languageFromPath( pathname, isSingle ) {
		if ( isSingle ) {
			return pathname.split( '/' ).filter( Boolean )[ 0 ] || null;
		}
		var match = pathname.match( /^\/c\/([a-z]{2})/ );
		return match ? match[ 1 ] : null;
	}

	/** Point the Book / Audio / Video switcher at the current language. */
	function initTypeSwitcher() {
		var items = document.querySelectorAll( '.post-header__item' );
		if ( ! items.length ) {
			return;
		}

		var isSingle = document.body.classList.contains( 'single' );
		var pathname = window.location.pathname;
		var lang = languageFromPath( pathname, isSingle );
		var type = isSingle ? null : typeFromPath( pathname );

		if ( lang ) {
			items.forEach( function ( item ) {
				item.setAttribute( 'href', '/c/' + lang + '/' + lang + '-' + item.dataset.type );
			} );
		}

		if ( ! type ) {
			return;
		}

		var active = document.querySelector( '.post-header__item[data-type="' + type + '"]' );
		if ( active ) {
			active.classList.add( '_active' );
		}

		// Keep language links in the main menu on the same content type.
		document.querySelectorAll( '.menu-item-object-category > a' ).forEach( function ( link ) {
			link.href = link.href.slice( 0, -1 ) + '-' + type + '/';
		} );
	}

	/** Capitalise "book" / "audio" / ... in breadcrumbs. */
	function capitalizeBreadcrumbs() {
		document.querySelectorAll( '.breadcrumbs__link span' ).forEach( function ( span ) {
			var text = span.textContent;
			if ( TYPES.indexOf( text ) !== -1 ) {
				span.textContent = text.charAt( 0 ).toUpperCase() + text.slice( 1 );
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initTypeSwitcher();
		capitalizeBreadcrumbs();
	} );
}() );
