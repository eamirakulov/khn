<?php

class Thim_List_Instructors_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'list-instructors',
			esc_html__( 'Thim: List Instructors', 'eduma' ),
			array(
				'description'   => esc_html__( 'Show carousel slider instructors.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-list-instructors'
			),
			array(),
			array(
				'visible_item'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Visible instructors', 'eduma' ),
					'default' => '3'
				),
				'show_pagination' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Pagination', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'auto_play'       => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
					'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
					'default'     => '0'
				),
			),

			THIM_DIR . 'inc/widgets/list-instructors/'
		);
	}


	/**
	 * Initialize the CTA widget
	 */


	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

}

function thim_list_instructors_register_widget() {
	register_widget( 'Thim_List_Instructors_Widget' );

}

add_action( 'widgets_init', 'thim_list_instructors_register_widget' );

