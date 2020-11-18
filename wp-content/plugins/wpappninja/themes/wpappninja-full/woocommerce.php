<?php $content_post = get_post(); ?>
<?php get_header(); ?>

<div class="posts">

	<div class="post main-post">
		<?php echo wpappninja_widget('woocommerce-top'); ?>

		<div class="wpapp-post-content"><?php woocommerce_content(); ?></div>

		<?php echo wpappninja_widget('woocommerce-bottom'); ?>

	</div>
</div>
<?php get_footer(); ?>
