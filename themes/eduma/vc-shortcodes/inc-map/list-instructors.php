<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: List Instructors', 'eduma' ),
	'base'        => 'thim-list-instructors',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display List Instructors.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-one-course-instructors',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading', 'eduma' ),
			'param_name'  => 'title',
			'description' => esc_html__( 'Write the heading.', 'eduma' )
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Visible Items', 'eduma' ),
			'param_name'  => 'visible_item',
			'std'         => '4',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'Yes', 'eduma' )    => 'yes',
				esc_html__( 'No', 'eduma' )     => 'no',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

	)
) );