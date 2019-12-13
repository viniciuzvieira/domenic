<?php
$extent_class = '';

$target_link = ! empty( $instance['read_more_group']['target'] ) ? $instance['read_more_group']['target'] : '_self';

if ( $instance['font_image_group']['icon_img'] && $instance['font_image_group']['icon_img'] != '0' ) {
	$extent_class .= ' has_custom_image';
}
if ( $instance['read_more_group']['read_more'] == 'more' ) {
	$extent_class .= ' has_read_more';
}
/* setup hover color */
$data_color     = $boxes_icon_style = $thim_animation = $style_bg = "";
$thim_animation .= thim_getCSSAnimation( $instance['css_animation'] );

if ( $instance['color_group']['icon_hover_color'] <> '' ) {
	$data_color .= ' data-icon="' . $instance['color_group']['icon_hover_color'] . '"';
}
if ( $instance['color_group']['icon_border_color_hover'] <> '' ) {
	$data_color .= ' data-icon-border="' . $instance['color_group']['icon_border_color_hover'] . '"';
}
if ( $instance['color_group']['icon_bg_color_hover'] <> '' ) {
	$data_color .= ' data-icon-bg="' . $instance['color_group']['icon_bg_color_hover'] . '"';
}

if ( $instance['read_more_group']['button_read_more_group']['bg_read_more_text_hover'] <> '' ) {
	$data_color .= ' data-btn-bg="' . $instance['read_more_group']['button_read_more_group']['bg_read_more_text_hover'] . '"';
}
if ( $instance['read_more_group']['button_read_more_group']['read_more_text_color_hover'] <> '' ) {
	$data_color .= ' data-text-readmore="' . $instance['read_more_group']['button_read_more_group']['read_more_text_color_hover'] . '"';
}

// custom font heading
$ct_font_heading = $style_font_heading = '';
$ct_font_heading .= ( $instance['title_group']['color_title'] != '' ) ? 'color: ' . $instance['title_group']['color_title'] . ';' : '';
if ( $instance['title_group']['font_heading'] == 'custom' ) {
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_font_size'] != '' ) ? 'font-size: ' . $instance['title_group']['custom_heading']['custom_font_size'] . 'px; line-height: ' . $instance['title_group']['custom_heading']['custom_font_size'] . 'px;' : '';
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_font_weight'] != '' ) ? 'font-weight: ' . $instance['title_group']['custom_heading']['custom_font_weight'] . ';' : '';
	$ct_font_heading .= ( ! empty( $instance['title_group']['custom_heading']['custom_mg_top'] ) ) ? 'margin-top: ' . $instance['title_group']['custom_heading']['custom_mg_top'] . 'px;' : '';
	$ct_font_heading .= ( $instance['title_group']['custom_heading']['custom_mg_bt'] != '' ) ? 'margin-bottom: ' . $instance['title_group']['custom_heading']['custom_mg_bt'] . 'px;' : '';
}
if ( $ct_font_heading ) {
	$style_font_heading = 'style="' . $ct_font_heading . '"';
}
/* end setup hover color */

// icon style
$icon_style = $boxes_icon_style = '';
$icon_style .= ( $instance['color_group']['icon_bg_color'] != '' ) ? 'background-color: ' . $instance['color_group']['icon_bg_color'] . ';' : '';
$icon_style .= ( $instance['color_group']['icon_border_color'] != '' ) ? 'border-color:' . $instance['color_group']['icon_border_color'] . ';' : '';
$icon_style .= ! empty( $instance['width_icon_box'] ) ? 'width: ' . $instance['width_icon_box'] . 'px;' : '';
$icon_style .= ( ! empty( $instance['height_icon_box'] ) && '' != $instance['height_icon_box'] ) ? 'height: ' . $instance['height_icon_box'] . 'px;' : '';
if ( $icon_style ) {
	$boxes_icon_style = 'style="' . $icon_style . '"';
}
// end icon style

