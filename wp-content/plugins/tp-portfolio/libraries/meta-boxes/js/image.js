jQuery(function ($) {
    // Use only one frame for all upload fields
    var frame;

    $('body').on('click', '.thim-image-advanced-upload', function (e) {
        e.preventDefault();

        var $uploadButton = $(this),
            $imageList = $uploadButton.siblings('.thim-images'),
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
            console.log(selection);

            if (maxFileUploads > 0 && (uploaded + selection.length) > maxFileUploads) {
                if (uploaded < maxFileUploads)
                    selection = selection.slice(0, maxFileUploads - uploaded);
                alert(msg);
            }

            // Get only files that haven't been added to the list
            // Also prevent duplication when send ajax request
            selection = _.filter(selection, function (attachment) {
                return $imageList.children('li#item_' + attachment.id).length == 0;
            });
            ids = _.pluck(selection, 'id');

            if (ids.length > 0) {
                console.log('xx: ' + $imageList.data('field_id'));
                console.log('yyy: ' + ids);
                var data = {
                    action: 'thim_attach_media',
                    post_id: $('#post_ID').val(),
                    field_id: $imageList.data('field_id'),
                    attachment_ids: ids,
                    _ajax_nonce: $uploadButton.data('attach_media_nonce')
                };

                $.post(ajaxurl, data, function (r) {
                    if (r.success) {
                        // console.log("success: ");
                        // console.log(r);
                        $imageList.append(r.data);
                    }
                }, 'json');
            }
        });
    });
    // Reorder images
    $('.thim-images').each(function () {
        var $this = $(this),
            data = {
                action: 'thim_reorder_images',
                _ajax_nonce: $this.data('reorder_nonce'),
                post_id: $('#post_ID').val(),
                field_id: $this.data('field_id')
            };
        $this.sortable({
            placeholder: 'ui-state-highlight',
            items: 'li',
            update: function () {
                //console.log(data);
                data.order = $this.sortable('serialize');
                $.post(ajaxurl, data);
            }
        });
    });
    // Delete file via Ajax
    $('.thim-images').on('click', '.thim-delete-file', function () {
        var $this = $(this),
            $parent = $this.parents('li'),
            $container = $this.closest('.thim-images'),
            data = {
                action: 'thim_delete_file',
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
                $container.trigger('update.rwmbFile');
            }

            $('.thim-uploaded').on('transitionend webkitTransitionEnd otransitionend', 'li.removed', function () {
                $(this).remove();
                $container.trigger('update.rwmbFile');
            });
        }, 'json');

        return false;
    });
});
