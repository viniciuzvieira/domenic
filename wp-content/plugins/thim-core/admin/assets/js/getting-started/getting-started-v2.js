;'use strict';

(function ($, Thim_YT, Thim_Plugins_Queue) {
	function emitEvent(key_event, args) {
		$(window).trigger(key_event, args);
	}

	var Router = Backbone.Router.extend({

		routes: {
			''          : 'start',
			'step-:step': 'step'
		},

		start: function () {
			this.goToStep(0);
		},

		goToStep: function (step) {
			this.navigate('step-' + step, {trigger: true});
		},

		step: function (step) {
			emitEvent('thim_gs_change_step', step);
		},

		initialize: function () {
			Backbone.history.start();
		}
	});

	var Thim_GS_Model = Backbone.Model.extend({
		initialize: function () {
			console.log("Model Thim_GS_Model Initialize!");
		},
		defaults  : {
			isRunning: false
		}
	});

	var Thim_Getting_Started = Backbone.View.extend({
		el: '.thim-getting-started',

		router: false,

		model: null,

		base_url_ajax: thim_gs.url_ajax,

		steps: thim_gs.steps,

		videos: [],

		current_step: 0,

		events: {
			'click .tc-skip-step': 'onSkipStep',
			'click .tc-run-step' : 'onClickRunStep'
		},

		/**
		 * Init page getting started.
		 *
		 * @since 0.8.7
		 */
		initialize: function () {
			var self = this;

			/**
			 * On router emit event change step.
			 */
			$(window).on('thim_gs_change_step', function (e, args) {
				self.goToStep(args);
			});

			$(window).on('thim_gs_complete', function () {
				self._request('finish', null)
					.success(function (response) {
						console.log(response);
					});
			});

			/**
			 * On key up next/back step.
			 */
			$(window).on('keyup', function (e) {
				switch (e.keyCode) {
					case 37:
						self.backStep();
						break;
					case 39:
						self.nextStep();
						break;
					case 13:
						self.triggerClickRunCurrentStep();
						break;
				}
			});

			this.model = new Thim_GS_Model();
			this.model.on('change:isRunning', this.toggleButtonSkip.bind(this));

			this.router = new Router();
			this.render();

			Thim_Plugins_Queue.complete(function (data) {
				var action = data.action;
				var slug = data.slug;
				var $plugin = self.$('.thim-table-plugins tr[data-plugin="' + slug + '"]');

				if (action === 'activate') {
					$plugin.removeClass('processing');
				}

				var remain = Thim_Plugins_Queue.count();
				if (remain === 1) {
					self.$('.thim-table-plugins').removeClass('running');
					self.model.set('isRunning', false);
					self.nextStep();
				}
			});

			Thim_Plugins_Queue.success(function (response, data) {
				var action = data.action;
				if (action !== 'activate') {
					return;
				}

				var slug = data.slug;
				var $plugin = self.$('.thim-table-plugins tr[data-plugin="' + slug + '"]');

				if (!response || !response.success) {
					$plugin.removeClass('processing').addClass('failed');
					$plugin.find('.updating-message').text('You can try again later :)');
				} else {
					$plugin.removeClass('inactive processing').addClass('active').find('.thim-input').prop('checked', false);
					$plugin.find('.updating-message').text('Active');
				}
			});

			Thim_Plugins_Queue.error(function (data) {
				var action = data.action;

				if (action !== 'activate') {
					return;
				}

				var slug = data.slug;
				var $plugin = self.$('.thim-table-plugins tr[data-plugin="' + slug + '"]');

				$plugin.addClass('failed');
				$plugin.find('.updating-message').text('You can try again later :)');
			});
		},

		/**
		 * Render view.
		 *
		 * @since 0.8.7
		 */
		render: function () {
			this.renderVideos();
			this.updateHeightMain();
		},

		updateHeightMain: function () {
			var $steps = this.$('.tc-step');
			var maxHeight = 1;

			$steps.each(function () {
				var $step = $(this);
				var h = $step.height();
				if (h > maxHeight) {
					maxHeight = h;
				}
			});

			this.$('main').height(maxHeight);
		},

		renderVideos: function () {
			var self = this;

			this.$('.thim-video-youtube')
				.each(function () {
					var $video = $(this);
					var videoId = $video.attr('data-video');
					var player = Thim_YT.createPlayer({
						id: videoId
					});

					self.videos.push(player);
				});
		},

		pauseVideo: function () {
			this.videos.forEach(function (video) {
				if (video.pauseVideo) {
					video.pauseVideo();
				}
			});
		},

		onSkipStep: function (e) {
			e.preventDefault();
			this.nextStep();
		},

		toggleButtonSkip: function () {
			if (!this.model) {
				return;
			}

			if (this.model.get('isRunning')) {
				this.$('.tc-skip-step').hide();
			} else {
				this.$('.tc-skip-step').show();
			}
		},

		goToStep: function (step) {
			if (step >= this.steps.length) {
				step = 0;
			}

			this.current_step = parseInt(step);

			if (this.steps.length - 1 === this.current_step) {
				emitEvent('thim_gs_complete');
			}

			this.$('.tc-number-step .current').text(this.current_step + 1);

			if (this.current_step + 1 === this.steps.length) {
				this.$('.tc-skip-step').hide();
			} else {
				this.$('.tc-skip-step').show();
			}

			var $run_step = this.$('.tc-run-step');
			$run_step.removeClass('updating-message');
			$run_step.attr('disabled', false);

			this.hideAllStep();
			this.pauseVideo();
			var key = this.getKeyCurrentKeyStep();

			this.$('.tc-step.' + key).addClass('active');

			this.updateControls();
		},

		getCurrentStep: function () {
			var index = this.current_step;
			return this.steps[index];
		},

		getKeyCurrentKeyStep: function () {
			var step = this.getCurrentStep();

			return step['key'];
		},

		hideAllStep: function () {
			this.$('.tc-step').removeClass('active');
		},

		triggerClickRunCurrentStep: function () {
			var key_step = this.getKeyCurrentKeyStep();
			var $step = this.$('.tc-step.' + key_step);
			$step.find('.tc-run-step').click();
		},

		nextStep: function () {
			if (this.current_step === this.steps.length - 1) {
				return;
			}

			this.current_step++;
			this.router.goToStep(this.current_step);
		},

		backStep: function () {
			if (!this.current_step) {
				return;
			}

			this.current_step--;
			this.router.goToStep(this.current_step);
		},

		updateControls: function () {
			var $steps = this.$('.tc-controls .step');
			$steps.removeClass('active current');

			var current_step = this.current_step;

			$steps.each(function () {
				var $st = $(this);
				var p = parseInt($st.attr('data-position'));

				if (p <= current_step) {
					$st.addClass('active');
				}

				if (p === current_step) {
					$st.addClass('current');
					var key_step = $st.attr('data-step');
					$('.tc-step.' + key_step).addClass('active');
				}
			});
		},

		onClickRunStep: function (e) {
			if (this.current_step === this.steps.length - 1) {
				return;
			}

			var $btn = $(e.target);
			$btn.addClass('updating-message')
				.attr('disabled', true);

			if ($btn.is('a')) {
				window.location.href = $btn.attr('href');
				return;
			}

			var key_step = this.getKeyCurrentKeyStep();

			switch (key_step) {
				case 'quick-setup':
					this._request_quick_setup();
					break;

				case 'install-plugins':
					this._request_install_plugins();
					break;

				case 'updates':
					this._login_envato(e);
					break;

				default:
					this.nextStep();
					break;
			}
		},

		_login_envato: function (e) {
			var $submit = $(e.target);
			var $form = $submit.closest('form');
			$form.submit();
		},

		_request: function (step, data) {
			var url_request = this.base_url_ajax + step;

			return $.ajax({
				url     : url_request,
				method  : 'POST',
				data    : data,
				dataType: 'json'
			});
		},

		_request_quick_setup: function () {
			var $form = this.$('.quick-setup form');
			var data = $form.serialize();
			var self = this;
			self.model.set('isRunning', true);

			this._request('quick-setup', data)
				.success(function () {
					self.model.set('isRunning', false);
					self.nextStep();
				})
				.error(function (error) {
					console.error(error);
				});
		},

		_request_install_plugins: function () {
			// if (!Thim_Core.check_active()) {
			//     return;
			// }

			var self = this;

			this.model.set('isRunning', true);

			this.$('.thim-table-plugins').addClass('running');
			var $plugins = this.$('.thim-plugins input.thim-input:checked');

			if (!$plugins || !$plugins.length) {
				this.model.set('isRunning', false);
				this.$('.thim-table-plugins').removeClass('running');
				self.nextStep();
				return;
			}

			$plugins.each(function () {
				var $input = $(this);
				var slug = $input.val();
				var status = $input.attr('data-status');

				self.$('.thim-table-plugins tr[data-plugin="' + slug + '"]').addClass('processing');
				if (status === 'not_installed') {
					Thim_Plugins_Queue.push({action: 'install', slug: slug});
				}

				Thim_Plugins_Queue.push({action: 'activate', slug: slug});
			});
		}
	});


	$(document).ready(function () {
		new Thim_Getting_Started();
	});
})(jQuery, Thim_Video_Youtube, Thim_Plugins_Queue);