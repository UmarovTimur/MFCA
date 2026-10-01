<?php
/**
 * SEO and GEO (generative engine optimisation).
 *
 * Meta description, canonical, Open Graph / Twitter cards, JSON-LD,
 * per-content <html lang>, robots rules and /llms.txt.
 *
 * Everything is skipped when a dedicated SEO plugin is active, so the
 * theme never duplicates its tags.
 *
 * @package MFCA
 */

/**
 * Is an SEO plugin handling meta tags?
 *
 * @return bool
 */
function mfca_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Plain-text description of the current page (<= 160 chars).
 *
 * @return string
 */
function mfca_meta_description() {
	$text = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$text = $post->post_excerpt ? $post->post_excerpt : strip_shortcodes( $post->post_content );
	} elseif ( is_category() || is_tag() ) {
		$text = term_description();
	}

	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $text ) ) );

	if ( '' === $text ) {
		$tagline = get_bloginfo( 'description' );
		$text    = ( is_category() || is_tag() ) ? single_term_title( '', false ) . ' — ' . $tagline : $tagline;
	}

	return wp_html_excerpt( $text, 160, '…' );
}

/**
 * Canonical URL for archives and the home page (core handles singular posts).
 *
 * @return string
 */
function mfca_archive_canonical() {
	return get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) );
}

/**
 * Image for social cards: featured image (incl. FIFU external URL), else site icon.
 *
 * @return string URL or empty string.
 */
function mfca_social_image() {
	if ( is_singular() ) {
		$id  = get_queried_object_id();
		$url = mfca_fifu_url( $id );
		if ( ! $url ) {
			$url = get_the_post_thumbnail_url( $id, 'large' );
		}
		if ( $url ) {
			return $url;
		}
	}

	return (string) get_site_icon_url( 512 );
}

/**
 * <meta> description, canonical, Open Graph and Twitter tags.
 */
function mfca_print_meta_tags() {
	if ( mfca_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}

	$description = mfca_meta_description();
	$title       = wp_get_document_title();
	$is_singular = is_singular();
	$url         = $is_singular ? get_permalink() : mfca_archive_canonical();
	$image       = mfca_social_image();
	$lang        = mfca_current_language();
	$tags        = $lang ? mfca_language_tags( $lang ) : null;
	$locale      = $tags ? $tags['og'] : get_locale();

	if ( $description ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $description ) );
	}

	if ( ! $is_singular ) {
		printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( $url ) );
	}

	$og = array(
		'og:type'        => is_singular( 'post' ) ? 'article' : 'website',
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => $description,
		'og:url'         => $url,
		'og:locale'      => $locale,
		'og:image'       => $image,
	);
	foreach ( $og as $property => $content ) {
		if ( $content ) {
			printf( "<meta property=\"%s\" content=\"%s\">\n", esc_attr( $property ), esc_attr( $content ) );
		}
	}

	if ( is_singular( 'post' ) ) {
		printf( "<meta property=\"article:published_time\" content=\"%s\">\n", esc_attr( get_post_time( 'c', true ) ) );
		printf( "<meta property=\"article:modified_time\" content=\"%s\">\n", esc_attr( get_post_modified_time( 'c', true ) ) );
	}

	printf( "<meta name=\"twitter:card\" content=\"%s\">\n", $image ? 'summary_large_image' : 'summary' );
}
add_action( 'wp_head', 'mfca_print_meta_tags', 1 );

/**
 * JSON-LD structured data.
 *
 * - Home: WebSite with sitelinks search box + Organization.
 * - Posts: Article. Breadcrumbs are already marked up by dimox_breadcrumbs().
 * - Categories: CollectionPage.
 */
