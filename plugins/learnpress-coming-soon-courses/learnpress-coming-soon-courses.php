<?php
/*
Plugin Name: LearnPress - Coming Soon Courses
Plugin URI: http://thimpress.com/learnpress
Description: Set a course is "Coming Soon" and schedule to public
Author: ThimPress
Version: 2.2
Author URI: http://thimpress.com
Tags: learnpress
Text Domain: learnpress
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 *  Define constants
 */
define( 'LP_COMING_SOON_PATH', dirname( __FILE__ ) );
define( 'LP_COMING_SOON_TEMPLATE_PATH', LP_COMING_SOON_PATH . '/templates' );
define( 'LP_COMING_SOON_VER', '2.0' );
define( 'LP_COMING_SOON_REQUIRE_VER', '2.0.5' );

class LP_Addon_Coming_Soon_Course {
	/**
	 * @var LP_Addon_Coming_Soon_Course
	 */
	protected static $_instance = null;

	/**
	 * @var RW_Meta_Box
	 */
	public $metabox = null;

	/**
	 * Hold the course ids is coming soon
	 *
	 * @var array
	 */
	protected $_coming_soon_courses = array();

	protected $_course_coming_soon = null;

	/**
	 * LP_Addon_Coming_Soon_Course constructor.
	 */
	public function __construct() {
		if ( self::$_instance instanceof LP_Addon_Coming_Soon_Course ) {
			return;
		}
		// LearnPress has loaded meta box library
//		add_action( 'learn_press_meta_box_loaded', array( $this, 'admin_meta_box' ) );
		add_action( 'learn_press_add_default_scripts', array( $this, 'add_default_scripts' ), 10, 4 );
		add_action( 'learn_press_add_default_styles', array( $this, 'add_default_styles' ), 10, 3 );
		add_action( 'learn_press_load_scripts', array( $this, 'load_scripts' ) );
		add_filter( 'learn_press_get_template', array( $this, 'change_default_template' ), 100, 5 );
		add_filter( 'learn_press_get_template_part', array( $this, 'change_content_course_template' ), 100, 3 );
		add_action( 'load-post.php', array( $this, 'course_coming_soon_meta_box' ), 20 );
		add_action( 'load-post-new.php', array( $this, 'course_coming_soon_meta_box' ), 20 );

		add_action( 'learn_press_content_coming_soon_message', array( $this, 'coming_soon_message' ), 10 );
		add_action( 'learn_press_content_coming_soon_countdown', array( $this, 'coming_soon_countdown' ), 10 );

		add_filter( 'learn_press_lp_course_tabs', array( $this, 'admin_course_tabs' ) );

		require_once LP_COMING_SOON_PATH . '/inc/functions.php';
	}

	public function course_coming_soon_meta_box( $post_id ) {
		$prefix                    = '_lp_';
		$meta_box                  = array(
			'id'       => 'course_coming_soon',
			'title'    => __( 'Coming soon', 'learnpress' ),
			'priority' => 'high',
			'pages'    => array( LP_COURSE_CPT ),
			'fields'   => array(
				array(
					'name'    => __( 'Enable', 'learnpress' ),
					'id'      => "{$prefix}coming_soon",
					'type'    => 'radio',
					'desc'    => __( 'Enable coming soon will show coming soon message on course detail page' ),
					'options' => array(
						'no'  => __( 'No', 'learnpress' ),
						'yes' => __( 'Yes', 'learnpress' ),
					),
					'std'     => 'no',
				),
				array(
					'name'   => __( 'Message', 'learnpress' ),
					'id'     => "{$prefix}coming_soon_msg",
					'type'   => 'wysiwyg',
					'editor' => true,
					'desc'   => __( 'The coming soon message will show in course details page', 'learnpress' ),
					'std'    => __( 'This course will coming soon', 'learnpress' ),
				),
				array(
					'name' => __( 'Coming soon end time', 'learnpress' ),
					'id'   => "{$prefix}coming_soon_end_time",
					'type' => 'datetime',
					'desc' => __( 'Set end time coming soon', 'learnpress' ),
				),
				array(
					'name'    => __( 'Show Countdown', 'learnpress' ),
					'id'      => "{$prefix}coming_soon_countdown",
					'type'    => 'radio',
					'desc'    => __( 'Show or hide countdown plugin', 'learnpress' ),
					'options' => array(
						'no'  => __( 'No', 'learnpress' ),
						'yes' => __( 'Yes', 'learnpress' ),
					),
					'std'     => 'no',
				)
			)
		);
		$this->_course_coming_soon = new RW_Meta_Box( $meta_box );
	}

	/**
	 * Register new tab in course page
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function admin_course_tabs( $tabs ) {
		$tabs['course_coming_soon'] = $this->_course_coming_soon;

		return $tabs;
	}

	public function change_default_template( $located, $template_name, $args, $template_path, $default_path ) {
		remove_filter( 'learn_press_get_template', array( $this, 'change_default_template' ), 100, 5 );
		if ( $template_name == 'content-single-course.php' ) {
			$course = LP()->global['course'];
			if ( $this->is_coming_soon( $course->id ) ) {
				$located = $this->locate_template( $template_name );
			}
		}
		add_filter( 'learn_press_get_template', array( $this, 'change_default_template' ), 100, 5 );

		return $located;
	}

	public function change_content_course_template( $template, $slug, $name ) {
		if ( $slug == 'content' && $name == 'course' ) {
			$course = LP()->global['course'];
			if ( $this->is_coming_soon( $course->id ) ) {
				remove_filter( 'learn_press_get_template_part', array(
					$this,
					'change_content_course_template'
				), 100, 3 );
				$template = $this->locate_template( "content-course.php" );
				add_filter( 'learn_press_get_template_part', array( $this, 'change_content_course_template' ), 100, 3 );
			}
		}

		return $template;
	}

	/**
	 * @param WP_Scripts
	 * @param string
	 * @param string
	 * @param array
	 */
	public function add_default_scripts( $scripts, $default_path, $suffix, $deps ) {
		$default_path = '/' . LP_WP_CONTENT . '/plugins/learnpress-coming-soon-courses/assets/';
		$scripts->add( 'learn-press-jquery-mb-coming-soon', $default_path . 'jquery.mb-coming-soon' . $suffix . '.js', $deps, false, 1 );
		$scripts->add( 'learn-press-coming-soon-course', $default_path . 'coming-soon-course' . $suffix . '.js', $deps, false, 1 );
	}

	/**
	 * @param WP_Styles
	 * @param string
	 * @param array
	 */
	public function add_default_styles( $styles, $default_path, $deps ) {
		$default_path = '/' . LP_WP_CONTENT . '/plugins/learnpress-coming-soon-courses/assets/';
		$styles->add( 'learn-press-coming-soon-course', $default_path . 'coming-soon-course.css' );
	}

	public function load_scripts() {

		LP_Assets::enqueue_style( 'learn-press-coming-soon-course' );
		LP_Assets::enqueue_script( 'learn-press-jquery-mb-coming-soon' );
		LP_Assets::enqueue_script( 'learn-press-coming-soon-course' );

		$course = LP()->global['course'];

		if ( $course && $this->is_coming_soon( $course->id ) ) {
			$translation_array = array(
				'days'    => __( 'days', 'learnpress' ),
				'hours'   => __( 'hours', 'learnpress' ),
				'minutes' => __( 'minutes', 'learnpress' ),
				'seconds' => __( 'seconds', 'learnpress' ),
			);
			wp_localize_script( 'learn-press-coming-soon-course', 'lp_coming_soon_translation', $translation_array );
		}

	}


