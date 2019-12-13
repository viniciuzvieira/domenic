jQuery(document).ready(function(e) {

    var deletediv= [];

    jQuery("body").append('<div id="overlay_dialog" style="display:none;"></div>'), jQuery(document).on("click", ".delete_current_url", function() {
        jQuery(this).parent().remove()
    }), jQuery(".add_entry").click(function() {
        
        var arrkey_pages_array = Object.keys(pages_array).map(function(k) { return k });
        var arr_pages_array = Object.keys(pages_array).map(function(k) { return pages_array[k] });
        var arrkey_role_names = Object.keys(wp_role_names).map(function(k) { return k });
        var arr_role_names = Object.keys(wp_role_names).map(function(k) { return wp_role_names[k] });
        var e = "<div class='url_block block_urls'><span class='dashicons dashicons-minus delete_current_url' ></span><div class='urlrrw_div'>  ";
        
        e += "<div class='pages_urls'><table><tr><td><span>Select Source Page/Pages</span></td>";
        e += "<td><select name='source_page'  class='source_page'  required>";
       
        for(i = 0; i < arr_pages_array.length; i = i + 1) {
            e += "<option value='" + arrkey_pages_array[i] + "'>" + arr_pages_array[i] + "</option>";
        }
        e += "</select></td></tr><tr><td>";
         e += "<span>Select Role/Roles</span></td>";
        e += "<td><select name='rrw_role' class='rrw_role' multiple required>";
       
        for(i = 0; i < arr_role_names.length; i = i + 1) {
            e += "<option value='" + arrkey_role_names[i] + "'>" + arr_role_names[i] + "</option>";
        }
        e += "<option value='login'>All Roles</option><option value='logout'>Logout User</option>";
        e += "</select></td></tr><tr><td>";
        e += "<span>Select Destination Page</span></td>";
        e += "<td><select name='destination_page' class='destination_page' required>";
        
        for(i = 0; i < arr_pages_array.length; i = i + 1) {
            e += "<option value='" + arrkey_pages_array[i] + "'>" + arr_pages_array[i] + "</option>";
        }
        e += "</select></td></tr></table></div>";
        e += "</div></div>";
        jQuery(".rrw_template_block").append(e)
    });

    jQuery(".save_redirecturl").click(function() {
         fail = false;
        
         jQuery( 'div.urlrrw_div .rrw_role' ).each(function()
        {         
            if( jQuery( this ).prop( 'required' ))
            {   
               
                if ( ! jQuery( this ).val()  ) 
                {
                    fail = true;
                    if(! jQuery(this).hasClass('error'))
                    {
                        jQuery( this ).after('<span class="error-msg" style="display: block;">* Required field.</span>');
                        jQuery( this ).addClass('error');
                    } 

                }
                else
                {   
                    var temp = jQuery( this ).val();
                    var login = 0;
                    var logout = 0;
                    var error = 0;
                    var arrayLength = temp.length;
                    for (var i = 0; i < arrayLength; i++) {
                        if(temp[i] == 'login')
                        {
                            login = 1;
                        }
                        if(temp[i] == 'logout' )
                        {
                           if(login == 1)
                           {
                                fail = true;
                                alert('Error : Login Logout can not be used together. Data not saved');
                                error = 1;
                           }
                        }
                        
                    }
                    jQuery( this ).removeClass('error');
                    if(jQuery( this ).next('span').hasClass('error-msg'))
                    {
                        jQuery( this ).next('span').remove();
                    }
                }
            }   
        });
         

        //submit if fail never got set to true
        if ( ! fail ) {
           var post_id = [];
           var sources = [];
           var redirectrole = [];
           var destination = [];
           jQuery(".post_id").each(function() {
                post_id.push(jQuery(this).val());
            });
           jQuery(".source_page").each(function() {
                sources.push(jQuery(this).val());
            });
           jQuery(".rrw_role").each(function() {
                redirectrole.push(jQuery(this).val());
            });
           jQuery(".destination_page").each(function() {
                destination.push(jQuery(this).val());
            });
           jQuery.ajax({
                url : ajaxurl,
                data : {
                    action : "rrw_save_urls",
                    post_id : post_id,
                    sources : sources,
                    redirectrole : redirectrole,
                    destination : destination,
                    url_delete : deletediv,
                    security : gyrixredirectnonce.ajaxSaveGyrix
                },
                type : "POST",
                success : function(e) {
                    e = e.replace(/^\s+|\s+$/g, '');
                    if(e == 'error')
                    {
                        alert('Source and role combination should be unique');
                        location.reload();
                    }
                    else
                    {
                        e ? (alert("Updated Successfully"), location.reload()) : console.log("Not updated")
                    }
                    
                }
           });


        }
          
    });
     jQuery(".save_redirectoptions").click(function() {

        if (jQuery('.rrw_multirole').is(":checked"))
        {
            var rrw_multirole = 'multi';
        }
        else
        {
            var rrw_multirole = 'primary';
        }
          
           jQuery.ajax({
                url : ajaxurl,
                data : {
                    action : "rrw_save_options",
                    rrw_multirole : rrw_multirole,
                    security : gyrixredirectnonce.ajaxSaveOptionGyrix
                },
                type : "POST",
                success : function(e) {
                    e = e.replace(/^\s+|\s+$/g, '');
                    if(e == 'error')
                    {
                        alert('Source and role combination should be unique');
                        location.reload();
                    }
                    else
                    {
                        e ? (alert("Updated Successfully"), location.reload()) : console.log("Not updated")
                    }
                    
                }
           });

          
    });
    jQuery(".delete_current_url").on("click", function() {
        deletediv.push(parseInt(jQuery(this).parent().find(".post_id").val()));
    });
});
