(function ($) {
    function _parseJSON(data) {
        var m = data.match(/<!-- THIM_IMPORT_START -->(.*)<!-- THIM_IMPORT_END -->/);
        try {
            if (m) {
                data = $.parseJSON(m[1]);
            } else {
                data = $.parseJSON(data);
            }
        } catch (e) {
            data = false;
        }
        return data;
    }

    function emitEvent(key_event, args) {
        $(window).trigger(key_event, args);
    }

    var Importer = Backbone.Model.extend({
        initialize: function () {
        },
        defaults: {
            plugins_required: [],
            demo: null,
            packages: []
        }
    });

    var Thim_Importer = Backbone.Model.extend({
        initialize: function () {
        },
        defaults: {
            demos: []
        }
    });

    var Thim_Form_Import = Backbone.View.extend({
        el: '.tc-modal-importer',
        doing_import: false,
        current_request: null,

        model: {},

        events: {
            'click .close': 'close',
            'click #start-import': 'import',
            'click #retry-import': 'retry_import',
            'click .package:not(.disabled, .obligatory)': 'onChangePackage'
        },

        try_again_step: 0,

        try_again_step_max: 50,

        retry: 0,

        retry_max: 2,

        failed: false,

        message_error: false,

        template: function (data) {
            var id = this.$el.attr('data-template');

            return wp.template(id)(data);
        },

        /**
         * Init form import.
         *
         * @since 0.8.6
         */
        initialize: function () {
            this.model = new Importer(thim_importer_data);
            this.render();

            var self = this;
            $(document).on('click', '.tc-modal-importer .tc-open-modal', function (e) {
                self.close();
            });

            $(window).on('thim_core_open_modal', function (e, data) {
                if ('tc-send-feedback' !== data) {
                    return;
                }

                $(document).find('#tc-send-feedback .content textarea').text(self.message_error);
                $(document).find('#tc-send-feedback .developer-access input').trigger('click');
                self.close();
            });
        },

        /**
         * Update on change package.
         *
         * @since 0.8.6
         *
         * @param e event
         */
        onChangePackage: function (e) {
            if (this.doing_import) {
                return;
            }

            var $target = this.$(e.currentTarget);

            var $checkbox = $target.find('input');
            $checkbox.prop('checked', !$checkbox.prop('checked'));

            var _package = $target.data('package');
            this._checkRequired(_package);
        },

        /**
         *
         * Check package is dependent another package.
         *
         * @since 0.8.6
         *
         * @param pack
         * @private
         */
        _checkRequired: function (pack) {
            var $package = this.$('.package[data-required="' + pack + '"]');
            if ($package.length === 0) {
                return;
            }

            var sub_pack = $package.data('package');
            var $input = this.$('#importer-' + sub_pack);
            var is_checked = this.$('#importer-' + pack).prop('checked');
            $input.attr('disabled', !is_checked);
            $package.toggleClass('disabled');
        },

        /**
         * Open modal import demo.
         *
         * @since 0.8.6
         *
         * @param demo
         */
        open: function (demo) {
            this.$el.addClass('md-show');
            this.$('.main').scrollTop(0);

            var $thim_dashboard = $('.thim-dashboard');
            $thim_dashboard.addClass('thim-modal-open');

            this.model.set('demo', demo);
            this.update();
        },

        /**
         * Close modal import demo.
         *
         * @since 0.8.6
         */
        close: function () {
            if (this.doing_import) {
                var r = window.confirm(thim_importer_data.confirm_close);

                if (!r) {
                    return;
                }
            }

            this._finish();

            this.$el.removeClass('md-show');

            var $thim_dashboard = $('.thim-dashboard');
            $thim_dashboard.removeClass('thim-modal-open');

            this.reset();
        },

        /**
         * Reset view.
         *
         * @since 0.8.6
         */
        reset: function () {
            this.doing_import = false;
            this.$('input').attr('disabled', false);
            this.$('#start-import').attr('disabled', false);
            this.$('input').prop('checked', true);
            this.$('.package').removeClass('disabled').removeAttr('data-status').removeAttr('data-percentage');
            this.$el.removeClass('importing completed');
            this.$('.package-progress-bar').css('width', 0);
        },

        /**
         * Update view when choose demo.
         *
         * @since 0.8.6
         */
        update: function () {
            var demo = this.model.get('demo');
            this.$('.demo-name').text(demo.title);

            var revsliders = demo.revsliders || [];
            this.$('.package.revslider').removeClass('disabled');
            if (!revsliders.length) {
                this.$('.package.revslider').addClass('disabled');
            }

            this.updatePluginsRequired(demo);
        },

        /**
         * Handle event click start import.
         *
         * @since 0.8.6
         */
        import: function () {
            this._updatePackages();
            this.$('#start-import').attr('disabled', true);
            this.$el.addClass('importing');
            this.$('input').attr('disabled', true);
            this._startImport();
        },

        /**
         * Retry import.
         *
         * @since 1.2.0
         */
        retry_import: function () {
            this.reset();
            this.retry++;
            this.import();
        },

        /**
         * Update selected packages.
         *
         * @since 0.8.6
         *
         * @private
         */
        _updatePackages: function () {
            var packages = [];
            this.$('.package:not(.disabled) input:checked').each(function () {
                var pack = $(this).parents('.package').data('package');

                packages.push(pack);
            });

            this.model.set('packages', packages);

            if (packages.length === 0) {
                this.$('#start-import').attr('disabled', true);
            } else {
                this.$('#start-import').attr('disabled', false);
            }
        },

        /**
         * Start import.
         *
         * @since 0.8.6
         *
         * @private
         */
        _startImport: function () {
            this.doing_import = true;

            this._initImport();
        },

        /**
         * Initialize import.
         *
         * @since 0.8.6
         *
         * @private
         */
        _initImport: function () {
            this._lockWindow();
            var demo = this.model.get('demo');
            var packages = this.model.get('packages');
            var self = this;

            var retry = this.failed;

            this.current_request = $
                .ajax({
                    url: this.model.get('url_ajax'),
                    method: 'POST',
                    data: {
                        action: 'thim_importer',
                        demo: demo.key,
                        packages: packages,
                        retry: retry
                    },
                    dataType: 'text'//'json'
                })
                .success(function (response) {
                    response = self._parseJSON(response);
                    var success = response.success || false;
                    if (!success) {
                        console.error('Failed!');
                    }

                    var data = response.data;
                    var next_step = data.next;
                    if (!next_step) {
                        return self._notifySuccess();
                    }

                    self._stepByStep(next_step);
                })
                .error(function (error) {
                    return self._notifyErrorAjax(error);
                });
        },

        /**
         * Step by step.
         *
         * @since 0.8.6
         *
         * @param step
         * @private
         */
        _stepByStep: function (step) {
            this.$('.package.' + step).attr('data-status', 'running');
            this._scrollTo(step);
            var self = this;

            this.current_request = $
                .ajax({
                    url: this.model.get('url_ajax'),
                    method: 'POST',
                    dataType: 'text',//'json'
                    data: {
                        action: 'thim_importer',
                        time: (new Date()).getTime()
                    }
                })
                .success(function (response) {
                    response = self._parseJSON(response);
                    if (!response) {
                        self.try_again_step += 1;
                        if (self.try_again_step > self.try_again_step_max) {
                            return self._notifyError({
                                code: '#002',
                                title: 'Something went wrong!'
                            });
                        }

                        return self._stepByStep(step);
                    }

                    var success = response.success || false;
                    var data = response.data;
                    if (!success) {
                        self.try_again_step += 1;
                        var code = data.code || '';
                        if (self.try_again_step > self.try_again_step_max || code.includes('008_DOWNLOAD_FAILED')) {
                            return self._notifyError(data);
                        }

                        return self._stepByStep(step);
                    }

                    var done_step = data.done || false;
                    var $done_wrap;
                    if (done_step) {
                        $done_wrap = self.$('.package.' + done_step).attr('data-status', 'completed');
                        $done_wrap.attr({'data-percentage': '100%'}).find('.package-progress-bar').css('width', '100%');
                    } else {
                        if (data.ext) {
                            if ($.inArray(data.next, ['media', 'main_content', 'plugins']) !== -1) {
                                var percentage = data.ext.percentage || 2;
                                $done_wrap = self.$('.package.' + data.next).attr({'data-percentage': percentage + '%'});
                                $done_wrap.find('.package-progress-bar').css('width', percentage + '%');
                            }
                        }
                    }

                    var next_step = data.next;
                    if (!next_step) {
                        return self._notifySuccess();
                    }

                    /**
                     * Recursive
                     */
                    self._stepByStep(next_step);
                })
                .error(function (error) {
                    self.try_again_step += 1;
                    if (self.try_again_step > self.try_again_step_max) {
                        return self._notifyErrorAjax(error);
                    }

                    return self._stepByStep(step);
                });
        },

        /**
         * Scroll to package.
         *
         * @since 0.8.6
         *
         * @param step
         * @private
         */
        _scrollTo: function (step) {
            var container = this.$('.main');
            var target = $(".package." + step);

            container.stop().animate({
                scrollTop: target.offset().top - container.offset().top + container.scrollTop()
            }, 500);
        },

        /**
         *
         * Parse JSON from response.
         *
         * @since 0.8.6
         *
         * @param data
         * @returns {*}
         * @private
         */
        _parseJSON: function (data) {
            var m = data.match(/<!-- THIM_IMPORT_START -->(.*)<!-- THIM_IMPORT_END -->/);
            try {
                if (m) {
                    data = $.parseJSON(m[1]);
                } else {
                    data = $.parseJSON(data);
                }
            } catch (e) {
                data = false;
            }
            return data;
        },

        /**
         * Notify when ajax failed.
         *
         * @since 0.8.6
         *
         * @param error
         * @returns {*}
         * @private
         */
        _notifyErrorAjax: function (error) {
            var l18n = thim_importer_data.details_error;

            var details = {
                title: l18n.title
            };

            if (error.status === 200) {
                details.code = l18n.code.request;

                return this._notifyError(details);
            }

            if (error.status > 200) {
                this.failed = true;
                details.code = l18n.code.server;

                if (this.retry < this.retry_max) {
                    return this._notify_retry(l18n.try_again);
                }

                return this._notifyError(details);
            }

            return true;
        },

        _notify_retry: function (title) {
            var $detail_error = this.$('.wrapper-finish .details-error');
            $detail_error.find('.how-to').html(title).show();

            this.$('.wrapper-finish').removeClass('success').addClass('failed retry');
            this._finish();

            return true;
        },

        /**
         * Notify error.
         *
         * @since 0.8.6
         *
         * @param details
         * @returns {boolean}
         * @private
         */
        _notifyError: function (details) {
            this._emitEvent('thim_importer_failed');

            var $detail_error = this.$('.wrapper-finish .details-error');
            $detail_error.find('.get-support .error-code').text(details.code);
            $detail_error.find('h3').text(details.title);

            var how_to = details.how_to || false;
            if (!how_to) {
                $detail_error.find('.how-to').hide();
            } else {
                $detail_error.find('.how-to').html(how_to).show();
            }

            this.$('.wrapper-finish').removeClass('success retry').addClass('failed');
            this._finish();

            this.message_error = '[Import Demo Content] ' + details.title + ' with code ' + details.code + '.';

            return true;
        },

        /**
         * Notify successful.
         *
         * @since 0.8.6
         *
         * @returns {boolean}
         * @private
         */
        _notifySuccess: function () {
            this.failed = false;
            this._emitEvent('thim_importer_complete', this.model.get('demo'));
            this.$('.wrapper-finish').removeClass('failed').addClass('success');
            this._finish();

            return true;
        },

        /**
         * Finish import.
         *
         * @since 0.8.6
         *
         * @returns {boolean}
         * @private
         */
        _finish: function () {
            this._forceStop();

            return true;
        },

        /**
         * Force stop.
         *
         * @since 0.8.6
         *
         * @private
         */
        _forceStop: function () {
            this.$el.removeClass('importing').addClass('completed');

            this.doing_import = false;
            this._unlockWindow();
            if (this.current_request) {
                this.current_request.abort();
            }
        },

        /**
         * Lock window.
         *
         * @since 0.8.6
         *
         * @private
         */
        _lockWindow: function () {
            window.onbeforeunload = function () {
                return 'The import process will cause errors if you leave this page!';
            };
        },

        /**
         * Unlock window.
         *
         * @since 0.8.6
         *
         * @private
         */
        _unlockWindow: function () {
            window.onbeforeunload = null;
        },

        /**
         * Emit event.
         *
         * @since 0.8.6
         *
         * @param key_event
         * @param args
         * @private
         */
        _emitEvent: function (key_event, args) {
            $(window).trigger(key_event, args);
        },

        updatePluginsRequired: function () {

        },

        /**
         * Render view.
         *
         * @since 0.8.6
         *
         * @returns {Thim_Form_Import}
         */
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));

            return this;
        }
    });

    var Thim_Form_Uninstall = Backbone.View.extend({
        el: '.tc-modal-importer-uninstall',

        model: {},

        current_request: false,

        doing_uninstall: false,

        events: {
            'click .close': 'close',
            'click .tc-start': 'startUninstall'
        },

        template: function (data) {
            var id = this.$el.attr('data-template');

            return wp.template(id)(data);
        },

        initialize: function () {
            this.model = new Importer(thim_importer_data);
            this.render();
        },

        /**
         * Open modal uninstall demo.
         *
         * @since 0.8.6
         */
        open: function () {
            this.$el.addClass('md-show');
            this.$('.main').scrollTop(0);

            this.$('.tc-start')
                .removeClass('updating-message')
                .attr('disabled', false);

            var $thim_dashboard = $('.thim-dashboard');
            $thim_dashboard.addClass('thim-modal-open');
        },

        /**
         * Close modal uninstall demo.
         *
         * @since 0.8.6
         */
        close: function () {
            if (this.doing_uninstall) {
                var r = window.confirm(thim_importer_data.confirm_close);

                if (!r) {
                    return;
                }
            }

            this.$el.removeClass('md-show');

            var $thim_dashboard = $('.thim-dashboard');
            $thim_dashboard.removeClass('thim-modal-open');

            this.reset();
        },

        startUninstall: function () {
            this.$('.tc-start')
                .addClass('updating-message')
                .attr('disabled', true);

            var self = this;
            self.$el.addClass('running');

            this.current_request = $.ajax({
                url: this.model.get('url_ajax'),
                method: 'POST',
                dataType: 'text',
                data: {
                    action: 'thim_importer_uninstall'
                }
            })
                .success(function (response) {
                    response = _parseJSON(response);

                    if (response.success) {
                        self.notify('success', thim_importer_data.uninstall_successful);
                        emitEvent('thim_uninstall_demo_successful');
                    } else {
                        self.notify('error', thim_importer_data.uninstall_failed);
                        emitEvent('thim_uninstall_demo_failed');
                    }
                })
                .error(function (error) {
                    if (error.statusText === 'abort') {
                        return;
                    }

                    self.$('.tc-start').attr('disabled', false);
                    self.notify('error', thim_importer_data.something_went_wrong);
                    emitEvent('thim_uninstall_demo_failed');
                })
                .complete(function () {
                    self.$('.tc-start')
                        .removeClass('updating-message');
                    self.$el.removeClass('running');
                    emitEvent('thim_uninstall_demo_complete');
                });
        },

        notify: function (type, content) {
            switch (type) {
                case 'error':
                    this.$('.tc-success').hide();
                    this.$('.tc-error').show().find('.content').text(content);
                    break;

                default:
                    this.$('.tc-error').hide();
                    this.$('.tc-success').show().find('.content').text(content);

                    break;
            }
        },

        reset: function () {
            this.$('.tc-start').attr('disabled', false);
            this.$('.notifications > *').hide();
        },

        render: function () {
            this.$el.html(this.template({}));
        }
    });

    var Thim_Importer_V2 = Backbone.View.extend({
        el: '.tc-importer-wrapper',
        form_install: null,
        form_uninstall: null,

        model: null,

        audio_complete: null,

        events: {
            'click .action-import': 'openInstall',
            'click .thim-screenshot': 'openInstall',
            'click .btn-uninstall': 'openUninstall'
        },

        initialize: function () {
            this.audio_complete = new Audio('https://thimpresswp.github.io/thim-core/static/complete.mp3');

            $(window).on('thim_importer_complete', this.onImportSuccess.bind(this));
            $(window).on('thim_uninstall_demo_successful', this.onUninstallSuccess.bind(this));
            this.model.on('change:installed', this.updateDemoInstalled.bind(this));
            this.render();
            this.form_install = new Thim_Form_Import();
            this.form_uninstall = new Thim_Form_Uninstall();
        },

        template: function (data) {
            var id = this.$el.attr('data-template');

            return wp.template(id)(data);
        },

        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
        },

        onImportSuccess: function (e, args) {
            this.audio_complete.play();
            this.model.set('installed', args.key);
        },

        onUninstallSuccess: function (e) {
            this.model.set('installed', false);
            this.render();
        },

        updateDemoInstalled: function () {
            var installed = this.model.get('installed');
            this.$('.thim-demo').removeClass('installed active');

            this.render();
        },

        openUninstall: function (e) {
            this.form_uninstall.open();
        },

        openInstall: function (e) {
            e.preventDefault();

            // if (!Thim_Core.check_active()) {
            //     return;
            // }

            var target = e.target;
            var $thim_demo = $(target).closest('.thim-demo');
            var demo_key = $thim_demo.data('thim-demo');
            var demos = this.model.get('demos');
            this.form_install.open(demos[demo_key]);
        }
    });


    $(document).ready(function () {
        new Thim_Importer_V2({
            model: new Thim_Importer(thim_importer)
        });
    });
})(jQuery);