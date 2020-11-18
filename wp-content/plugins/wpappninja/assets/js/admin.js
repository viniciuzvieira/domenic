jQuery(document).ready(function($){
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$('#blog_logo_button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});

	$('.add_media').on('click', function(){
		_custom_media = false;
	});
	
	wpappninja_label();
	jQuery('.label_iconic label').click(wpappninja_label);
	
	item = wpappninja_getCookie('wpappninja_tab');

	if (wpappninja_go_toggle == "1") {
		if (item !== "") {
			wpappninja_toggle('#wpappninja_label_' + item, item);
		} else {
			wpappninja_toggle('#wpappninja_label_third', 'third');
		}
	}
	
	if (wpappninja_getCookie('wpappninja_help') == 'off') {
		jQuery(".wpappninja_help").css("display", "none");
		jQuery("#wpappninja_help")[0].checked = false;
	}
	
	jQuery("#wpappninja_help").change(function() {
		if (jQuery("#wpappninja_help")[0].checked) {
			jQuery(".wpappninja_help").css("display", "block");
			document.cookie='wpappninja_help=on';
		} else {
			jQuery(".wpappninja_help").css("display", "none");
			document.cookie='wpappninja_help=off';
		}
	});
	
	jQuery("input.wpapp-color-picker").wpColorPicker();
});

function wpappninja_label() {
	/*jQuery(".wpappninja_div .label_iconic label").css('display','none');
	jQuery(".wpappninja_div .label_iconic label:has(input[type=\"radio\"]:checked)").css('display','block');*/
}

function wpappninja_toggle(el, id) {
	if (id == '') {
		return;
	}

	if (id.indexOf("menu_") > -1 && wpappninja_enable_speed_menu == 1) {
		id = "menu_speed";
	}
	//if (el !== "") {
		
		jQuery(".wpappninja_item:not(.wpappninja_localize_a)").css('background', '#fff');
		jQuery(".wpappninja_item:not(.wpappninja_localize_a)").css('color', '#555');
		jQuery(".wpappninja_item:not(.wpappninja_localize_a) .dashicons").css('color', '#fd9b02');

		jQuery(".wpappninja_item:not(.wpappninja_localize_rate_a)").css('background', '#fff');
		jQuery(".wpappninja_item:not(.wpappninja_localize_rate_a)").css('color', '#555');
		jQuery(".wpappninja_item:not(.wpappninja_localize_rate_a) .dashicons").css('color', '#fd9b02');
		
		jQuery("#wpappninja_label_" + id).css('background', '#fd9b02');
		jQuery("#wpappninja_label_" + id).css('color', 'white');
		jQuery("#wpappninja_label_" + id).find(".dashicons").css('color', 'white');

		if (id.indexOf("menu_") > -1) {
			jQuery("#wpappninja_label_menu").css('background', '#fd9b02');
			jQuery("#wpappninja_label_menu").css('color', 'white');
			jQuery("#wpappninja_label_menu").find(".dashicons").css('color', 'white');
			
			jQuery(".wpappninja_label_" + id).css('background', '#fd9b02');
			jQuery(".wpappninja_label_" + id).css('color', 'white');
			jQuery(".wpappninja_label_" + id).find(".dashicons").css('color', 'white');
		}
		
		jQuery(".wpappninja_item .dashicons-warning").css('color', 'red');
		
		jQuery("#wpappninja_label_inject .dashicons").css('color', '#999');
		jQuery("#wpappninja_label_regex .dashicons").css('color', '#999');
		
		/*jQuery(".wpappninja_item").each(function( index ) {
			if (jQuery(this).find(".dashicons").hasClass('dashicons-yes')) {
				jQuery(this).find(".dashicons").css('color', 'green');
				jQuery(this).css('border', '1px solid green');
			}

			if (jQuery(this).find(".dashicons").hasClass('dashicons-warning')) {
				jQuery(this).find(".dashicons").css('color', 'red');
				jQuery(this).css('border', '1px solid red');
			}
		});*/
	//}

	var date = new Date();
	date.setTime(date.getTime()+(90*24*60*60*1000));
	var expires = "; expires="+date.toGMTString();

	if (id.indexOf("menu_") == -1) {
		document.cookie = 'wpappninja_tab=' + id+expires+'; path=/';
	}
	
	jQuery('.wpappninja_i_').css('display', 'none');
	jQuery('#wpappninja_i__' + id).toggle();

	return false;
}

function wpappninja_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function wpappninja_select_defaut(selectName, inputName) {
	value = jQuery( "select[name='"+selectName+"'] option:selected" ).text();
	jQuery("input[name='"+inputName+"']").val(value);
}
