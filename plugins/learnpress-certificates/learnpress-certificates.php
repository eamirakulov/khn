<?php
/*
Plugin Name: LearnPress - Certificates
Plugin URI: http://thimpress.com/learnpress
Description: An addon for LearnPress plugin to create certificate for a course
Author: ThimPress
Author URI: http://thimpress.com
Tags: learnpress
Version: 2.3
Text Domain: learnpress-certificates
Domain Path: /languages/
*/
function learn_press_addon_certificates_notice() {
    ?>
    <div class="error">
        <p><?php _e( '<strong>LearnPress - Certificates</strong> addon requires upgrading to works with <strong>LearnPress</strong> version 3.0 or higher', 'learnpress-certificates' ); ?></p>
    </div>
	<?php
}

function learn_press_load_addon_certificates() {
	if ( defined( 'LEARNPRESS_VERSION' ) && version_compare( LEARNPRESS_VERSION, '3.0', '<' ) ) {
		include_once __DIR__.DIRECTORY_SEPARATOR.'backward.php';
		LP_Addon_Certificates::instance();
	} else {
		add_action( 'admin_notices', 'learn_press_addon_certificates_notice' );
	}
}

add_action( 'plugins_loaded', 'learn_press_load_addon_certificates' );