<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// errors messages
if ( ! empty( $messages ) ) :
	?>

	<?php foreach ( $messages as $code => $message ) : ?>
	<?php
	wpems_get_template(
		'notices/' . $code . '.php',
		array(
			'messages' => $message,
		)
	);
	?>
<?php endforeach; ?>

<?php
endif;
