<?php
/**
 * Audiobook player.
 *
 * @package Sydney
 */

defined( 'ABSPATH' ) || exit;

function mfca_post_has_audio_category( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();

	foreach ( get_the_category( $post_id ) as $category ) {
		if ( false !== strpos( $category->slug, 'audio' ) ) {
			return true;
		}
	}

	return false;
}

function mfca_get_audiobook_tracks( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$tracks  = array();

	$tracklist = get_post_meta( $post_id, 'alb_tracklist', true );
	$seen_urls = array();

	if ( is_array( $tracklist ) ) {
		foreach ( $tracklist as $index => $track ) {
			$url = '';

			if ( ! empty( $track['stream_link'] ) ) {
				$url = $track['stream_link'];
			} elseif ( ! empty( $track['track_mp3'] ) && is_string( $track['track_mp3'] ) ) {
				$url = $track['track_mp3'];
			} elseif ( ! empty( $track['track_mp3_id'] ) ) {
				$url = wp_get_attachment_url( (int) $track['track_mp3_id'] );
			}

			if ( ! $url ) {
				continue;
			}

			$normalized_url = strtok( $url, '?' );
			if ( isset( $seen_urls[ $normalized_url ] ) ) {
				continue;
			}
			$seen_urls[ $normalized_url ] = true;

			$tracks[] = array(
				'title'  => ! empty( $track['stream_title'] ) ? $track['stream_title'] : sprintf( __( 'Глава %d', 'sydney' ), $index + 1 ),
				'artist' => ! empty( $track['artist_name'] ) ? $track['artist_name'] : '',
				'album'  => ! empty( $track['stream_album'] ) ? $track['stream_album'] : '',
				'url'    => esc_url_raw( $url ),
			);
		}
	}

	if ( empty( $tracks ) ) {
		$tracks = mfca_extract_audio_links_from_content( get_post_field( 'post_content', $post_id ) );
	}

	return $tracks;
}

function mfca_extract_audio_links_from_content( $content ) {
	if ( ! preg_match_all( '#https?://[^\s"\']+\.(?:mp3|m4a|ogg|wav)(?:\?[^\s"\']*)?#i', $content, $matches ) ) {
		return array();
	}

	$tracks = array();

	foreach ( array_unique( $matches[0] ) as $index => $url ) {
		$name     = basename( strtok( $url, '?' ) );
		$name     = preg_replace( '/\.(mp3|m4a|ogg|wav)$/i', '', $name );
		$name     = str_replace( array( '-', '_' ), ' ', $name );
		$tracks[] = array(
			'title'  => $name ? $name : sprintf( __( 'Глава %d', 'sydney' ), $index + 1 ),
			'artist' => '',
			'album'  => '',
			'url'    => esc_url_raw( $url ),
		);
	}

	return $tracks;
}

function mfca_render_audiobook_player( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$tracks  = mfca_get_audiobook_tracks( $post_id );

	if ( empty( $tracks ) ) {
		return '';
	}

	?>
	<section class="mfca-audiobook" data-audiobook-id="<?php echo esc_attr( $post_id ); ?>">
		<div class="mfca-audiobook__main">
			<div class="mfca-audiobook__body">
				<p class="mfca-audiobook__eyebrow"><?php esc_html_e( 'Аудиокнига', 'sydney' ); ?></p>
				<p class="mfca-audiobook__track-title" data-role="track-title"><?php echo esc_html( $tracks[0]['title'] ); ?></p>

				<audio preload="metadata" data-role="audio" src="<?php echo esc_url( $tracks[0]['url'] ); ?>"></audio>

				<div class="mfca-audiobook__seek">
					<span data-role="current-time">0:00</span>
					<input type="range" min="0" max="100" value="0" step="0.1" data-role="seek" aria-label="<?php esc_attr_e( 'Позиция воспроизведения', 'sydney' ); ?>">
					<span data-role="duration">0:00</span>
				</div>

				<div class="mfca-audiobook__controls">
					<button type="button" class="mfca-audiobook__button" data-action="previous" aria-label="<?php esc_attr_e( 'Предыдущая глава', 'sydney' ); ?>">‹‹</button>
					<button type="button" class="mfca-audiobook__button" data-action="rewind">-15</button>
					<button type="button" class="mfca-audiobook__play" data-action="toggle" aria-label="<?php esc_attr_e( 'Слушать', 'sydney' ); ?>">
						<svg class="mfca-audiobook__icon mfca-audiobook__icon--play" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>
						<svg class="mfca-audiobook__icon mfca-audiobook__icon--pause" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M6 5h4v14H6zm8 0h4v14h-4z"/></svg>
					</button>
					<button type="button" class="mfca-audiobook__button" data-action="forward">+30</button>
					<button type="button" class="mfca-audiobook__button" data-action="next" aria-label="<?php esc_attr_e( 'Следующая глава', 'sydney' ); ?>">››</button>
					<label class="mfca-audiobook__speed" title="<?php esc_attr_e( 'Скорость', 'sydney' ); ?>">
						<svg class="mfca-audiobook__icon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M12 4a10 10 0 0 0-8.66 15h2.3A8 8 0 1 1 18.36 19h2.3A10 10 0 0 0 12 4zm4.3 4.3-3.9 3.9a2 2 0 1 0 1.4 1.4l3.9-5.3z"/></svg>
						<select data-role="speed" aria-label="<?php esc_attr_e( 'Скорость', 'sydney' ); ?>">
							<option value="0.75">0.75x</option>
							<option value="1" selected>1x</option>
							<option value="1.25">1.25x</option>
							<option value="1.5">1.5x</option>
							<option value="2">2x</option>
						</select>
					</label>
				</div>
			</div>
		</div>

		<ol class="mfca-audiobook__playlist">
			<?php foreach ( $tracks as $index => $track ) : ?>
				<li>
					<button
						type="button"
						class="mfca-audiobook__chapter <?php echo esc_attr( 0 === $index ? 'is-active' : '' ); ?>"
						data-track-index="<?php echo esc_attr( $index ); ?>"
						data-track-url="<?php echo esc_url( $track['url'] ); ?>"
						data-track-title="<?php echo esc_attr( $track['title'] ); ?>"
					>
						<span class="mfca-audiobook__chapter-number"><?php echo esc_html( $index + 1 ); ?></span>
						<span class="mfca-audiobook__chapter-title"><?php echo esc_html( $track['title'] ); ?></span>
					</button>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php
}

function mfca_add_audiobook_player_to_content( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || ! mfca_post_has_audio_category() ) {
		return $content;
	}

	if ( empty( mfca_get_audiobook_tracks() ) ) {
		return $content;
	}

	$content = preg_replace( '#<!--\s*wp:sonaar/sonaar-block\b.*?/-->#s', '', $content );

	ob_start();
	mfca_render_audiobook_player();

	return $content . ob_get_clean();
}
add_filter( 'the_content', 'mfca_add_audiobook_player_to_content', 8 );
