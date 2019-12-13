(function ($) {
    'use strict';

    $(document).ready(function () {
        var url_ajax = thim_theme_update.admin_ajax;
        var action = thim_theme_update.action;
        var i18l = thim_theme_update.i18l;

        $(document).on('click', '.tc-login-envato', function (e) {
            e.preventDefault();

            var $btn = $('.tc-registration-wrapper .activate-btn');
            if ($btn.length) {
                $btn.click();
            }
        });

        $(document).on('click', '.tc-button-deregister', function (e) {
            e.preventDefault();

            var confirm = window.confirm(i18l.confirm_deregister);
            if (!confirm) {
                return;
            }

            window.location.href = thim_theme_update.url_deregister;
        });

        $(document).on('click', '.tc-update-now', function () {
            var $button = $(this);
            var $notice = $button.closest('.notice');
            var $p = $notice.find('p');

            $notice.removeClass('update-message').addClass('updating-message');
            $p.text(i18l.updating);

            request_update_theme()
                .success(
                    function (response) {
                        var success = response.success || false;
                        var messages = response.data;

                        if (success) {
                            var version = response.data;
                            $('.tc-box-update-wrapper .version-number').text(version);
                            $('.tc-header .version').text(version);
                            $p.html(i18l.updated);

                            var $notification_count = $('#thim-core-count-notification');
                            var count = $notification_count.find('.plugin-count').text();
                            $notification_count.find('.plugin-count').text(parseInt(count) - 1);
                        } else if (messages.length && !success) {
                            var html = '';
                            messages.forEach(function (string) {
                                html += '<div>' + string + '</div>';
                            });

                            $p.html(html);
                        } else {
                            $p.html(i18l.wrong);
                        }

                        if (success) {
                            $notice.addClass('notice-success');
                        } else {
                            $notice.addClass('notice-error');
                        }
                    }
                )
                .error(
                    function (error) {
                        $p.html(i18l.wrong);
                        $notice.addClass('notice-error');
                    }
                )
                .complete(
                    function () {
                        window.onbeforeunload = null;
                        $notice.removeClass('updating-message').removeClass('notice-warning');
                    }
                );
        });

        function request_update_theme() {
            var nonce = thim_theme_update.nonce;

            window.onbeforeunload = function () {
                return i18l.warning_leave;
            };

            return $.ajax({
                url: url_ajax,
                method: 'POST',
                data: {
                    action: action,
                    nonce: nonce
                },
                dataType: 'json'
            });
        }
    });
})(jQuery);