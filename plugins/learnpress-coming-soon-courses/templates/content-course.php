<?php
/**
 * Template for displaying course content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$message = '';
$course  = LP()->global['course'];
if ( learn_press_is_coming_soon( $course->id ) && '' !== ( $message = get_post_meta( $course->id, '_lp_coming_soon_msg', true ) ) ) {
	$message = strip_tags( $message );
}
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php learn_press_get_template( 'loop/course/thumbnail.php' ); ?>
	<?php if ( $message ) { ?>
		<div class="learn-press-coming-soon-course-message"> <?php echo $message; ?></div>
	<?php } ?>
	<?php
	if ( learn_press_is_coming_soon( $course->id ) && learn_press_is_show_coming_soon_countdown( $course->id ) ) {
		$end_time = learn_press_get_coming_soon_end_time( $course->id, 'Y-m-d H:i:s' );
		$datetime = new DateTime( $end_time );
		$timezone = get_option( 'gmt_offset' );
		?>
		<div class="countdown learnpress-course-coming-soon" data-time="<?php echo esc_attr( $datetime->format( DATE_ATOM ) ) ?>" data-speed="500" data-timezone="<?php echo $timezone; ?>"></div>
	<?php } ?>
</li>