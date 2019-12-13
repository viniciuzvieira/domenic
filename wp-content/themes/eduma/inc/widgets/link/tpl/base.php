<?php

$html = '';

if ( !empty( $instance['text'] ) ) {
	if ( !empty( $instance['link'] ) ) {
		$html .= '<h4 class="title"><a href="' . $instance['link'] . '">' . $instance['text'] . '</a></h4>';
	} else {
		$html .= '<h4 class="title">' . $instance['text'] . '</h4>';
	}
	if ( !empty( $instance['content'] ) ) {
		$html .= '<div class="desc">' . $instance['content'] . '</div>';
	}
}

echo ent2ncr( $html );