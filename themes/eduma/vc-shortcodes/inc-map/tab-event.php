<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Tab events', 'eduma' ),
	'base'        => 'thim-tab-event',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Show all event with tab', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-tab-event',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),
	)
) );