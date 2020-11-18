<?php $content_post = get_post(); ?>
<?php get_header(); ?>

<div class="posts wpappninja-bbpress">

	<?php
	while ( have_posts() ) : the_post();
		$content_post = get_post();
		$content = apply_filters('the_content', $content_post->post_content);
		?>
		<div class="post">
			<div class="wpapp-post-content" data-instant><?php echo $content; ?></div>
		</div>

	<?php
	endwhile;
	?>

</div>

<?php
if(get_wpappninja_option('infinitescroll', '0') !== "0" && !wpappninja_is_custom_home($content_post) && !isset($_GET['wpappninja_read_push']) && !isset($_GET['wpapp_shortcode'])) {
	wpappninja_show_previous_next($content_post); 
}

get_footer();
