(function() {
	tinymce.PluginManager.add('wpappninja_editor', function( editor, url ) {

		editor.addButton('wpappninja_widgets', {
			icon: 'wpappninja_tinymce',
			text:'WPMobile.App Widgets',
			type:'menubutton',
			menu: [

				/*{
					text: 'Ads (of the WPMobile.App adserver)',
					onclick: function() {
						editor.insertContent('[wpapp_ads]');
					}
				},*/

				{
					text: 'Author info',
					onclick: function() {
						editor.insertContent('[wpapp_author]');
					}
				},



				{
					text: 'Category',
					onclick: function() {
						editor.insertContent('[wpapp_category]');
					}
				},

				{
					text: 'Comment box',
					onclick: function() {
						editor.insertContent('[wpapp_comment]');
					}
				},
				{
					text: 'Date',
					onclick: function() {
						editor.insertContent('[wpapp_date]');
					}
				},

				{
					text: 'Excerpt',
					onclick: function() {
						editor.insertContent('[wpapp_excerpt]');
					}
				},

				{
					text: 'Homepage configure button',
					onclick: function() {
						editor.insertContent('[wpapp_home_configure]');
					}
				},
				{
					text: 'Homepage user content',
					onclick: function() {
						editor.insertContent('[wpapp_home]');
					}
				},

				{
					text: 'Image (full)',
					onclick: function() {
						editor.insertContent('[wpapp_image]');
					}
				},

				{
					text: 'Image + title',
					onclick: function() {
						editor.insertContent('[wpapp_image_small]');
					}
				},


				{
					text: 'Lang selector',
					onclick: function() {
						editor.insertContent('[wpapp_lang_selector]');
					}
				},

				{
					text: 'Login form',
					onclick: function() {
						editor.insertContent('[wpapp_login]');
					}
				},


				

				{
					text: 'Push notifications history',
					onclick: function() {
						editor.insertContent('[wpapp_history]');
					}
				},

				{
					text: 'Push notifications settings',
					onclick: function() {
						editor.insertContent('[wpapp_push]');
					}
				},

				{
					text: 'Push notifications badge',
					onclick: function() {
						editor.insertContent('[wpmobile_notification_badge]');
					}
				},


                   {
                       text: 'Number of comment',
                       onclick: function() {
                           editor.insertContent('[wpapp_comment_number]');
                       }
                   },


				{
					text: 'QRCode scanner',
					onclick: function() {
						editor.insertContent('[wpmobile_qrcode_2]');
					}
				},

				{
					text: 'Recent posts',
					onclick: function() {
						editor.insertContent('[wpapp_recent]');
					}
				},

				{
					text: 'Share on social network',
					onclick: function() {
						editor.insertContent('[wpapp_social]');
					}
				},

				{
					text: 'Search bar',
					onclick: function() {
						editor.insertContent('[wpapp_search]');
					}
				},

				{
					text: 'Similar posts',
					onclick: function() {
						editor.insertContent('[wpapp_similar]');
					}
				},





				{
					text: 'Tags',
					onclick: function() {
						editor.insertContent('[wpapp_tags]');
					}
				},



				{
					text: 'Title',
					onclick: function() {
						editor.insertContent('[wpapp_title]');
					}
				},


				{
					text: 'Title (of the page)',
					onclick: function() {
						editor.insertContent('[wpapp_title_main]');
					}
				},


			]
		});
	});
})();

window.wp.mce.views.register( 'wpapp_author', {
    initialize: function() {

    	var title = 'Author info';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_author.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_history', {
    initialize: function() {

    	var title = 'Push notifications history';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_history.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );




window.wp.mce.views.register( 'wpmobile_qrcode_2', {
    initialize: function() {
    	var title = 'QRCode scanner';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_qrcode.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_qrcode', {
    initialize: function() {
    	var title = 'QRCode scanner';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_qrcode.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );
window.wp.mce.views.register( 'wpmobile_qrcode', {
    initialize: function() {
    	var title = 'QRCode scanner';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_qrcode.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpmobile_notification_badge', {
    initialize: function() {
    	var title = 'Notification badge';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpmobile_notification_badge.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_login', {
    initialize: function() {
    	var title = 'Login form';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_login.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_date', {
    initialize: function() {
    	var title = 'Date';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_date.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_lang_selector', {
    initialize: function() {
    	var title = 'Lang selector';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_lang_selector.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_home', {
    initialize: function() {
    	var title = 'Homepage user content';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_home.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_recent', {
    initialize: function() {
    	var title = 'Recent posts';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_recent.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_home_configure', {
    initialize: function() {
    	var title = 'Homepage configure button';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_home_configure.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_push', {
    initialize: function() {
    	var title = 'Push notifications settings';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_push.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_search', {
    initialize: function() {
    	var title = 'Search bar';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_search.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_category', {
    initialize: function() {
    	var title = 'Category';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_tags.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_tags', {
    initialize: function() {
    	var title = 'Tags';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_tags.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_ads', {
    initialize: function() {
    	var title = 'Ads (of the WPMobile.App adserver)';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_ads.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_image', {
    initialize: function() {
    	var title = 'Image (full)';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_image.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_image_small', {
    initialize: function() {
    	var title = 'Image + title';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_image_small.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_comment', {
    initialize: function() {
    	var title = 'Comment box';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_comment.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_social', {
    initialize: function() {
    	var title = 'Share on social network';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_social.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_excerpt', {
    initialize: function() {
    	var title = 'Excerpt';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_excerpt.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_similar', {
    initialize: function() {
    	var title = 'Similar posts';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_similar.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );


window.wp.mce.views.register( 'wpapp_title', {
    initialize: function() {
    	var title = 'Title';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_title.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );

window.wp.mce.views.register( 'wpapp_title_main', {
    initialize: function() {
    	var title = 'Title (of the page)';
	    var content = '<div class="wpapp_item_wrapper">';
	    
	    content += '<p><img width="335px" height="auto" src="https://cdn.wpmobile.app/tinymce/wpapp_title.png?v=1" /></p><small style="text-transform: uppercase;font-family: sans-serif;background: #fd9b02;color: white;font-size: 11px;padding: 10px;position: absolute;top: 0;right: 30px;">'+title+'</small>';

		content += '</div>';
	    this.render( content );
	},
} );
