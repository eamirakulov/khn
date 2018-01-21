<?php
/*
Plugin Name: LearnPress - Course Review
Plugin URI: http://thimpress.com/learnpress
Description: Adding review for course
Author: ThimPress
Version: 2.2
Author URI: http://thimpress.com
Tags: learnpress
Requires at least: 3.8
Tested up to: 4.8

Text Domain: learnpress
Domain Path: /languages/
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function learn_press_addon_course_review_notice() {
	?>
    <div class="error">
        <p><?php _e( '<strong>Course Review</strong> addon requires upgrading to works with <strong>LearnPress</strong> version 3.0 or higher', 'learnpress-course-review' ); ?></p>
    </div>
	<?php
}

function learn_press_load_addon_course_review() {

	if ( defined( 'LEARNPRESS_VERSION' ) && version_compare( LEARNPRESS_VERSION, '3.0', '<' ) ) {
		include_once "backward.php";
		LP_Addon_Course_Review::instance();
	} else {
		add_action( 'admin_notices', 'learn_press_addon_course_review_notice' );
	}
}

add_action( 'plugins_loaded', 'learn_press_load_addon_course_review' );
