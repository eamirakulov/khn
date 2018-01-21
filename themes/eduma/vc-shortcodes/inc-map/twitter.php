<?php
vc_map( array(
	'name'        => esc_html__( 'Thim: Twitter', 'eduma' ),
	'base'        => 'thim-twitter',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display twitter feed', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-twitter',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Username', 'eduma' ),
			'param_name'  => 'username',
		),


		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Tweets Display:', 'eduma' ),
			'param_name'  => 'number',
		),

	)
) );