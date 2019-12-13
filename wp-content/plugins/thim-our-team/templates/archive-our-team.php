<?php
/**
 * The Template for displaying all single posts.
 *
 * @package    thimpress
 */

get_header();

$class            = "four-col";
$images_size      = 'our_team_size22';
$class_size       = "";
$item_classes     = "";
$style_layout     = "";
$style_our_team   = "";
$gutter_pt        = " gutter";
$style_our_team   = "standard";
$style_pagination = "select-toggle_all";
$hover_our_team   = "effects_zoom_01";
$same_size        = " same";
$class_size       = $class_size . " " . $class;
?>
	<div
		class="wapper_our_team <?php echo $style_our_team; ?> <?php echo $hover_our_team; ?><?php echo $gutter_pt; ?><?php echo $same_size; ?> <?php echo $style_pagination; ?>">
		<div class="our_team_column">
			<div class="content_our_team">
				<?php
				wp_enqueue_script( 'infinitescroll' );

				while ( have_posts() ) : the_post();

					$image_id  = get_post_thumbnail_id( get_the_ID() );
					$image_url = wp_get_attachment_image( $image_id, $images_size, false, array( 'alt'   => get_the_title(),
					                                                                             'title' => get_the_title()
					) );

					// check postfolio type
					$data_href = "";
					if ( get_post_meta( get_the_ID(), 'selectOur_Team', true ) == "our_team_type_1" ) {
						if ( get_post_meta( get_the_ID(), 'style_image_popup', true ) == "Style-01" ) { // prettyPhoto
							$imclass = "image-popup-01";
							if ( get_post_meta( get_the_ID(), 'project_item_slides', true ) != "" ) { //overide image
								$att     = get_post_meta( get_the_ID(), 'project_item_slides', true );
								$imImage = wp_get_attachment_image_src( $att, 'full' );
								$imImage = $imImage[0];
							} else if ( has_post_thumbnail( $post->ID ) ) {// using thumb

								$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
								$imImage = $image[0];
							} else {// no thumb and no overide image
								$imclass = "";
								$imImage = get_permalink( $post->ID );
							}
						} else { // magnific
							$imclass = "image-popup-02";
							if ( get_post_meta( get_the_ID(), 'project_item_slides', true ) != "" ) {
								$att     = get_post_meta( get_the_ID(), 'project_item_slides', true );
								$imImage = wp_get_attachment_image_src( $att, 'full' );
								$imImage = $imImage[0];
							} else if ( has_post_thumbnail( $post->ID ) ) {

								$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
								$imImage = $image[0];
							} else {
								$imclass = "";
								$imImage = get_permalink( $post->ID );
							}
						}
					} else if ( get_post_meta( get_the_ID(), 'selectOur_Team', true ) == "our_team_type_3" ) {
						$imclass = "video-popup";
						if ( get_post_meta( get_the_ID(), 'project_video_embed', true ) != "" ) {

							if ( get_post_meta( get_the_ID(), 'project_video_type', true ) == "youtube" ) {
								$imImage = 'http://www.youtube.com/watch?v=' . get_post_meta( get_the_ID(), 'project_video_embed', true );
							} else if ( get_post_meta( get_the_ID(), 'project_video_type', true ) == "vimeo" ) {
								$imImage = 'https://vimeo.com/' . get_post_meta( get_the_ID(), 'project_video_embed', true );
							}


						} else if ( has_post_thumbnail( $post->ID ) ) {
							$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
							$imImage = $image[0];
						} else {
							$imclass = "";
							$imImage = get_permalink( $post->ID );
						}
					} else if ( get_post_meta( get_the_ID(), 'selectOur_Team', true ) == "our_team_type_2" ) {
						$imclass   = "slider-popup";
						$imImage   = "#" . $post->post_name;
						$data_href = 'data-href="' . get_permalink( $post->ID ) . '"';
					} else {
						$imclass   = "";
						$data_href = "";
						$imImage   = get_permalink( $post->ID );
					}
					/* end check our_team type */

					echo '<li class="element-item ' . $item_classes . ' item_our_team ' . $class_size . $style_layout . '">';

					if ( $style_our_team == 'standard' ) {
						echo '<div class="our_team-content-inner">';
						echo '<div class="our_team-image">';
						echo '<a href="' . esc_url( $imImage ) . '" class="link_hover ' . $imclass . '" ' . $data_href . '>';
						echo $image_url;
						echo '</a>';
						echo '<div class="our_team_hover"><div class="thumb-bg"><div class="mask-content">';
						echo '<a href="' . esc_url( $imImage ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ' . $imclass . '" ' . $data_href . '>Zoom</a>';
						echo '</div> </div></div></div>';
						echo '
		<div class="our_team_standard"><h3><a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" >' . get_the_title( $post->ID ) . '</a></h3>';
						echo '<span class="p_line"></span>';

						echo '</div>';
						echo '</div>';
					} else { // classic | gallery
						echo '<div class="our_team-image">' . $image_url . '
		<div class="our_team_hover"><div class="thumb-bg""><div class="mask-content">';
						echo '<h3><a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" >' . get_the_title( $post->ID ) . '</a></h3>';
						echo '<span class="p_line"></span>';
						echo '<a href="' . esc_url( $imImage ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ' . $imclass . '" ' . $data_href . '>Zoom</a>';
						echo '</div></div></div></div>';
					}
					echo '</li>';
					?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>

<?php
get_footer();