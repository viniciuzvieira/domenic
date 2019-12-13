wp.customize.controlConstructor['kirki-accordion'] = wp.customize.Control.extend({

	//@thim_created
	ready: function () {
		'use strict';

		var control = this;
		var $ = jQuery;


		$(window).load(function () {
			closeAll(true);
		});

		control.container.on('click', '.thim-customize-accordion', function () {
			var $button = jQuery(this).children('.accordion');
			if ($button.hasClass('active')) {
				return closeAll(false);
			}

			closeAll(this);
			$button.addClass('active');

			var field = jQuery(this),
				fields = field.data('fields');
			for (var i = 0; i < fields.length; i++) {
				var value = fields[i];
				var $control = jQuery(value);
				if ($control) {
					if ($control.hasClass('init-hide')) {
						$control.removeClass('init-hide');
					}
					var control2 = wp.customize.control(value.replace('#customize-control-', ''));
					check_active_callback(control2, fields);
				}
			}

		});

		function check_active_callback(control2, fields) {
			for (var i = 0; i < fields.length; i++) {
				var value = fields[i];
				$.each(_wpCustomizeSettings.controls, function (k, _control) {
					if ('#customize-control-' + k != value) {
						return;
					}
					var findControl = wp.customize.control(k);
					if (findControl) {
						if (!$.isArray(_control.active_callback)) {
							findControl.container.show();
							return;
						}
						var show = window.thim_evaluate(control2, _control);
						if (show !== undefined) {
							findControl.container.toggle(show);
						}
					}
				});
			}
		}

		function closeAll(exclude) {
			var element_closeAll = jQuery('.thim-customize-accordion');
			if (undefined !== exclude || !exclude) {
				element_closeAll = element_closeAll.not(exclude);
			}

			element_closeAll.each(function () {
				jQuery(this).children('.accordion').removeClass('active');

				var fields = jQuery(this).data('fields');
				for (var i = 0; i < fields.length; i++) {
					var value = fields[i];
					var $control = jQuery(value);
					if ($control) {
						$control.slideUp(200, function () {
							jQuery(this).addClass('init-hide');
						});
					}
				}
			});
		}
	}
});