<?php
/**
 * Created by PhpStorm.
 * User: Tu
 * Date: 12/1/2016
 * Time: 4:22 PM
 */

/**
 * @param string       $template_name
 * @param string|array $args
 */
function learn_press_coming_soon_course_template( $template_name, $args = '' ) {
	learn_press_get_template( $template_name, $args, learn_press_template_path() . '/addons/coming-soon-course/', LP_COMING_SOON_TEMPLATE_PATH );
}

/**
 * @param string $template_name
 *
 * @return string
 */
function learn_press_coming_soon_course_locate_template( $template_name ) {
	return learn_press_locate_template( $template_name, learn_press_template_path() . '/addons/coming-soon-course/', LP_COMING_SOON_TEMPLATE_PATH );
}

/**
 * @param int $course_id
 *
 * @return mixed
 */
function learn_press_is_coming_soon( $course_id = 0 ) {
	return LP_Addon_Coming_Soon_Course::instance()->is_coming_soon( $course_id );
}

/**
 * @param $course_id
 *
 * @return bool
 */
function learn_press_is_show_coming_soon_countdown( $course_id ) {
	return LP_Addon_Coming_Soon_Course::instance()->is_show_coming_soon_countdown( $course_id );
}

/**
 * @param int
 * @param string
 *
 * @return int
 */
function learn_press_get_coming_soon_end_time( $course_id, $format = 'timestamp' ) {
	return LP_Addon_Coming_Soon_Course::instance()->get_coming_soon_end_time( $course_id, $format );
}