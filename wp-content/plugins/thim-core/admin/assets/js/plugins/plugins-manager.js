/**
 * Called in tab plugins
 * @since 0.4.0
 */
(function ($, Thim_Plugins, Thim_Plugins_Queue, wp, Thim_Core) {
	$(document).ready(function () {
		thim_plugins.init();
	});

	function unescape_html(html) {
		return $('<textarea></textarea>').html(html).text();
	}

	var thim_plugins = {

		/**
		 * Init functions
		 *
		 * @since 0.4.0
		 */
		init: function () {
			this.filter_search();
			this.onEvent();
			this.count();
			this.initRouting();
		},

		initRouting: function () {
			var hash = window.location.hash;
			var $tab = $('.filter-links a[href="' + hash + '"]');
			if ($tab) {
				$tab.click();
			}
		},

		/**
		 * Count each type plugin.
		 *
		 * @since 1.0.0
		 */
		count: function () {
			var all = $('.list-plugins .plugin-card').length;
			var required = $('.list-plugins .plugin-card.required').length;
			var recommended = $('.list-plugins .plugin-card.recommended').length;
			var updates = $('.list-plugins .plugin-card.can-update').length;

			add_count('all', all);
			add_count('required', required);
			add_count('recommended', recommended);
			add_count('updates', updates);

			function add_count(type, number) {
				var $a = $('.filter-links .' + type + ' a');
				if (!$a) {
					return;
				}

				$a.find('span').text(number);
			}

			var $notification_count = $('#thim-core-count-notification');
			$notification_count.each(function (index, element) {
				element.className = element.className.replace(/count-\d+/, 'count-' + updates);
			});
			if (updates > 0) {
				$notification_count.find('.plugin-count').text(updates);
			} else {
				$notification_count.remove();
			}

			if (updates) {
				$('.filter-links .updates').addClass('active');
			} else {
				$('.filter-links .updates').removeClass('active');
			}
		},

		/**
		 * Filter & search plugins.
		 *
		 * @since 0.4.0
		 */
		filter_search: function () {
			var $wrapper = $('.thim-wrapper .plugin-tab');
			var qsRegex, buttonFilter;
			var $filter_links = $wrapper.find('.filter-links');
			var $plugin_filter = $wrapper.find('#plugin-filter');

			// init isotope
			var $grid = $plugin_filter.find('.list-plugins').isotope({
				filter: function () {
					var $this = $(this);
					var searchResult = qsRegex ? $this.find('.name').text().match(qsRegex) : true;
					var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
					return searchResult && buttonResult;
				}
			});

			// click on filter tab
			$filter_links.on('click', 'li', function () {
				$filter_links.find('li a').removeClass('current');
				$(this).find('a').addClass('current');
				buttonFilter = $(this).data('filter');
				$grid.isotope();
			});

			// use value of search field to filter
			var $quicksearch = $('.wp-filter-search').keyup(this.debounce(function () {
				qsRegex = new RegExp($quicksearch.val(), 'gi');
				$grid.isotope();
			}));


		},

		/**
		 * debounce so filtering doesn't happen every millisecond
		 *
		 * @since 0.4.0
		 *
		 * @param fn
		 * @param threshold
		 * @returns {debounced}
		 */
		debounce: function (fn, threshold) {
			var timeout;
			return function debounced() {
				if (timeout) {
					clearTimeout(timeout);
				}

				function delayed() {
					fn();
					timeout = null;
				}

				timeout = setTimeout(delayed, threshold || 100);
			}
		},

		onEvent: function () {
			var self = this;

			Thim_Plugins_Queue.success(
				function (response, args) {
					if (response.success) {
						var data = response.data;
						var action = data.action;
						var slug = data.slug;
						var status = data.status;
						var $plugin = $(document).find('.plugin-card.plugin-card-' + slug);
						var $button = $plugin.find('.plugin-action-buttons .button');

						$button.text(data.text);
						$button.attr('data-action', action);

						$plugin.removeClass('can-update');
						$plugin.attr('data-status', status);

						var info = data.info;
						$plugin.find('.data_Version').text(info.Version);

						self.count();

						if (args.action === 'update') {
							wp.updates.decrementCount('plugin');
						}
					} else {
						var arrMessages = response.data.messages;
						var messages = '';

						for (var i = 0; i < arrMessages.length; i++) {
							messages += unescape_html(arrMessages[i]) + "\n";
						}

						alert(messages);
					}
				}
			);

			Thim_Plugins_Queue.error(function (data, error) {
				var slug = data.slug;

				var $plugin = $(document).find('.plugin-card.plugin-card-' + slug);
				var $button = $plugin.find('.plugin-action-buttons .button');
				$button.text('Failed');
			});

			Thim_Plugins_Queue.complete(function (data) {
				var slug = data.slug;

				var $plugin = $(document).find('.plugin-card.plugin-card-' + slug);
				var $button = $plugin.find('.plugin-action-buttons .button');

				$button.attr('disabled', false);
				$button.removeClass('updating-message');
			});

			$(document).on('click', '.plugin-action-buttons button:not(.updating-message)', function (e) {
				e.preventDefault();
				var $self = $(this);
				var action = $self.attr('data-action');

				if (action === 'update') {
					if (!Thim_Core.check_active()) {
						return;
					}
				}

				$self.addClass('updating-message');
				$self.attr('disabled', true);

				var $plugin = $self.parents('.plugin-action-buttons');
				var slug = $plugin.data('slug');
				Thim_Plugins_Queue.push({action: action, slug: slug});
			});
		}
	}

})(jQuery, Thim_Plugins, Thim_Plugins_Queue, window.wp, window.Thim_Core);