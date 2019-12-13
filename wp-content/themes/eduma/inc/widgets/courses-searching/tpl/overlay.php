<?php

$placeholder = '';

if($instance['placeholder'] && $instance['placeholder'] <> ''){
	$placeholder = $instance['placeholder'];
}

?>
<div class="thim-course-search-overlay">
	<div class="search-toggle"><i class="fa fa-search"></i></div>
	<div class="courses-searching layout-overlay">
		<div class="search-popup-bg"></div>
		<form method="get" action="<?php echo site_url( '/' ); ?>">
			<input type="text" value="" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="thim-s form-control courses-search-input" autocomplete="off" />
			<input type="hidden" value="course" name="ref" />
			<button type="submit"><i class="fa fa-search"></i></button>
			<span class="widget-search-close"></span>
		</form>
		<ul class="courses-list-search list-unstyled"></ul>
	</div>
</div>
