var image_display_block = '';
jQuery(document).ready(function($) {
    $('.mfcf7_zl_add_file').on('click tap', function() {
        image_display_block = '';
        var custome_block = $(this).attr('custom-container-id');
        if (custome_block !== '') {
          if($('#'+custome_block).length) {
            image_display_block = $('#'+custome_block);
          }
        }

        var zl_filecontainer = '#mfcf7_zl_multifilecontainer';
        zl_filecontainer = $(this).parent('.mfcf7_zl_main_multifilecontainer').find(zl_filecontainer);

        //$(zl_filecontainer).append($('#mfcf7_zl_multifilecontainer span.mfcf7-zl-multiline-sample').html());
        zl_filecontainer.append(zl_filecontainer.find('span.mfcf7-zl-multiline-sample').html());

        zl_filecontainer.find('p.wpcf7-form-control-wrap:last').hide();

        zl_filecontainer.find('p.wpcf7-form-control-wrap:last input').on('change',function(e) {
            var files = $(this)[0].files;
			      for (var i = 0; i < files.length; i++) {
                var filename = "'"+files[i].name+"'";
                var cancel = '<a href="javascript:void(0);" onclick="javascript: removeSelectedFile(this,'+filename+')" class="mfcf7_zl_delete_file"><span class="delete-file" aria-hidden="true">&#x274C;</span></a>';
                if (image_display_block === '') {
                  zl_filecontainer.find('p.wpcf7-form-control-wrap:last span.mfcf7-zl-multifile-name').append('<p class="mfcf7_zl_delete_file_tag" data-name="'+ files[i].name +'">' + files[i].name + '&nbsp;' + cancel + '</p>');
                } else {
                  image_display_block.append('<p class="mfcf7_zl_delete_file_tag" data-name="'+ files[i].name +'">' + files[i].name + '&nbsp;' + cancel + '</p>');
                }
            }
            zl_filecontainer.find('p.wpcf7-form-control-wrap:last').show();
            // zl_filecontainer.find('p.wpcf7-form-control-wrap:last span.zl-multifile-name').html(filename);
            zl_filecontainer.find('p.wpcf7-form-control-wrap:last input').hide();
            zl_filecontainer.find('p.wpcf7-form-control-wrap:last .mfcf7-zl-multifile-name').show();
            zl_filecontainer.find('p.wpcf7-form-control-wrap:last a.mfcf7_zl_delete_file').show();

        });
		    zl_filecontainer.find('p.wpcf7-form-control-wrap:last a.mfcf7_zl_delete_file').hide();
        var fname = zl_filecontainer.find('p.wpcf7-form-control-wrap:last').find('input').trigger('click');
		    zl_filecontainer.find('p.wpcf7-form-control-wrap:last input').hide();

        document.addEventListener('wpcf7mailsent', function(event) {
            jQuery(zl_filecontainer).find('p').remove();
            if($('#mfcf7_zl_removalfilecontainer input').length) {
              $('#mfcf7_zl_removalfilecontainer input').val('');
            }
        });
    });

	//to avoid bad request error in safari when it has empty file input https://stackoverflow.com/questions/49614091/safari-11-1-ajax-xhr-form-submission-fails-when-inputtype-file-is-empty
	$('.wpcf7-form').submit(function() {
		//your code here
		var inputs = $('.wpcf7-form input[type="file"]:not([disabled])');
		inputs.each(function(_, input) {
		  if (input.files.length > 0) return
		  $(input).prop('disabled', true);
		})
  });
	document.addEventListener( 'wpcf7submit', function( event ) {
		var inputs = $('.wpcf7-form input[type="file"][disabled]');
		inputs.each(function(_, input) {
		  $(input).prop('disabled', false);
		})
	}, false );
});

function removeSelectedFile(element, filename) {
  var formcontainer = jQuery(element).parent().parent().parent().parent().parent('.mfcf7_zl_main_multifilecontainer');
  if (image_display_block !== '') {
    var elementid = jQuery(element).parent().parent().attr('id');
    if (jQuery('input[custom-container-id="'+elementid+'"]').length) {
      formcontainer = jQuery('input[custom-container-id="'+elementid+'"]').parent();
    }
  }
  var removal_files = formcontainer.find('#mfcf7_zl_removalfilecontainer input').val();
  if (removal_files !== '') {
    filename = removal_files+'##||##'+filename;
  }
  formcontainer.find('#mfcf7_zl_removalfilecontainer input').val(filename);
  var top_span = jQuery(element).closest('.mfcf7-zl-multifile-name');
  jQuery(element).parent().remove();
  if(top_span.find('.mfcf7_zl_delete_file_tag') && top_span.find('.mfcf7_zl_delete_file_tag').length == 0) {
    top_span.remove();
  }
}
