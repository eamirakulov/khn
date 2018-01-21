<?php

class Thim_Course_Filters_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'course-filters',
			esc_html__( 'Thim: Course Filters', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display course filters box', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'dashicons dashicons-welcome-learn-more',
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-course-filters'
			),
			array(),
			array(
				'title' => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading', 'eduma' ),
					'default'               => esc_html__( 'Course Filters', 'eduma' ),
					'allow_html_formatting' => true
				),
				'show_price'   => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Filter By Price?', 'eduma' ),
					'default' => false
				),
				'show_featured'   => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Filter By Featured?', 'eduma' ),
					'default' => false
				),
			)
		);
	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

	function enqueue_frontend_scripts() {
		wp_enqueue_script( 'course-filters', THIM_URI . 'inc/widgets/course-filters/js/course-filters.js', array( 'jquery' ), true );
	}

}

function thim_course_filters_register_widget() {
	register_widget( 'Thim_Course_Filters_Widget' );
}

add_action( 'widgets_init', 'thim_course_filters_register_widget' );