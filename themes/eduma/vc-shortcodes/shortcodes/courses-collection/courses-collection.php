<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_courses_collection( $atts ) {

	$instance = shortcode_atts( array(
		'title'         => '',
		'limit'         => '8',
		'columns'       => '3',
		'feature_items' => '2',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	if ( thim_is_new_learnpress( '2.0' ) ) {
		$layout = 'base-v2.php';
	} else {
		$layout = 'base.php';
	}

	$widget_template       = THIM_DIR . 'inc/widgets/courses-collection/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/courses-collection/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
	echo '<div class="thim-widget-courses-collection">';
	include $widget_template;
	echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-courses-collection', 'thim_shortcode_courses_collection' );


