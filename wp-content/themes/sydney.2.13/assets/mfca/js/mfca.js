(function () {
	function prettifyBreadcrumbLabels() {
		var categories = ['book', 'audio', 'video', 'story'];
		var links = document.querySelectorAll('.breadcrumbs__link span');

		links.forEach(function (label) {
			var text = label.textContent.trim();

			if (categories.indexOf(text) !== -1) {
				label.textContent = text.charAt(0).toUpperCase() + text.slice(1);
			}
		});
	}

	function enableDragScroll() {
		document.querySelectorAll('.custom-slider').forEach(function (slider) {
			var isDown = false;
			var startX = 0;
			var scrollLeft = 0;

			slider.addEventListener('mousedown', function (event) {
				isDown = true;
				startX = event.pageX - slider.offsetLeft;
				scrollLeft = slider.scrollLeft;
				slider.classList.add('dragging');
			});

			slider.addEventListener('mouseleave', function () {
				isDown = false;
				slider.classList.remove('dragging');
			});

			slider.addEventListener('mouseup', function () {
				isDown = false;
				slider.classList.remove('dragging');
			});

			slider.addEventListener('mousemove', function (event) {
				var x;
				var walk;

				if (!isDown) {
					return;
				}

				event.preventDefault();
				x = event.pageX - slider.offsetLeft;
				walk = (x - startX) * 1.5;
				slider.scrollLeft = scrollLeft - walk;
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		prettifyBreadcrumbLabels();
		enableDragScroll();
	});
})();