// read more button css
$read_more = $read_more_style = '';
$read_more .= ( $instance['read_more_group']['button_read_more_group']['border_read_more_text'] != '' ) ? 'border-color: ' . $instance['read_more_group']['button_read_more_group']['border_read_more_text'] . ';' : '';
$read_more .= ( $instance['read_more_group']['button_read_more_group']['bg_read_more_text'] != '' ) ? 'background-color: ' . $instance['read_more_group']['button_read_more_group']['bg_read_more_text'] . ';' : '';
$read_more .= ( $instance['read_more_group']['button_read_more_group']['read_more_text_color'] != '' ) ? 'color: ' . $instance['read_more_group']['button_read_more_group']['read_more_text_color'] . ';' : '';
if ( $read_more ) {
	$read_more_style = ' style="' . $read_more . '"';
}
// end
// video background
$bg_video = $poster = $class_bg_video = $icon_play = '';
if ( $instance['self_poster'] != '' ) {
	$poster = ' poster="' . wp_get_attachment_url( $instance['self_poster'] ) . '"';
}
if ( $instance['widget_background'] == 'bg_video' && $instance['self_video'] != '' ) {
	$src            = wp_get_attachment_url( $instance['self_video'] );
	$bg_video       = '<video loop muted ' . $poster . ' class="full-screen-video">
					  <source src="' . $src . '" type="video/mp4">
  				</video>';
	$class_bg_video = ' background-video';
	$icon_play      = '<span class="bg-video-play"></span>';
}
if ( $instance['widget_background'] == 'bg_color' && $instance['bg_box_color'] != '' ) {
	$style_bg = 'style="background: ' . $instance['bg_box_color'] . '"';
}

// show title
$html_title = $border_bottom_title = $class_separator = '';
if (  $instance['title_group']['line_after_title'] == '1' || $instance['title_group']['line_after_title'] == true ) {
	$border_bottom_title = '<span class="line-heading"></span>';
	$thim_animation      .= ' wrapper-line-heading';
}

$prefix = '<div class="wrapper-box-icon' . $extent_class . $class_bg_video . ' ' . $instance['layout_group']['text_align_sc'] . ' ' . $instance['layout_group']['style_box'] . ' ' . $instance['layout_group']['box_icon_style'] . $thim_animation . '" ' . $style_bg . ' ' . $data_color . '>';
$suffix = '</div>';
//wrapper-box-icon

// Set link to Box
$more_link = $link_prefix = $link_sufix = $complete_prefix = $complete_suffix = $icon_link_prefix = $icon_link_suffix = '';
if ( $instance['read_more_group']['link'] != '' ) {
	if ( $instance['read_more_group']['read_more'] == 'complete_box' ) {
		$complete_prefix .= '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '">';
		$complete_suffix .= '</a>';
	} elseif ( $instance['read_more_group']['read_more'] == 'more' ) {
		// Display Read More
		$more_link = '<a class="smicon-read sc-btn" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '" ' . $read_more_style . ' >';
		$more_link .= $instance['read_more_group']['button_read_more_group']['read_text'];
		$more_link .= '<i class="fa fa-chevron-right"></i></a>';
	} elseif ( $instance['read_more_group']['read_more'] == 'title' ) {
		//Box Title
		$link_prefix .= '<a class="smicon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '">';
		$link_sufix  .= '</a>';
	}
}
// end
$boxes_content_style = $content_style = '';
if ( $instance['layout_group']['pos'] != 'top' ) {
	$boxes_content_style .= ( $instance['width_icon_box'] != '' && $instance['font_awesome_group']['icon'] != 'none' ) ? 'width: calc( 100% - ' . $instance['width_icon_box'] . 'px - 15px);' : '';
}
if ( $boxes_content_style ) {
	$content_style = ' style="' . $boxes_content_style . '"';
}


if ( $instance['title_group']['title'] != '' ) {
	$html_title .= '<div class="sc-heading article_heading">';
	$html_title .= '<' . $instance['title_group']['size'] . ' class = "heading__primary' . $class_separator . '" ' . $style_font_heading . '>';
	if ( $complete_prefix != '' ) {
		$complete_prefix = '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '" ' . $style_font_heading . '>';
	}
	if ( $link_prefix != '' ) {
		$link_prefix = '<a class="smicon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '" ' . $style_font_heading . '>';
	}
	$html_title .= $complete_prefix . $link_prefix . $instance['title_group']['title'] . $link_sufix . $complete_suffix;
	$html_title .= '</' . $instance['title_group']['size'] . '>';
	$html_title .= $border_bottom_title;
	$html_title .= '</div>';
}
// end show title

