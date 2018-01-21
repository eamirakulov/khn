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


do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-item' );

?>
<div id="course-curriculum-popup" class="course-summary">
    <div id="popup-sidebar">
        <?php
        do_action( 'thim_before_curiculumn_item' );
        /**
         * @since 3.0.0
         *
         * @see learn_press_single_item_summary()
         */
        do_action( 'learn-press/single-item-summary' );
        ?>
    </div>
</div>
<?php

/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );