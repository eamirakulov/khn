<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Gallery Posts', 'eduma' ),
	'base'        => 'thim-gallery-posts',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Gallery Posts.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-gallery-posts',
	'params'      => array(

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Select Category', 'eduma' ),
			'param_name' => 'cat',
			'value'      => thim_sc_get_categories(),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Columns', 'eduma' ),
			'param_name'  => 'columns',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( '1', 'eduma' )      => '1',
				esc_html__( '2', 'eduma' )      => '2',
				esc_html__( '3', 'eduma' )      => '3',
				esc_html__( '4', 'eduma' )      => '4',
				esc_html__( '5', 'eduma' )      => '5',
				esc_html__( '6', 'eduma' )      => '6',
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Filter', 'eduma' ),
			'param_name'  => 'filter',
			'std'         => true,
		),

	)
) );