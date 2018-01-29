<?php

function thim_child_enqueue_styles() {
	if ( is_multisite() ) {
		wp_enqueue_style( 'thim-child-style', get_stylesheet_uri() );
	} else {
		wp_enqueue_style( 'thim-parent-style', get_template_directory_uri() . '/style.css' );
	}
}

add_action( 'wp_enqueue_scripts', 'thim_child_enqueue_styles', 1000 );

// Our custom post type function
function create_posttype() {
 
    register_post_type( 'place',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Places' ),
                'singular_name' => __( 'Place' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'place'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );