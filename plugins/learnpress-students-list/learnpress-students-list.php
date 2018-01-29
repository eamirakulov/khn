<?php
/*
Plugin Name: LearnPress - Students List
Plugin URI: http://thimpress.com/learnpress
Description: Get students list by filters.
Author: ThimPress
Version: 2.0.1
Author URI: http://thimpress.com
Tags: learnpress
Text Domain: learnpress
Domain Path: /languages/
*/

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 *  Define constants
 */
define( 'LP_ADDON_STUDENTS_LIST_PATH', dirname( __FILE__ ) );
define( 'LP_ADDON_STUDENTS_LIST_VER', '2.0.1' );
define( 'LP_ADDON_STUDENTS_LIST_REQUIRE_VER', '2.0' );
define( 'LP_ADDON_STUDENTS_LIST_TEMPLATE', LP_ADDON_STUDENTS_LIST_PATH . '/templates/' );


/**
 * Class LP_Addon_Students_List
 */
class LP_Addon_Students_List {

	/**
	 * @var null
	 */
	protected static $_instance = null;

	public function __construct() {

		// Load scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

		// Learnpress has loaded meta box library
		add_filter( 'learn_press_course_settings_meta_box_args', array( $this, 'add_settings_meta_box_args' ), 10, 1 );

		// Add students tab for default course tab
		add_filter( 'learn_press_course_tabs', array( $this, 'add_course_students_tabs' ), 10, 1 );

		// shortcodes
		include_once LP_ADDON_STUDENTS_LIST_PATH . '/inc/shortcodes.php';
		// widgets
		include_once LP_ADDON_STUDENTS_LIST_PATH . '/inc/widgets.php';
	}


	/**
	 * Load scripts
	 */
	public function load_scripts() {
		wp_enqueue_style( 'learnpress-students-list', plugins_url( '/', __FILE__ ) . 'assets/learnpress-students-list.css' );
		wp_enqueue_script( 'learnpress-students-list', plugins_url( '/', __FILE__ ) . 'assets/learnpress-students-list.js', array( 'jquery' ) );
	}

	/**
	 * Add student tabs to default course tabs
	 *
	 * @param $tabs
	 *
	 * @return array
	 */
	public function add_course_students_tabs( $tabs ) {

		$course = LP()->global['course'];

		$hide_students_list = get_post_meta( $course->ID, '_lp_hide_students_list', true );
		$students_tab       = array();

		if ( $hide_students_list != 'yes' ) {
			// Students list
			$students_tab['students-list'] = array(
				'title'    => __( 'Students', 'learnpress' ),
				'priority' => 40,
				'callback' => 'learn_press_course_students_list'
			);
		}

		$tabs = array_merge( $tabs, $students_tab );

		return $tabs;
	}

	/**
	 * Add settings metabox for student list
	 *
	 * @param $meta_box
	 *
	 * @return mixed
	 */
	public function add_settings_meta_box_args( $meta_box ) {
		$prefix = '_lp_';

		$additional_fields = array(
			array(
				'name' => __( 'Show students list', 'learnpress' ),
				'id'   => "{$prefix}hide_students_list",
				'type' => 'yes_no',
				'desc' => __( 'Option to hide the students list in each individual course.', 'learnpress' ),
				'std'  => 'yes',
			)
		);

		$meta_box['fields'] = array_merge( $meta_box['fields'], $additional_fields );

		return $meta_box;
	}

	/**
	 * Load text domain
	 */
	static function load_text_domain() {
		if ( function_exists( 'learn_press_load_plugin_text_domain' ) ) {
			learn_press_load_plugin_text_domain( LP_ADDON_STUDENTS_LIST_PATH, true );
		}
	}

	/**
	 * Show admin notice
	 */
	public static function admin_notice() { ?>
        <div class="error">
            <p><?php printf( __( '<strong>Students List</strong> addon version %s requires <strong>LearnPress</strong> version %s or higher', 'learnpress' ), LP_ADDON_STUDENTS_LIST_VER, LP_ADDON_STUDENTS_LIST_REQUIRE_VER ); ?></p>
        </div>
	<?php }

	/**
	 * @return bool|LP_Addon_Students_List|null
	 */
	static function instance() {
		if ( !defined( 'LEARNPRESS_VERSION' ) || ( version_compare( LEARNPRESS_VERSION, LP_ADDON_STUDENTS_LIST_REQUIRE_VER, '<' ) ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'admin_notice' ) );
			return false;
		}
		if ( !self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}

add_action( 'learn_press_loaded', array( 'LP_Addon_Students_List', 'instance' ) );

