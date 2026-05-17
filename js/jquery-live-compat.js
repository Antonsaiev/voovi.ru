(function (window) {
	'use strict';

	function installLiveCompat($) {
		if (!$ || !$.fn || $.fn.live) {
			return;
		}

		$.fn.live = function (types, data, fn) {
			if (typeof data === 'function') {
				fn = data;
				data = undefined;
			}

			if (!this.selector) {
				return this.on(types, data, fn);
			}

			return $(document).on(types, this.selector, data, fn);
		};

		$.fn.die = function (types, fn) {
			if (!this.selector) {
				return this.off(types, fn);
			}

			return $(document).off(types, this.selector, fn);
		};
	}

	installLiveCompat(window.jQuery);

	try {
		var currentJQuery = window.jQuery;
		var currentDollar = window.$;

		Object.defineProperty(window, 'jQuery', {
			configurable: true,
			get: function () {
				return currentJQuery;
			},
			set: function (value) {
				currentJQuery = value;
				installLiveCompat(value);
			}
		});

		Object.defineProperty(window, '$', {
			configurable: true,
			get: function () {
				return currentDollar;
			},
			set: function (value) {
				currentDollar = value;
				installLiveCompat(value);
			}
		});
	} catch (error) {
		// Old browsers still get the initial patch above.
	}
}(window));