$icon_link_prefix = $complete_prefix;
$icon_link_suffix = $complete_suffix;
if ( $instance['read_more_group']['link'] && ! empty( $instance['read_more_group']['link_to_icon'] ) && $instance['read_more_group']['link_to_icon'] != 'no' ) {
	$icon_link_prefix = '<a class="icon-box-link" target="' . $target_link . '" href="' . $instance['read_more_group']['link'] . '">';
	$icon_link_suffix = '</a>';
}

/* show icon or custom icon */
$html_icon = $icon_layout = '';
if ( $instance['layout_group']['box_icon_style'] ) {
	$icon_layout = ' ' . $instance['layout_group']['box_icon_style'];
}
if ( $instance['icon_type'] == 'font-awesome' ) {
	if ( $instance['font_awesome_group']['icon'] == '' ) {
		$instance['font_awesome_group']['icon'] = 'none';
	}
	if ( $instance['font_awesome_group']['icon'] != 'none' ) {
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';
		if ( strpos( $instance['font_awesome_group']['icon'], 'fa' ) !== false ) {
			$class = $instance['font_awesome_group']['icon'];
		} else {
			$class = 'fa fa-' . $instance['font_awesome_group']['icon'];
		}
		$style     = '';
		$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
		$style     .= ( $instance['font_awesome_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_awesome_group']['icon_size'] . 'px; line-height:' . $instance['font_awesome_group']['icon_size'] . 'px; vertical-align: middle;' : '';
		$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
} else {
	if ( $instance['icon_type'] == 'font-7-stroke' ) {
		if ( $instance['font_7_stroke_group']['icon'] == '' ) {
			$instance['font_7_stroke_group']['icon'] = 'none';
		}
		if ( $instance['font_7_stroke_group']['icon'] != 'none' ) {
			if ( strpos( $instance['font_7_stroke_group']['icon'], 'pe-7s' ) !== false ) {
				$class = $instance['font_7_stroke_group']['icon'];
			} else {
				$class = 'pe-7s-' . $instance['font_7_stroke_group']['icon'];
			}

			$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';
			$style     = '';
			$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
			$style     .= ( $instance['font_7_stroke_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_7_stroke_group']['icon_size'] . 'px; line-height:' . $instance['font_7_stroke_group']['icon_size'] . 'px; vertical-align: middle;' : '';
			$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
			$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
		}
	} else if ( $instance['icon_type'] == 'icomoon' ) {
		if ( $instance['font_icomoon_group']['icon'] == '' ) {
			$instance['font_icomoon_group']['icon'] = 'none';
		}
		if ( $instance['font_icomoon_group']['icon'] != 'none' ) {
			$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon icomoon">';
			$class     = 'icomoon-' . $instance['font_icomoon_group']['icon'];
			$style     = '';
			$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
			$style     .= ( $instance['font_icomoon_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_icomoon_group']['icon_size'] . 'px; line-height:' . $instance['font_icomoon_group']['icon_size'] . 'px; vertical-align: middle;' : '';
			$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
			$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
		}
	} else if ( $instance['icon_type'] == 'font-flaticon' ) {
		if ( $instance['font_flaticon_group']['icon'] == '' ) {
			$instance['font_flaticon_group']['icon'] = 'none';
		}
		if ( $instance['font_flaticon_group']['icon'] != 'none' ) {
			if ( strpos( $instance['font_flaticon_group']['icon'], 'flaticon' ) !== false ) {
				$class = $instance['font_flaticon_group']['icon'];
			} else {
				$class = 'flaticon-' . $instance['font_flaticon_group']['icon'];
			}

			$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon">';
			$style     = '';
			$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
			$style     .= ( $instance['font_flaticon_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_flaticon_group']['icon_size'] . 'px; line-height:' . $instance['font_flaticon_group']['icon_size'] . 'px; vertical-align: middle;' : '';
			$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
			$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
		}
	} else if ( $instance['icon_type'] == 'font_ionicons' ) {
		if ( $instance['font_ionicons_group']['icon'] == '' ) {
			$instance['font_ionicons_group']['icon'] = 'none';
		}
		if ( $instance['font_ionicons_group']['icon'] != 'none' ) {
			if ( $instance['layout_group']['pos'] == 'push' ) {
				$html_icon  .= '<div class="wrap_icon">';
				$line_color = '';
				$line_color .= ( $instance['color_group']['icon_color'] != '' ) ? 'background-color:' . $instance['color_group']['icon_color'] . ';' : '';
				$html_icon  .= '<div class="line_icon" style="' . $line_color . '"></div>';
			}
			$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $complete_prefix . '<span class="inner-icon"><span class="icon">';
			$class     = $instance['font_ionicons_group']['icon'];
			$style     = '';
			$style     .= ( $instance['color_group']['icon_color'] != '' ) ? 'color:' . $instance['color_group']['icon_color'] . ';' : '';
			$style     .= ( $instance['font_ionicons_group']['icon_size'] != '' ) ? ' font-size:' . $instance['font_ionicons_group']['icon_size'] . 'px; line-height:' . $instance['font_ionicons_group']['icon_size'] . 'px; vertical-align: middle;' : '';
			$html_icon .= '<i class="' . $class . '" style="' . $style . '"></i>';
			$html_icon .= '</span></span>' . $complete_suffix . '</div>';
			if ( $instance['layout_group']['pos'] == 'push' ) {
				$html_icon .= '</div>';

			}
		}
	} else {
		$html_icon .= '<div class="boxes-icon' . $icon_layout . '" ' . $boxes_icon_style . '>' . $icon_link_prefix . '<span class="inner-icon"><span class="icon icon-images">';
		$html_icon .= thim_get_feature_image( $instance['font_image_group']['icon_img'] );
		$html_icon .= '</span></span>' . $icon_link_suffix . '</div>';
	}
}
/* end show icon or custom icon */

/* show CONTENT*/
$html_content = $color_desc = $line_height = '';
if ( $instance['desc_group']['content'] != '' ) {
	if ( $instance['desc_group']['custom_font_size_des'] ) {
		$line_height = (int) $instance['desc_group']['custom_font_size_des'] + 7;
	}
	$color_desc .= ( $instance['desc_group']['color_description'] ) ? 'color: ' . $instance['desc_group']['color_description'] . ';' : '';
	$color_desc .= ( $instance['desc_group']['custom_font_size_des'] ) ? 'font-size: ' . $instance['desc_group']['custom_font_size_des'] . 'px;' : '';
	$color_desc .= ( $instance['desc_group']['custom_font_weight'] ) ? 'font-weight: ' . $instance['desc_group']['custom_font_weight'] . ';' : '';
	$color_desc .= ( $line_height ) ? 'line-height: ' . $line_height . 'px;' : '';
	if ( $color_desc <> '' ) {
		$color_desc = 'style="' . $color_desc . '"';
	}
	//
	$html_content .= '<div class="desc-icon-box">';
	$html_content .= ( $instance['desc_group']['content'] != '' ) ? '<div class="desc-content" ' . $color_desc . '>' . $complete_prefix . $instance['desc_group']['content'] . $complete_suffix . '</div>' : '';
	$html_content .= $more_link;
	$html_content .= "</div>";
}

if ( $html_content == '' && $more_link != '' ) {
	$html_content = $more_link;
}

// html
//start div wrapper-box-icon
$html = $prefix;
$html .= '<div class="smicon-box iconbox-' . $instance['layout_group']['pos'] . '">';
// show icon
$html .= $html_icon;
// show title
$html .= '<div class="content-inner" ' . $content_style . '>';
$html .= $html_title;

// show content
$html .= $html_content;
$html .= $icon_play;
$html .= '</div>';
$html .= '</div><!--end smicon-box-->';
$html .= $suffix;
$html .= $bg_video;
echo ent2ncr( $html );