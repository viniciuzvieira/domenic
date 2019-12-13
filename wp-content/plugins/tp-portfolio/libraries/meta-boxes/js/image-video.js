jQuery(function ($) {
    portfolioToggle();

    /**
     * Show, hide a type of portfolio
     *
     * @return void
     * @since 1.0
     */
    function portfolioToggle() {
        var $pBox = $('div[class*="portfolio_type_"]').hide();
        $("#selectPortfolio").on('change', function () {
            $pBox.hide();
            $('.' + $(this).val()).show();
        }).trigger('change');
    }

    /**
     * Custom metabox
     *
     *
     *
     */
    dialog = $("#dialog-k").dialog({
        autoOpen: false,
        height: 'auto',
        width: 'auto',
        modal: true
        , 'buttons': {
            "Add": function () {
                ids = $('#thim-video-data-k').val();
                type = $('#thim-video-type-k').val();

                var $uploadButton = $(".thim-video-advanced-upload-k"),
                    $imageList = $uploadButton.siblings('.thim-images-video');
                if (ids.length > 0) {

                    if (type == "vimeo") {
                        xids = ["v." + ids];
                        datav = '<iframe src="http://player.vimeo.com/video/' + ids + '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="150" height="150" frameborder="0"></iframe>';
                    } else {
                        xids = ["y." + ids];
                        datav = '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="150" height="150" src="http://www.youtube.com/embed/' + ids + '" frameborder="0"></iframe>';
                    }

                    if (!portfolio_add) {
                        var data = {
                            action: 'thim_edit_media_k',
                            post_id: $('#post_ID').val(),
                            field_id: $imageList.data('field_id'),
                            attachment_old: edit_data,
                            attachment_ids: xids
                            , _ajax_nonce: $uploadButton.data('attach_media_nonce')
                        };
                    } else {
                        var data = {
                            action: 'thim_attach_image_video',
                            post_id: $('#post_ID').val(),
                            field_id: $imageList.data('field_id'),
                            attachment_ids: xids
                            , _ajax_nonce: $uploadButton.data('attach_media_nonce')
                        };
                    }

                    $.post(ajaxurl, data, function (r) {
                        if (r.success) {
                            if (!portfolio_add) {
                                edit_li.empty();
                                edit_li.append('<li id="item_' + xids + '">' + datav +
                                    '<div class="thim-image-bar">' +
                                    '<a title="Edit" class="thim-edit-file-k" href="#" target="_blank">Edit</a> |' +
                                    '<a title="Delete" class="thim-delete-file-k" href="#" data-attachment_id="' + xids + '">×</a>' +
                                    '</div> </li>');
                            } else {
                                $imageList.removeClass('hidden').append('<li id="item_' + xids + '">' + datav +
                                    '<div class="thim-image-bar">' +
                                    '<a title="Edit" class="thim-edit-file-k" href="#" target="_blank">Edit</a> |' +
                                    '<a title="Delete" class="thim-delete-file-k" href="#" data-attachment_id="' + xids + '">×</a>' +
                                    '</div> </li>');
                            }
                            // Reorder images
                        }
                    }, 'json');
                }

                $('#thim-video-data-k').val('');
                $(this).dialog('close');
            },
            "Close": function () {
                $(this).dialog('close');
            }
        },
        dialogClass: 'portfolio-dialog'
    });

    var portfolio_add = false;
    var edit_li;
    var edit_data;
    $('body').on('click', '.thim-edit-file-k', function (e) {
        e.preventDefault();
        edit_li = $(this).parents('li').eq(0);
        var e_data = edit_li.attr('id');
        edit_data = e_data;
        edit_data = edit_data.replace("item_", '');

        if (e_data.substring(0, 6) == "item_y") {
            e_data = e_data.replace("item_y.", '');
            $("#thim-video-type-k").val("youtube");
        } else {//item_v
            e_data = e_data.replace("item_v.", '');
            $("#thim-video-type-k").val("vimeo");
        }

        $("#thim-video-data-k").val(e_data);


        $('.portfolio-dialog .ui-button-text:contains(Add)').text('Save');
        portfolio_add = false;
        dialog.dialog("open");
    });

    $('body').on('click', '.thim-video-advanced-upload-k', function (e) {
        $('.portfolio-dialog .ui-button-text:contains(Save)').text('Add');
        $("#thim-video-type-k").val("youtube");
        $("#thim-video-data-k").val("");
        portfolio_add = true;
        dialog.dialog("open");
    });
    var frame;
    $('body').on('click', '.thim-image-video-advanced-upload', function (e) {
        e.preventDefault();
        var $uploadButton = $(this),
            $imageList = $uploadButton.siblings('.thim-images-video'),
            maxFileUploads = $imageList.data('max_file_uploads'),
            msg = maxFileUploads > 1 ? 'You may only upload maximum' : 'You may only upload maximum';

        msg = msg.replace('%d', maxFileUploads);

        // Create a frame only if needed
        if (!frame) {
            frame = wp.media({
                className: 'media-frame thim-media-frame',
                multiple: true,
                title: 'Select Image',
                library: {
                    type: 'image'
                }
            });
        }

        // Open media uploader
        frame.open();

        // Remove all attached 'select' event
        frame.off('select');

        // Handle selection
        frame.on('select', function () {
            // Get selections
            var selection = frame.state().get('selection').toJSON(),
                uploaded = $imageList.children().length,
                ids;

            if (maxFileUploads > 0 && (uploaded + selection.length) > maxFileUploads) {
                if (uploaded < maxFileUploads)
                    selection = selection.slice(0, maxFileUploads - uploaded);
                alert(msg);
            }

            // Get only files that haven't been added to the list
            // Also prevent duplication when send ajax request
            // selection = _.filter(selection, function (attachment) {
            //     return $imageList.children('li#item_' + attachment.id).length == 0;
            // });
            ids = _.pluck(selection, 'id');

            console.log(ids);

            if (ids.length > 0) {
                var data = {
                    action: 'thim_attach_image_video',
                    post_id: $('#post_ID').val(),
                    field_id: $imageList.data('field_id'),
                    attachment_ids: ids,
                    _ajax_nonce: $uploadButton.data('attach_media_nonce')
                };

                $.post(ajaxurl, data, function (r) {
                    if (r.success) {
                        $imageList.append(r.data);
                    }
                }, 'json');
            }
        });
    });

    // Reorder images
    $('.thim-images-video').each(function () {
        var $this = $(this),
            data = {
                action: 'thim_reorder_image_video',
                _ajax_nonce: $this.data('reorder_nonce'),
                post_id: $('#post_ID').val(),
                field_id: $this.data('field_id')
            };
        $this.sortable({
            placeholder: 'ui-state-highlight',
            items: 'li',
            update: function () {
                data.order = $this.sortable('serialize');
                $.post(ajaxurl, data);
            }
        });
    });

    // Delete file via Ajax
    $('.thim-image-video-uploaded').on('click', '.thim-delete-file-k', function () {
        var $this = $(this),
            $parent = $this.parents('li'),
            $container = $this.closest('.thim-image-video-uploaded'),
            data = {
                action: 'thim_delete_image_video',
                _ajax_nonce: $container.data('delete_nonce'),
                post_id: $('#post_ID').val(),
                field_id: $container.data('field_id'),
                attachment_id: $this.data('attachment_id'),
                force_delete: $container.data('force_delete')
            };

        $.post(ajaxurl, data, function (r) {
            if (!r.success) {
                alert(r.data);
                return;
            }

            $parent.addClass('removed');

            // If transition events not supported
            if (
                !('ontransitionend' in window)
                && ('onwebkittransitionend' in window)
                && !('onotransitionend' in myDiv || navigator.appName == 'Opera')
            ) {
                $parent.remove();
                $container.trigger('update.thimFile');
            }

            $('.thim-image-video-uploaded').on('transitionend webkitTransitionEnd otransitionend', 'li.removed', function () {
                $(this).remove();
                $container.trigger('update.thimFile');
            });
        }, 'json');

        return false;
    });
});