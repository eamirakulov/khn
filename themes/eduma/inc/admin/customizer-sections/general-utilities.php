<?php
/**
 * Section Utilities
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'utilities',
		'panel'    => 'general',
		'priority' => 100,
		'title'    => esc_html__( 'Utilities', 'eduma' ),
	)
);

// Feature: Google Analytics
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_google_analytics',
		'label'    => esc_html__( 'Google Analytics', 'eduma' ),
		'tooltip'  => esc_html__( 'Enter your ID Google Analytics.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 10,
	)
);

// Feature: Facebook Pixel
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_facebook_pixel',
		'label'    => esc_html__( 'Facebook Pixel', 'eduma' ),
		'tooltip'  => esc_html__( 'Enter your ID Facebook Pixel.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 20,
	)
);

// Feature: Body custom class
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_body_custom_class',
		'label'    => esc_html__( 'Body Custom Class', 'eduma' ),
		'tooltip'  => esc_html__( 'Enter body custom class.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 30,
	)
);

// Feature: Body custom class
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_register_redirect',
		'label'    => esc_html__( 'Register Redirect', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows register redirect url. Blank will redirect to home page.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 40,
	)
);

// Feature: Body custom class
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_login_redirect',
		'label'    => esc_html__( 'Login Redirect', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows login redirect url. Blank will redirect to home page.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 50,
	)
);

thim_customizer()->add_field(
	array(
		'type'     => 'select',
		'id'       => 'thim_page_builder_chosen',
		'label'    => esc_html__( 'Page Builder', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows select page builder which you want to using.', 'eduma' ),
		'priority' => 55,
		'multiple' => 0,
		'section'  => 'utilities',
		'choices'  => array(
			''                => esc_html__( 'Select', 'eduma' ),
			'site_origin'     => esc_html__( 'Site Origin', 'eduma' ),
			'visual_composer' => esc_html__( 'Visual Composer', 'eduma' ),
		),
	)
);
/*
thim_customizer()->add_field(
	array(
		'type'            => 'image',
		'id'              => 'thim_footer_bottom_bg_img',
		'label'           => esc_html__( 'Footer Bottom Background image', 'eduma' ),
		'priority'        => 60,
		'section'         => 'utilities',
		'transport'       => 'postMessage',
		'js_vars'         => array(
			array(
				'element'  => '.footer-bottom .thim-bg-overlay-color-half',
				'function' => 'css',
				'property' => 'background-image',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'thim_page_builder_chosen',
				'operator' => '===',
				'value'    => 'visual_composer',
			),
		),
	)
);
*/

// Feature: Size avata
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_size_thumb_avatar',
		'label'    => esc_html__( 'Size Avatar Thumbnail (px)', 'eduma' ),
		'tooltip'  => esc_html__( 'Enter Size Avatar Thumbnail.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 60,
	)
);
thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_size_full_avatar',
		'label'    => esc_html__( 'Size Avatar Full (px)', 'eduma' ),
		'tooltip'  => esc_html__( 'Enter Size Avatar Full.', 'eduma' ),
		'section'  => 'utilities',
		'priority' => 65,
	)
);