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
function thim_shortcode_list_events( $atts ) {

	$instance = shortcode_atts( array(
		'title'        => '',
		'layout'       => 'base',
		'number_posts' => '2',
		'text_link'    => '',
	), $atts );

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	if ( ! empty( $instance['layout'] ) ) {
		$layout = $instance['layout'] . '.php';
	} else {
		$layout = 'base.php';
	}

	$widget_template       = THIM_DIR . 'inc/widgets/list-event/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/list-event/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
	include $widget_template;
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-list-events', 'thim_shortcode_list_events' );


