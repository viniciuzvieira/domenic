<?php
if (isset($_GET['offlinewpappninja']) || isset($_GET['wpapp_shortcode'])) {
	status_header(200);
} ?>
<?php get_header(); ?>
<div class="posts">

<?php
    
    if (!isset($_GET['wpapp_shortcode'])) {?>
<div class="post main-post" style="text-align:center">
<div class="wpapp-post-content">
<?php }
    
if (isset($_GET['offlinewpappninja'])) {

	$has_custom_offline_page = get_page_by_title("wpmobile_offline");

	if ($has_custom_offline_page != null) {
		echo get_post_field('post_content', $has_custom_offline_page->ID);
	} else {
		echo '<h1><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">close_round</i></h1>';
		_e('Sorry, this page is not available offline.', 'wpappninja');
		echo '<br/>';
		_e('Check your connection.', 'wpappninja');
	}

} elseif (isset($_GET['wpapp_shortcode'])) {

    if ($_GET['wpapp_shortcode'] == 'wpapp_push' || $_GET['wpapp_shortcode'] == 'wpapp_config' || $_GET['wpapp_shortcode'] == 'wpapp_login') {
        echo '<div class="post main-post">
        <div class="wpapp-post-content">';
    }

    if ($_GET['wpapp_shortcode'] == 'wpapp_recent') {
        echo wpappninja_widget('list-top');
    }

    echo '<div data-instant>' . do_shortcode('[' . $_GET['wpapp_shortcode'] . ']') . '</div>';
    
    if ($_GET['wpapp_shortcode'] == 'wpapp_recent') {
        echo wpappninja_widget('list-bottom');
    }

    if ($_GET['wpapp_shortcode'] == 'wpapp_push' || $_GET['wpapp_shortcode'] == 'wpapp_config' || $_GET['wpapp_shortcode'] == 'wpapp_login') {
        echo '</div>
        </div>';
    }

} else if (isset($_GET['wpappninja_read_push'])) {

    echo wpappninja_show_push();

}else {
	echo '<h1><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">close_round</i></h1>';
	_e('Sorry, this page does not exist.', 'wpappninja');
} ?>
<?php
    if (!isset($_GET['wpapp_shortcode'])) {?>
<br/><br/></div></div>
<?php } ?>
</div>
<?php get_footer(); ?>
