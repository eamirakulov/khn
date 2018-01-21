<?php

class Thim_Login_Popup_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'login-popup',
			esc_html__( 'Thim: Login Popup', 'eduma' ),
			array(
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-login-popup'
			),
			array(),
			array(
				'text_register' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Register Label', 'eduma' ),
					'default' => 'Register',
				),
				'text_login'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Login Label', 'eduma' ),
					'default' => 'Login',
				),
				'text_logout'   => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Logout Label', 'eduma' ),
					'default' => 'Logout',
				),
				'shortcode'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Shortcode', 'eduma' ),
					'description' => esc_html__( 'Enter shortcode to show in form Login', 'eduma' ),
					'default'     => '',
				)

			)
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

function thim_login_popup_widget() {
	register_widget( 'Thim_Login_Popup_Widget' );

}

add_action( 'widgets_init', 'thim_login_popup_widget' );

