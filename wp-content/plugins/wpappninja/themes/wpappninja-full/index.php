<?php get_header(); ?>

<?php if (get_wpappninja_option('titlespeed') == "1" && !isset($_GET['wpappninja_read_push']) && !isset($_GET['wpapp_shortcode'])) {

	$title = get_the_archive_title();

	if (trim($title) != "") { ?>

	<div class="title-speed"><?php echo trim($title);?></div>

	<?php }
} ?>

<div class="posts" data-instant>

	<?php $nb_posts = 0; $bottomList = false; ?>

	<?php if (isset($_GET['wpapp_shortcode'])) {

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

	} else if(!isset($_GET['wpappninja_read_push'])) { ?>

		<?php echo wpappninja_widget('list-top'); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php $nb_posts++; ?>

			<?php $content_post = get_post(); ?>
			<?php wpappninja_show_card($content_post); ?>

		<?php endwhile; ?>

		<?php if ($nb_posts == 0) { ?>
		<div style="text-align:center">
		<h1><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">search</i></h1>
		<?php _e('No result', 'wpappninja'); ?>
		<br/><br/>
		</div>

		<?php } ?>

		<?php $bottomList = true; ?>

	<?php } else {
		echo wpappninja_show_push();
	} ?>


</div>

<?php if ($bottomList) { echo wpappninja_widget('list-bottom'); } ?>

<?php if(!isset($_GET['wpappninja_read_push']) && !isset($_GET['wpapp_shortcode'])) { ?>
	<div class="pagination">

	<?php $big = 999999999; // need an unlikely integer

	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
		) ); ?>
	</div>
<?php } ?>

<?php get_footer(); ?>
