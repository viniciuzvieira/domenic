<?php
/**
 * Template for displaying the price of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.1.4.2
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course = LP()->global['course'];

if ( learn_press_is_enrolled_course() ) {
	return;
}
$is_required = $course->is_required_enroll();

?>
<?php if ( $price = $course->get_price_html() ) {
	$origin_price = $course->get_origin_price_html();
	$sale_price   = $course->get_sale_price();
	$sale_price   = isset( $sale_price ) ? $sale_price : '';
	$class        = '';
	$class .= ( $sale_price !== '' ) ? ' has-origin' : '';
	if ( $course->is_free() || !$is_required ) {
		$class .= ' free-course';
		$price = esc_html__( 'Free', 'eduma' );
	}

	?>

	<div class="course-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<div class="value<?php echo $class; ?>" itemprop="price">
			<?php
			if ( $sale_price !== '' ) {
				echo '<span class="course-origin-price">' . $origin_price . '</span>';
			}
			?>
			<?php echo $price; ?>
		</div>
		<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency(); ?>" />
	</div>
	<?php
}