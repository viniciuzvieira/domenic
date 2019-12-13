(function ($, Thim_Core) {
    var Thim_Feedback_Model = Backbone.Model.extend({});

    var Thim_Feedback = Backbone.View.extend({
        el: '#tc-send-feedback',

        events: {
            'click .btn-send': 'onClickSend',
            'change .developer-access input': 'onChangeIncludeAccess'
        },

        model: null,

        onChangeIncludeAccess: function (e) {
            var $input = $(e.currentTarget);
            var value = $input.prop('checked') ? 'yes' : 'no';
            $input.val(value);
        },

        onClickSend: function (e) {
            this.$el.find('.btn-send').attr('disabled', true).addClass('updating-message');
            this.$el.find('.md-content').addClass('sending');

            this.send();
        },

        send: function () {
            var self = this;
            var url = this.model.get('ajax_url');
            var $form = this.$el.find('.info');
            var data = $form.serialize();

            return $.ajax({
                url: url,
                method: 'POST',
                data: data,
                dataType: 'json'
            })
                .complete(function () {
                    self.complete();
                })
                .success(function (response) {
                    if (response && response.success) {
                        self.success(response.data);
                        return;
                    }

                    self.failed(response.data || self.model.get('wrong'));
                })
                .error(function (error) {
                    self.failed(self.model.get('wrong'));
                });
        },

        success: function (message) {
            this.$el.find('.md-content').addClass('success');
            this.$el.find('.messages .content').text(message);
        },

        failed: function (message) {
            this.$el.find('.md-content').addClass('failed');
            this.$el.find('.messages .content').text(message);
        },

        complete: function () {
            this.$el.find('.md-content').removeClass('sending').addClass('complete');
            this.$el.find('.btn-send').removeClass('updating-message').attr('disabled', false);
        },

        initialize: function () {
            this.model = new Thim_Feedback_Model(thim_core_feedback);

            $(document).on('click', '.thim-core-open-send-feedback', function (e) {
                e.preventDefault();

                if (!Thim_Core.check_active()) {
                    return;
                }

                $(window).trigger('thim_core_trigger_open_modal', 'tc-send-feedback');
            });

            $(window).on('thim_modal_open', this.onOpenModal.bind(this));
        },

        onOpenModal: function (e, data) {
            if ('#tc-send-feedback' !== data) {
                return;
            }

            this.$el.find('.content textarea').focus();
        }
    });


    $(document).ready(function () {
        new Thim_Feedback();
    });
})(jQuery, window.Thim_Core);