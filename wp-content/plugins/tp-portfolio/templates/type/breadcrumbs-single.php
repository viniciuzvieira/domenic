<?php
$postid = get_the_ID();
?>
<div class="top_site_main">
	<div class="container page-title-wrapper">
		<div class="page-title-captions">
			<?php
			echo '<header class="entry-header" >';
			echo '<h2 class="entry-title">' . get_the_title($postid) . '</h2>';
			echo '</header>';
			?>
		</div>
		<div class="breadcrumbs">
			<?php
			echo thim_portfolio_breadcrumbs();
			?>
		</div>
	</div>
</div>