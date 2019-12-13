<div class="archive-testimonials">
	<?php
	while ( have_posts() ) :
        the_post();
		$link    = get_post_meta( get_the_ID(), 'website_url', true );
		$regency = get_post_meta( get_the_ID(), 'regency', true );
		$author  = get_post_meta( get_the_ID(), 'author', true );
		?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="image">
                <a href="<?php the_permalink(); ?>"><?php echo thim_get_feature_image( get_post_thumbnail_id(), 'full', 200, 200 ); ?></a>
            </div>
            <div class="content">
                <h3 class="title"><?php echo get_the_title(); ?></h3>
				<?php
				$regency = get_post_meta( get_the_ID(), 'regency', true );
				echo '<div class="regency">' . esc_attr( $regency ) . '</div>';
				?>
                <div class="entry-content">
					<?php the_content(); ?>
                </div>
            </div>
        </article>
		<?php
	endwhile;
	?>
</div>
<?php
thim_paging_nav();

