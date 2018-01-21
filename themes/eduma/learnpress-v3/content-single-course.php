<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-single-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
$is_enrolled      = $user->has( 'enrolled-course', $course->get_id() );
/**
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );
do_action( 'learn_press_before_single_course' );
do_action( 'learn_press_before_single_course_summary' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-course' );

?>
<div id="learn-press-course" class="course-summary learn-press">

    <?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

    <div class="course-meta">
        <?php learn_press_course_instructor(); ?>
        <?php learn_press_course_categories(); ?>
        <?php thim_course_forum_link(); ?>
        <?php thim_course_ratings(); ?>
        <?php learn_press_course_progress(); ?>
    </div>
    <?php if ( !$is_enrolled ) { ?>
        <div class="course-payment">
            <?php

            learn_press_get_template( 'single-course/price.php' );
            learn_press_get_template( 'single-course/buttons.php' );

            ?>
        </div>
    <?php } ?>
    <div class="course-summary">
        <?php
        /**
         * @since 3.0.0
         *
         * @see learn_press_single_course_summary()
         */
        do_action( 'learn-press/single-course-summary' );
        ?>
    </div>
    <?php thim_related_courses(); ?>
    <?php
    $related_courses = thim_get_related_courses( 5 );
    //var_dump($related_courses);
    ?>
</div>
<?php

/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );

/**
 * @deprecated
 */
do_action( 'learn_press_after_single_course_summary' );
do_action( 'learn_press_after_single_course' );
do_action( 'learn_press_after_main_content' );
?>