	/**
	 * Display coming soon message
	 */
	public function coming_soon_message() {
		$course = LP()->global['course'];
		if ( $this->is_coming_soon( $course->id ) && '' !== ( $message = get_post_meta( $course->id, '_lp_coming_soon_msg', true ) ) ) {
			// enable shortcode in coming message
			$message = do_shortcode( $message );
			$this->get_template( 'single-course/coming-soon-message.php', array( 'message' => $message ) );
		}
	}

	/**
	 * Display coming soon countdown
	 */
	public function coming_soon_countdown() {
		$course   = LP()->global['course'];
		$end_time = $this->get_coming_soon_end_time( $course->id, 'Y-m-d H:i:s' );
		$datetime = new DateTime( $end_time );
		$timezone = get_option( 'gmt_offset' );
		$this->get_template( 'single-course/coming-soon-countdown.php', array(
			'datetime' => $datetime,
			'timezone' => $timezone
		) );
	}

	/**
	 * @param        $template_name
	 * @param string $args
	 */
	public function get_template( $template_name, $args = '' ) {
		learn_press_coming_soon_course_template( $template_name, $args );
	}

	/**
	 * @param $template_name
	 *
	 * @return string
	 */
	public function locate_template( $template_name ) {
		return learn_press_coming_soon_course_locate_template( $template_name );
	}

	/**
	 * Check all options and return TRUE if a course has 'Coming Soon'
	 *
	 * @param int $course_id
	 *
	 * @return mixed
	 */
	public function is_coming_soon( $course_id = 0 ) {
		if ( ! $course_id && LP_COURSE_CPT == get_post_type() ) {
			$course_id = get_the_ID();
		}
		$end_time = $current_time = 0;
		if ( empty( $this->_coming_soon_courses[ $course_id ] ) ) {
			$this->_coming_soon_courses[ $course_id ] = false;
			if ( $this->is_enable_coming_soon( $course_id ) ) {
				$end_time     = $this->get_coming_soon_end_time( $course_id );
				$current_time = current_time( 'timestamp' );

				if ( $end_time == 0 || $end_time > $current_time ) {
					$this->_coming_soon_courses[ $course_id ] = true;
				}
			}
		}

		return $this->_coming_soon_courses[ $course_id ];
	}

	/**
	 * Return TRUE if 'Coming Soon' is enabled
	 *
	 * @param int $course_id
	 *
	 * @return bool
	 */
	public function is_enable_coming_soon( $course_id = 0 ) {
		if ( ! $course_id && LP_COURSE_CPT == get_post_type() ) {
			$course_id = get_the_ID();
		}

		return 'yes' == get_post_meta( $course_id, '_lp_coming_soon', true );
	}

	/**
	 * Return expiration time of 'Coming Soon'
	 *
	 * @param int $course_id
	 * @param     string
	 *
	 * @return int
	 */
	public function get_coming_soon_end_time( $course_id = 0, $format = 'timestamp' ) {
		if ( ! $course_id && LP_COURSE_CPT == get_post_type() ) {
			$course_id = get_the_ID();
		}
		$end_time = 0;
		if ( $this->is_enable_coming_soon( $course_id ) ) {
			$end_time = get_post_meta( $course_id, '_lp_coming_soon_end_time', true );
			if ( $format == 'timestamp' ) {
				$end_time = strtotime( $end_time );
			} elseif ( $format ) {
				$end_time = date( $format, strtotime( $end_time ) );
			}
		}

		return $end_time;
	}

	/**
	 * Return TRUE if a course is enabled countdown
	 *
	 * @param int $course_id
	 *
	 * @return bool
	 */
	public function is_show_coming_soon_countdown( $course_id = 0 ) {
		if ( ! $course_id && LP_COURSE_CPT == get_post_type() ) {
			$course_id = get_the_ID();
		}

		return 'yes' == get_post_meta( $course_id, '_lp_coming_soon_countdown', true );
	}

	/**
	 * Singleton instance of our class
	 *
	 * @return LP_Addon_Coming_Soon_Course
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

add_action( 'learn_press_ready', array( 'LP_Addon_Coming_Soon_Course', 'instance' ) );