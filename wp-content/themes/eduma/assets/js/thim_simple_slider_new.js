(function ($) {
	$.fn.extend({

		thim_simple_slider: function (options) {

			// Default options for slider
			var defaults = {
				item        : 3,
				itemActive  : 1,
				itemSelector: '.item-event',
				align       : 'left',
				navigation  : true,
				pagination  : true,
				height      : 400,
				activeWidth : 1170,
				itemWidth   : 800,
				prev_text   : 'Prev',
				next_text   : 'Next'
			};

			var options = $.extend(defaults, options);

			return this.each(function () {
				var opts = options;

				// get thim_simple_slider
				var obj = $(this);

				// Get item selector
				var items = $(opts.itemSelector, obj),
					count_item = items.length,
					current_item = opts.itemActive - 1,
					loaded = false;


				obj.wrapInner('<div class="thim-simple-wrapper ' + opts.align + '"><div class="wrapper"></div></div>');

				var simple_wrapper = $(".thim-simple-wrapper", obj),
					wrapper = simple_wrapper.find(".wrapper"),
					activeWidth = opts.activeWidth,
					itemWidth = opts.itemWidth;

				_parseItems(items);

				var item_slider = simple_wrapper.find(".simple-item");


				simple_wrapper.css('height', opts.height);
				$('.simple-item', obj).css('height', opts.height);
				//wrapper.css({'height': dimension.height});

				_updatePosition();
				_navEvent();
				_pagiEvent();

				//Event for navigation
				function _navEvent() {
					if (opts.navigation) {
						simple_wrapper.append('<div class="navigation"><div data-nav="prev" class="control-nav prev">'+opts.prev_text+'</div><div data-nav="next" class="control-nav next">'+opts.next_text+'</div></div>');
					}else{
						return;
					}
					$('.control-nav', obj).on('click', function () {
						var data_nav = $(this).data('nav');
						if (data_nav == 'prev') {
							_prevItem();
						} else {
							_nextItem();
						}
					});
				}


				//Event for pagination
				function _pagiEvent() {
					if (opts.pagination) {
						var pagi_html = '<div class="pagination">';
						for ( var i = 0; i < count_item ; i++) {
							if( i === current_item ) {
								pagi_html += '<div class="item active"></div>';
							}else{
								pagi_html += '<div class="item"></div>';
							}

						}
						pagi_html += '</div>';

						simple_wrapper.append(pagi_html);
					}else{
						return;
					}

					$('.pagination .item', obj).on('click', function ( e ) {
						if( $(this).hasClass('active')) {
							e.preventDefault();
						}
						var index = $( ".pagination .item" ).index( this );
						$('.pagination .item', obj).removeClass('active');
						$(this).addClass('active');
						current_item = index;
						_updatePosition();
					});

					loaded = true;
				}


				function _updatePosition() {
					var active = simple_wrapper.find(".simple-item.active-item"),
						new_active = item_slider.eq(current_item);
					if( loaded ) {

						active.css({'position': 'absolute', 'right': 0, 'left':0}).animate({
							'opacity': 0.5,
						}, 300, function(){
							active.css({'position': '', 'right': '', 'left': ''}).removeClass('active-item');
							var list_item = $('.simple-item', obj);
							//list_item.css('width', itemWidth);

							var right_pos = activeWidth;
							for ( var i = 0; i < list_item.length; i++) {
								if( i != current_item ) {
									var elem = list_item.eq(i);
									elem.css('width', itemWidth);
									if( opts.align == 'left' ) {
										elem.css('left', right_pos);
									}else{
										elem.css('right', right_pos);
									}
									right_pos = right_pos + itemWidth;
								}
							};
						});
						new_active.addClass('active-item').css({'width': activeWidth, 'opacity': 0, 'right': '0', 'left': '0'}).animate({
							'opacity': 1,
							//'right': 0
						}, 500, function(){
							//new_active.addClass('active-item');



						});
					}else{
						if( opts.align == 'left' ) {
							new_active.css({'width':activeWidth, 'left': ''});
						}else{
							new_active.css({'width':activeWidth, 'right': ''});
						}
						var list_item = $('.simple-item:not(.active-item)', obj);

						list_item.css('width', itemWidth);

						var right_pos = activeWidth;
						for ( var i = 0; i < list_item.length; i++) {
							var elem = list_item.eq(i);
							if( opts.align == 'left' ) {
								elem.css('left', right_pos);
							}else{
								elem.css('right', right_pos);
							}
							right_pos = right_pos + itemWidth;
						}
						if( opts.pagination ) {
							$('.pagination .item', obj).removeClass('active').eq(current_item).addClass('active');
						}
					}

					if( opts.pagination ) {
						$('.pagination .item', obj).removeClass('active').eq(current_item).addClass('active');
					}


				}


				function _nextItem() {
					if (current_item < count_item - 1) {
						current_item = current_item + 1;
					} else {
						current_item = 0;
					}
					_updatePosition();
				};

				function _prevItem() {
					if (current_item > 0) {
						current_item = current_item - 1;
					} else {
						current_item = count_item - 1;
					}
					_updatePosition();
				}

				function _parseItems($items) {
					$items.each(function (key, val) {
						if (key == current_item) {
							$(val).wrap('<div class="simple-item active-item"></div>');
						} else {
							$(val).wrap('<div class="simple-item"></div>');
						}
					});
				}
			});
		}
	});

	$(document).ready(function () {
		$('.thim-event-simple-slider').thim_simple_slider({
			item        : 3,
			itemActive  : 1,
			itemSelector: '.item-event',
			align       : 'right',
			pagination  : true,
			navigation  : true,
			height      : 400,
			activeWidth : 1170,
			itemWidth   : 800,
			prev_text   : '<i class="fa fa-long-arrow-left"></i>',
			next_text   : '<i class="fa fa-long-arrow-right"></i>'
		})
	});
})(jQuery);