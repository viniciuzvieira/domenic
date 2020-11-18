<?php get_header(); ?>

<div class="pages">
	<div data-page="post-<?php the_ID(); ?>" class="page">
		<div class="page-content">


			<div class="content-block">
				<div class="content-block-inner">
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
						$content_post = get_post();
						$json = array();

						$json['titre'] = html_entity_decode(get_the_title());
						$json['date'] = wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date));

						$bio = array(
							'avatar' => wpappninja_get_gravatar(get_the_author_meta('user_email', $content_post->post_author)),
							'name' => get_the_author_meta('display_name', $content_post->post_author),
						);

						$json['bio'] = $bio;

						$json['config']['remove_title'] = get_wpappninja_option('remove_title', '0');
						$json['config']['show_avatar'] = get_wpappninja_option('show_avatar', '1');
						$json['config']['show_date'] = get_wpappninja_option('show_date', '0');

						$padding = "";
						?>


						<section>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

								<?php
								if (!isset($_GET['wpappninja_simul4'])) {
									if ($json['config']['remove_title'] == "0") { ?>
									<h1><?php echo $json['titre'];?></h1>
									<?php } ?>

									<?php if (($json['config']['show_avatar'] == "1" OR $json['config']['show_date'] == "1") && !is_page()) { ?>
									<div id="wpappninja_auteur">
										<?php if ($json['config']['show_avatar'] == "1") { $padding="padding-left: 17px;"; ?><div style="float:left;" id="wpappninja_avatar"><img src="<?php echo $json['bio']['avatar'];?>" height="50" width="50" style="border-radius:99px;" /></div><?php } ?>
											
										<div style="float:left;display:grid;height: 50px;<?php echo $padding;?>">
											<?php if ($json['config']['show_avatar'] == "1") { ?><div id="wpappninja_name" style="font-size:15px;margin:auto 0"><?php echo $json['bio']['name'];?></div><?php } ?>
											<?php if ($json['config']['show_date'] == "1") { ?><div id="wpappninja_date" style="color:gray;font-size:13px;margin:auto 0;"><?php echo $json['date'];?></div><?php } ?>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php } ?>
								<?php } ?>

								<?php the_content(); ?>

							</article>
						</section>

						<?php //comments_template(); ?>

					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
