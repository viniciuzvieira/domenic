'use strict';

(function ($) {
	$(document).ready(function () {
		// Membership Settings Admin
		var $wrap = $('#learn-press-pmpro-settings-admin'),
				$buyThoughMembership = $('#learn_press_buy_through_membership', $wrap),
				$btnBuyCourse = $('#learn_press_button_buy_course', $wrap);


		$buyThoughMembership.on('change', function () {
			console.log($btnBuyCourse.closest('tr'))

			if ($(this).prop('checked')) {
				$btnBuyCourse.closest('tr').addClass('hide-if-js');
			} else {
				$btnBuyCourse.closest('tr').removeClass('hide-if-js');
			}
		}).trigger('change');


		/**
		 * 
		 * @param {type} repo
		 * @returns {String}
		 */
		function formatCourse (repo) {
			if (repo.loading) {
				return repo.text;
			}
			var markup = "<div class='select2-result-course_title'>" + repo.ID + ' - ' + repo.post_title + "</div>";
			return markup;
		}

		/**
		 * 
		 * @param {type} repo
		 * @returns {unresolved}
		 */
		function formatCourseSelection (repo) {
			return repo.name || repo.text;
		}


		$(".lp-pmpro-courses-data-search-ajax").select2({
			ajax: {
				method: 'post',
				url: ajaxurl,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					var level_id = $(this).data('level_id');
					var ex = $(this).val();
					return {
						action: 'learn_press_pmpro_search_courses_action',
						level_id: level_id,
						q: params.term, // search term
						ex: ex,
						page: params.page,
						limit: params.limit
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
//			minimumInputLength: 1,
			templateResult: formatCourse, // omitted for brevity, see the source of this page
			templateSelection: formatCourseSelection // omitted for brevity, see the source of this page
		});

	});
})(jQuery)
