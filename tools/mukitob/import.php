<?php
/**
 * Create MFCA posts from data/books.json (see scrape.py).
 *
 * Run inside the WordPress container:
 *   docker compose cp tools/mukitob/data/books.json wordpress:/tmp/books.json
 *   docker compose cp tools/mukitob/import.php wordpress:/tmp/import.php
 *   docker compose exec -T wordpress php /tmp/import.php [--dry-run]
 *
 * - Posts mirror the existing ones: author, title, subtitle, annotation, file links,
 *   cover via FIFU (fifu_image_url), mp3 tracks via Sonaar's alb_tracklist.
 * - Files stay on mukitob.com (hotlinked); nothing heavy is downloaded.
 * - Books already imported (same cover URL, or the mukitob_id meta) are skipped.
 * - Missing language / type categories are created.
 */

define( 'WP_USE_THEMES', false );
require '/var/www/html/wp-load.php';

$dry = in_array( '--dry-run', $argv, true );

$books = json_decode( file_get_contents( '/tmp/books.json' ), true );
if ( ! $books ) {
	exit( "Cannot read /tmp/books.json\n" );
}

// mukitob language code => site category slug and name.
$langs = array(
	'az' => array( 'az', 'Azərbaycan' ),
	'ka' => array( 'ka', 'Қарақалпақ' ),
	'kg' => array( 'kg', 'Кыргыз' ),
	'kz' => array( 'kz', 'Казах' ),
	'tj' => array( 'tj', 'Тоҷик' ),
	'tm' => array( 'tk', 'Türkmen' ),
	'uz' => array( 'uz', 'Uzbek' ),
);

function mf_term( $slug, $name, $parent = 0, $dry = false ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( $term ) {
		return (int) $term->term_id;
	}
	echo "  + category {$slug}\n";
	if ( $dry ) {
		return 0;
	}
	$res = wp_insert_term( $name, 'category', array( 'slug' => $slug, 'parent' => $parent ) );
	return is_wp_error( $res ) ? 0 : (int) $res['term_id'];
}

global $wpdb;
$author = (int) $wpdb->get_var( "SELECT post_author FROM {$wpdb->posts} WHERE post_type='post' AND post_status='publish' ORDER BY ID DESC LIMIT 1" );

$cats = array();
foreach ( array_unique( array_column( $books, 'lang' ) ) as $l ) {
	list( $code, $name ) = $langs[ $l ];
	$root = mf_term( $code, $name, 0, $dry );
	$cats[ $l ] = array( 'root' => $root );
	foreach ( array( 'book', 'audio', 'video', 'story' ) as $type ) {
		$cats[ $l ][ $type ] = mf_term( "{$code}-{$type}", $type, $root, $dry );
	}
}

$created = 0;
$skipped = 0;

foreach ( $books as $b ) {
	$key   = $b['lang'] . '/' . $b['id'];
	$cover = $b['cover'] ? 'https://mukitob.com' . $b['cover'] : '';

	$exists = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE (meta_key='mukitob_id' AND meta_value=%s) OR (meta_key='fifu_image_url' AND meta_value=%s) LIMIT 1",
			$key,
			$cover
		)
	);
	if ( $exists ) {
		$skipped++;
		continue;
	}

	$content  = '';
	$author_t = trim( $b['author'] );
	if ( $author_t ) {
		$content .= "<!-- wp:paragraph -->\n<p><strong>" . esc_html( $author_t ) . "</strong></p>\n<!-- /wp:paragraph -->\n\n";
	}
	$content .= "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html( $b['title'] ) . "</h3>\n<!-- /wp:heading -->\n\n";
	if ( $b['about'] ) {
		$content .= "<!-- wp:paragraph -->\n<p><em>" . esc_html( $b['about'] ) . "</em></p>\n<!-- /wp:paragraph -->\n\n";
	}
	if ( $b['annotation'] ) {
		$content .= "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $b['annotation'] ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}
	if ( $b['files'] ) {
		$links = array();
		foreach ( $b['files'] as $fmt => $f ) {
			$links[] = '<a href="' . esc_url( $f['url'] ) . '">' . esc_html( strtoupper( $fmt ) ) . ( $f['size'] ? ' (' . esc_html( $f['size'] ) . ')' : '' ) . '</a>';
		}
		$content .= "<!-- wp:paragraph -->\n<p>" . implode( ' · ', $links ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}

	$tracklist = array();
	foreach ( $b['tracks'] as $t ) {
		$file = rawurldecode( basename( $t['url'] ) );
		$name = preg_replace( '/\.mp3$/i', '', $file );
		$tracklist[] = array(
			'FileOrStream'            => 'stream',
			'stream_link'             => $t['url'],
			'stream_title'            => $name,
			'stream_album'            => $b['title'],
			'post_audiopreview_promo' => 'disabled',
			'song_store_list'         => array( array( 'store-icon' => 'fas fa-download', 'store-link' => $t['url'] ) ),
		);
	}
	if ( $tracklist ) {
		$content .= "<!-- wp:sonaar/sonaar-block {\"player_layout\":\"skin_boxed_tracklist\",\"playlist_source\":\"from_current_post\",\"playlist_hide_artwork\":true} /-->\n";
	}

	$term_ids = array( $cats[ $b['lang'] ]['root'], $cats[ $b['lang'] ]['book'] );
	if ( $tracklist ) {
		$term_ids[] = $cats[ $b['lang'] ]['audio'];
	}

	echo "{$key} {$b['title']}" . ( $tracklist ? ' [audio x' . count( $tracklist ) . ']' : '' ) . "\n";
	if ( $dry ) {
		$created++;
		continue;
	}

	$meta = array( 'mukitob_id' => $key );
	if ( $cover ) {
		$meta['fifu_image_url'] = $cover;
	}
	if ( $tracklist ) {
		$meta['post_playlist_source'] = 'default';
		$meta['alb_tracklist']        = $tracklist;
	}

	$post_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_author'   => $author,
				'post_title'    => $b['title'],
				'post_content'  => $content,
				'post_category' => array_filter( $term_ids ),
				'meta_input'    => $meta,
			)
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		echo '  ERROR ' . $post_id->get_error_message() . "\n";
		continue;
	}
	if ( $cover && function_exists( 'fifu_dev_set_image' ) ) {
		fifu_dev_set_image( $post_id, $cover );
	}
	$created++;
}

echo ( $dry ? '[dry-run] ' : '' ) . "created: {$created}, skipped (already there): {$skipped}\n";
