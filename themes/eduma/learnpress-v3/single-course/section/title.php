<?php
/**
 * Template for displaying title of section in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = learn_press_get_current_user();
$course      = learn_press_get_the_course();
$user_course = $user->get_course_data( get_the_ID() );

if ( ! isset( $section ) ) {
	return;
}
//var_dump($section);die();
$title = $section->get_title();
?>

<?php if ( $title ) { ?>
    <h4 class="section-header">
        <span class="collapse"></span>
        <?php echo $title; ?>
        <span class="meta">
                <span class="step"><?php printf( __( '%d/%d', 'learnpress' ), $user_course->get_completed_items( '', false, $section->get_id() ), $section->count_items( '', false ) ); ?></span>
            </span>
    </h4>
    <?php if ( $description = $section->get_description() ) { ?>
        <p class="section-desc"><?php echo $description; ?></p>
    <?php } ?>
<?php } ?>

