<?php

$rand = time() . '-1-' . rand( 0, 100 );
echo '<div class="thim-widget-step">';
echo '<ul>';
//$active = $content_active ='';

$j = $k = 1;
if ( $instance['tab'] ) {
	switch ( count( $instance['tab'] ) ) {
		case 1:
			$class = 'tab-col-1';
			break;
		case 2:
			$class = 'tab-col-2';
			break;
		case 3:
			$class = 'tab-col-3';
			break;
		case 4:
			$class = 'tab-col-4';
			break;
		case 5:
			$class = 'tab-col-5';
			break;
		case 6:
			$class = 'tab-col-6';
			break;
		default:
			$class = 'tab-col-1';
	}
	foreach ( $instance['tab'] as $i => $tab ) {
		if ( $j == '1' ) {
			$active = "class='active " . $class . "'";
		} else {
			$active = "class='" . $class . "'";
		}
		$bg_color = $tab["bg_title"] ? $tab["bg_title"] : '';
		$style_bg = $bg_color ? 'style="background-color: ' . $tab["bg_title"] . '"' : '';
		echo '<li role="presentation" ' . $active . '><a ' . $style_bg . ' href="#thim-widget-tab-' . $j . $rand . '"  role="tab" data-toggle="tab"><strong>' . $j . '</strong>' . $tab['title'] . '</a></li>';
		$j ++;
	}
}

echo '</ul>';

echo '<div class="tab-content-step">';
if ( $instance['tab'] ) {
	foreach ( $instance['tab'] as $i => $tab ) {
		if ( $k == '1' ) {
			$content_active = " active";
		} else {
			$content_active = '';
		}

		$rel    = 'nofollow';
		$target = '_self';

		if ( is_array( $tab['link'] ) ) {
			$link   = $tab['link']['url'];
			$rel    = $tab['link']['nofollow'] ? 'nofollow' : 'dofollow';
			$target = $tab['link']['is_external'] ? '_blank' : '_self';
		} else {
			$link = $tab['link'];
		}

		echo ' <div role="tabpanel" class="tab-pane' . $content_active . '" id="thim-widget-tab-' . $k . $rand . '">';
		echo $tab['content'];
		if ( $tab["link"] ) {
			echo '<a href="' . $link . '" class="readmore" target="' . $rel . '" rel="' . $target . '">' . esc_html__( 'Learn More', 'eduma' ) . ' <i class="lnr icon-arrow-right"></i></a>';
		}
		echo '</div>';
		$k ++;
	}
}
echo '</div>';
echo '</div>';