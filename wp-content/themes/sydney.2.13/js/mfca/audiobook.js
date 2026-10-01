/**
 * MFCA audiobook player (see inc/mfca/audiobook-player.php).
 */
( function () {
	'use strict';

	function formatTime(seconds) {
		var safeSeconds = Number.isFinite(seconds) ? Math.max(0, Math.floor(seconds)) : 0;
		var hours = Math.floor(safeSeconds / 3600);
		var minutes = Math.floor((safeSeconds % 3600) / 60);
		var secs = safeSeconds % 60;

		if (hours > 0) {
			return hours + ':' + String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
		}

		return minutes + ':' + String(secs).padStart(2, '0');
	}

	function setupAudiobookPlayer(player) {
		var audio = player.querySelector('[data-role="audio"]');
		var title = player.querySelector('[data-role="track-title"]');
		var playButton = player.querySelector('[data-action="toggle"]');
		var seek = player.querySelector('[data-role="seek"]');
		var currentTime = player.querySelector('[data-role="current-time"]');
		var duration = player.querySelector('[data-role="duration"]');
		var speed = player.querySelector('[data-role="speed"]');
		var chapters = Array.prototype.slice.call(player.querySelectorAll('.mfca-audiobook__chapter'));
		var storagePrefix = 'mfca-audiobook:' + (player.dataset.audiobookId || 'post') + ':';
		var activeIndex = 0;
		var seeking = false;

		if (!audio || !playButton || !chapters.length) {
			return;
		}

		function storageKey(index) {
			var chapter = chapters[index];
			return storagePrefix + index + ':' + (chapter ? chapter.dataset.trackUrl : '');
		}

		function saveProgress() {
			if (!audio.duration || audio.currentTime < 3) {
				return;
			}

			localStorage.setItem(storageKey(activeIndex), String(Math.floor(audio.currentTime)));
		}

		function restoreProgress() {
			var saved = parseFloat(localStorage.getItem(storageKey(activeIndex)) || '0');

			if (saved > 3) {
				audio.currentTime = saved;
			}
		}

		function setActiveChapter(index, autoplay) {
			var chapter = chapters[index];

			if (!chapter) {
				return;
			}

			saveProgress();
			activeIndex = index;
			audio.src = chapter.dataset.trackUrl;
			audio.playbackRate = parseFloat(speed.value || '1');
			title.textContent = chapter.dataset.trackTitle || chapter.textContent.trim();
			seek.value = 0;
			currentTime.textContent = '0:00';
			duration.textContent = '0:00';

			chapters.forEach(function (item) {
				item.classList.toggle('is-active', item === chapter);
			});

			audio.addEventListener('loadedmetadata', restoreProgress, { once: true });
			audio.load();

			if (autoplay) {
				audio.play();
			}
		}

		function updatePlayState() {
			playButton.classList.toggle('is-playing', !audio.paused);
			playButton.setAttribute('aria-label', audio.paused ? 'Слушать' : 'Пауза');
		}

		playButton.addEventListener('click', function () {
			if (audio.paused) {
				audio.play();
			} else {
				audio.pause();
			}
		});

		player.querySelector('[data-action="rewind"]').addEventListener('click', function () {
			audio.currentTime = Math.max(0, audio.currentTime - 15);
		});

		player.querySelector('[data-action="forward"]').addEventListener('click', function () {
			audio.currentTime = Math.min(audio.duration || audio.currentTime + 30, audio.currentTime + 30);
		});

		player.querySelector('[data-action="previous"]').addEventListener('click', function () {
			setActiveChapter(Math.max(0, activeIndex - 1), true);
		});

		player.querySelector('[data-action="next"]').addEventListener('click', function () {
			setActiveChapter(Math.min(chapters.length - 1, activeIndex + 1), true);
		});

		chapters.forEach(function (chapter, index) {
			chapter.addEventListener('click', function () {
				setActiveChapter(index, true);
			});
		});

		seek.addEventListener('input', function () {
			seeking = true;
			currentTime.textContent = formatTime((audio.duration || 0) * (parseFloat(seek.value) / 100));
		});

		seek.addEventListener('change', function () {
			audio.currentTime = (audio.duration || 0) * (parseFloat(seek.value) / 100);
			seeking = false;
		});

		speed.addEventListener('change', function () {
			audio.playbackRate = parseFloat(speed.value || '1');
			localStorage.setItem(storagePrefix + 'speed', speed.value);
		});

		audio.addEventListener('loadedmetadata', function () {
			duration.textContent = formatTime(audio.duration);
		});

		audio.addEventListener('timeupdate', function () {
			if (!seeking && audio.duration) {
				seek.value = String((audio.currentTime / audio.duration) * 100);
				currentTime.textContent = formatTime(audio.currentTime);
			}
		});

		audio.addEventListener('play', updatePlayState);
		audio.addEventListener('pause', function () {
			saveProgress();
			updatePlayState();
		});

		audio.addEventListener('ended', function () {
			localStorage.removeItem(storageKey(activeIndex));

			if (activeIndex < chapters.length - 1) {
				setActiveChapter(activeIndex + 1, true);
			} else {
				updatePlayState();
			}
		});

		window.addEventListener('beforeunload', saveProgress);

		speed.value = localStorage.getItem(storagePrefix + 'speed') || '1';
		audio.playbackRate = parseFloat(speed.value || '1');
		audio.addEventListener('loadedmetadata', restoreProgress, { once: true });
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.mfca-audiobook' ).forEach( setupAudiobookPlayer );
	} );
}() );