function mfca_print_json_ld() {
	if ( mfca_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}

	$lang      = mfca_current_language();
	$tags      = $lang ? mfca_language_tags( $lang ) : null;
	$in_lang   = $tags ? $tags['lang'] : get_bloginfo( 'language' );
	$org       = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);
	$site_icon = get_site_icon_url( 512 );
	if ( $site_icon ) {
		$org['logo'] = $site_icon;
	}

	$graph = array();

	if ( is_front_page() ) {
		$graph[] = $org;
		$graph[] = array(
			'@type'           => 'WebSite',
			'@id'             => home_url( '/#website' ),
			'url'             => home_url( '/' ),
			'name'            => get_bloginfo( 'name' ),
			'description'     => get_bloginfo( 'description' ),
			'publisher'       => array( '@id' => $org['@id'] ),
			'inLanguage'      => array_values( array_filter( array_map( function ( $code ) {
				$t = mfca_language_tags( $code );
				return $t ? $t['lang'] : null;
			}, array_keys( mfca_languages() ) ) ) ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
	} elseif ( is_singular( 'post' ) ) {
		$article = array(
			'@type'            => 'Article',
			'@id'              => get_permalink() . '#article',
			'mainEntityOfPage' => get_permalink(),
			'headline'         => wp_html_excerpt( get_the_title(), 110 ),
			'description'      => mfca_meta_description(),
			'datePublished'    => get_post_time( 'c', true ),
			'dateModified'     => get_post_modified_time( 'c', true ),
			'inLanguage'       => $in_lang,
			'author'           => array( '@id' => $org['@id'] ),
			'publisher'        => $org,
		);
		$image   = mfca_social_image();
		if ( $image ) {
			$article['image'] = $image;
		}
		$graph[] = $article;
	} elseif ( is_category() ) {
		$graph[] = array(
			'@type'       => 'CollectionPage',
			'@id'         => mfca_archive_canonical() . '#collection',
			'url'         => mfca_archive_canonical(),
			'name'        => single_term_title( '', false ),
			'description' => mfca_meta_description(),
			'inLanguage'  => $in_lang,
			'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
		);
	}

	if ( ! $graph ) {
		return;
	}

	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
	// Keep "</script>" inside strings from ending the block.
	echo '<script type="application/ld+json">' . str_replace( '</', '<\/', $json ) . "</script>\n";
}
add_action( 'wp_head', 'mfca_print_json_ld', 2 );

/**
 * <html lang> follows the language of the content (e.g. Uzbek books get lang="uz").
 *
 * @param string $output Attributes from language_attributes().
 * @return string
 */
function mfca_html_lang( $output ) {
	$lang = mfca_current_language();
	$tags = $lang ? mfca_language_tags( $lang ) : null;

	if ( ! $tags || is_admin() ) {
		return $output;
	}

	return preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $tags['lang'] ) . '"', $output, 1 );
}
add_filter( 'language_attributes', 'mfca_html_lang' );

/**
 * Keep internal search results out of the index.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function mfca_robots( $robots ) {
	if ( mfca_seo_plugin_active() ) {
		return $robots;
	}

	if ( is_search() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['max-image-preview'] );
	}

	return $robots;
}
add_filter( 'wp_robots', 'mfca_robots' );

/**
 * robots.txt: keep crawlers out of internal search.
 * Applies to the virtual robots.txt only; a physical file on the server wins.
 *
 * @param string $output robots.txt content.
 * @return string
 */
function mfca_robots_txt( $output ) {
	return str_replace(
		"Disallow: /wp-admin/\n",
		"Disallow: /wp-admin/\nDisallow: /?s=\nDisallow: /search/\n",
		$output
	);
}
add_filter( 'robots_txt', 'mfca_robots_txt', 20 );

/**
 * Serve /llms.txt: a short markdown map of the site for AI assistants.
 */
function mfca_serve_llms_txt() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	$path    = untrailingslashit( (string) wp_parse_url( home_url(), PHP_URL_PATH ) ) . '/llms.txt';

	if ( $request !== $path ) {
		return;
	}

	$types = array(
		'book'  => 'Books',
		'audio' => 'Audiobooks',
		'video' => 'Videos',
	);

	$lines   = array();
	$lines[] = '# ' . get_bloginfo( 'name' );
	$lines[] = '';
	$description = get_bloginfo( 'description' );
	if ( $description ) {
		$lines[] = '> ' . $description;
		$lines[] = '';
	}
	$lines[] = 'A free library of books, audiobooks and videos in the languages of Central Asia and the Caucasus. Content is organised by language, then by type.';
	$lines[] = '';

	foreach ( mfca_languages() as $code => $name ) {
		$term = get_category_by_slug( $code );
		if ( ! $term ) {
			continue;
		}
		$label   = $name ? $name : strtoupper( $code );
		$lines[] = "## {$label}";
		$lines[] = '';
		$lines[] = '- [' . $label . ' — all](' . get_category_link( $term->term_id ) . ')';
		foreach ( $types as $type => $type_label ) {
			$sub = get_category_by_slug( "{$code}-{$type}" );
			if ( $sub && $sub->count ) {
				$lines[] = '- [' . $type_label . '](' . get_category_link( $sub->term_id ) . '): ' . (int) $sub->count . ' items';
			}
		}
		$lines[] = '';
	}

	$lines[] = '## Optional';
	$lines[] = '';
	$lines[] = '- [Sitemap](' . home_url( '/wp-sitemap.xml' ) . ')';

	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo implode( "\n", $lines ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	exit;
}
add_action( 'template_redirect', 'mfca_serve_llms_txt', 0 );
