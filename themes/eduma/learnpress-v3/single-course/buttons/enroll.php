<?php
/**
 * Template for displaying Enroll button in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/buttons/enroll.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php if ( ! isset( $course ) ) {
	$course = learn_press_get_course();
} ?>

<?php do_action( 'learn-press/before-enroll-form' ); ?>

    <form name="enroll-course" class="enroll-course purchase-course" method="post" enctype="multipart/form-data">

		<?php do_action( 'learn-press/before-enroll-button' ); ?>

        <input type="hidden" name="enroll-course" value="<?php echo esc_attr( $course->get_id() ); ?>"/>
        <input type="hidden" name="enroll-course-nonce"
               value="<?php echo esc_attr( LP_Nonce_Helper::create_course( 'enroll' ) ); ?>"/>

        <button class="button button-enroll-course thim-enroll-course-button">
			<?php echo esc_html( apply_filters( 'learn-press/enroll-course-button-text', __( 'Take this course', 'eduma' ) ) ); ?>
        </button>

		<?php do_action( 'learn-press/after-enroll-button' ); ?>

    </form>

<?php do_action( 'learn-press/after-enroll-form' ); ?>