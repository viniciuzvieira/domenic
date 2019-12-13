<div class="tc-dashboard-wrapper wrap">
	<?php
	do_action( 'thim_dashboard_registration_box' );
	?>

    <div class="row">
        <div class="col-md-6">
            <div class="tc-sortable" data-column="left">
				<?php
				do_action( 'thim_dashboard_boxes_left' );
				?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="tc-sortable" data-column="right">
				<?php
				do_action( 'thim_dashboard_boxes_right' );
				?>
            </div>
        </div>
    </div>
</div>
