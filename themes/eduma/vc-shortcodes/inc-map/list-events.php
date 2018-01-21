<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: List Events', 'eduma' ),
	'base'        => 'thim-list-events',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display List Events.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-list-event',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Number events display', 'eduma' ),
			'param_name'  => 'number_posts',
			'std'         => '2',
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Layout', 'eduma' ),
			'param_name'  => 'layout',
			'value'       => array(
				esc_html__( 'Default', 'eduma' )  => 'base',
				esc_html__( 'Slider', 'eduma' )   => 'slider',
				esc_html__( 'Layout 2', 'eduma' ) => 'layout-2',
				esc_html__( 'Layout 3', 'eduma' ) => 'layout-3',
			),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text View All', 'eduma' ),
			'param_name'  => 'text_link',
			'std'         => esc_html__( 'View All', 'eduma' ),
		),

	)
) );