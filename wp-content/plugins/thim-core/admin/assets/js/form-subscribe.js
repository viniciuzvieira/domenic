'use strict';

(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.thim-form-subscribe .tc-button', function (e) {
            var url_ajax = tc_form_subscribe.url_ajax;

            $.ajax({
                url: url_ajax,
                success: function (response) {
                    console.log(response);

                    $(document).find('.thim-form-subscribe').addClass('is-subscribed');
                }
            })
        });
    });
})(jQuery);