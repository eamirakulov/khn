<?php
/*
Plugin Name: LearnPress - Gradebook
Plugin URI: http://thimpress.com/learnpress
Description: Adding Course Gradebook for LearnPress
Author: ThimPress
Version: 2.1.7
Author URI: http://thimpress.com
Tags: learnpress
Text Domain: learnpress-gradebook
Domain Path: /languages/
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'LP_GRADEBOOK_PATH', dirname( __FILE__ ) );
define( 'LP_GRADEBOOK_THEME_TMPL', get_template_directory() . '/learnpress/addons/gradebook' );
define( 'LP_GRADEBOOK_TMPL', LP_GRADEBOOK_PATH . '/templates' );
define( 'LP_GRADEBOOK_VER', '2.1.7' );
define( 'LP_GRADEBOOK_REQUIRE_VER', '2.0' );

/**
 * Class LP_GradeBook
 */
class LP_GradeBook {
	/**
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * LP_GradeBook constructor.
	 */
	function __construct() {
		require_once LP_GRADEBOOK_PATH . '/inc/functions.php';
		if( is_admin() ) {
			require_once LP_GRADEBOOK_PATH. '/inc/class-gradebook-export.php';
		}
		$this->_init_hooks();
	}

	function export( $course_id, $args = null ) {
		$args = wp_parse_args(
			$args,
			array(
				'type'  => 'csv',
				'from'  => '',
				'to'    => '',
				's'     => '',
				'count' => false,
				'limit' => - 1
			)
		);
		$filename = sanitize_title( get_the_title( $course_id ) ) . '-gradebook';

		$course = LP_Course::get_course( $course_id );
		$course_result = get_post_meta( $course_id, '_lp_course_result', true );

		switch ( $course_result ) {
			case 'evaluate_lesson':
				$users = learn_press_gradebook_get_lessons( $course_id, $args );
				$items = $course->get_lessons();
				break;
			case 'evaluate_final_quiz':
				$users = learn_press_gradebook_get_quizzes( $course_id, $args );
				$items = $course->get_quizzes();
			case 'evaluate_quizzes':
				$users = learn_press_gradebook_get_quizzes( $course_id, $args );
				$items = $course->get_quizzes();
				break;
		}

		$data = array(
			array(
				__( 'User ID', 'learnpress-gradebook' ),
				__( 'User login', 'learnpress-gradebook' ),
				__( 'User email', 'learnpress-gradebook' ),
				__( 'User display name', 'learnpress-gradebook' )
			)
		);


		$questions= array();
		
		if ( $users ) {
			foreach ( $users as $user ) {
				$row    = array();
				$row[]  = $user['user']['id'];
				$row[]  = $user['user']['user_login'];
				$row[]  = $user['user']['user_email'];
				$row[]  = $user['user']['display_name'];
				$result = 0;

				$user_obj = learn_press_get_user($user['user']['id']);
				$course_info = $user_obj->get_course_info($course_id, null, true );

				if ( $items ) {
					foreach( $course_info['items'] as $item ){
						if( !in_array($item['title'], $data[0] )){
							$data[0][] = $item['title'];
						}

						$status = '';
						if( $item['type'] == LP_QUIZ_CPT ) {
							$quiz_progress = $user_obj->get_quiz_progress( $item['id'], $course_id );
							if( $quiz_progress->correct_percent ){
								$status .= $quiz_progress->correct_percent.'% ';
							}
						}

						switch ( $item['status'] ) {
							case 'viewed':
								$status .= __('Viewed', 'learnpress-gradebook');
								break;
							case 'completed':
								$status .= __('Completed', 'learnpress-gradebook');
								break;
							case 'passed':
								$status .= __('Passed', 'learnpress-gradebook');
								break;
							case 'failed':
								$status .= __('Failed', 'learnpress-gradebook');
								break;
							default:
								$status .= $item['status'];
								break;
						}
						$row[] = $status;
					}
				}

				$user_obj  = learn_press_get_user( $user['user']['id'] );
				$course_info = $user_obj->get_course_info($course_id, null, true );

				$result = $course_info['results'];
				$row[]  = number_format($result, 0) . '%';
				$row[]  = $user['status'] == 'completed' ? __( 'Completed', 'learnpress-gradebook' ) : __( 'In Progress', 'learnpress-gradebook' );
				$data[] = $row;
			}
		}

		$data[0][] = __( 'Average', 'learnpress-gradebook' );
		$data[0][] = __( 'Status', 'learnpress-gradebook' );

		if ( strtolower( $args['type'] ) == 'csv' ) {
			header( "Cache-Control: public" );
			header( "Content-Type: application/octet-stream" );
			header( "Content-Type: text/csv; charset=utf-8" );
			header( 'Content-Disposition: attachment; filename=' . $filename . '.csv' );
			header( 'Pragma: no-cache' );
			foreach ( $data as $k => $row ) {
				echo $k ? "\n" : '';
				echo join( ',', $row );
			}
		}
		exit();
	}

	private function _init_hooks() {
		add_action( 'init', array( __CLASS__, 'load_text_domain' ) );
		add_filter( 'manage_lp_course_posts_columns', array( $this, 'manage_course_posts_columns' ) );
		add_action( 'manage_lp_course_posts_custom_column', array( $this, 'manage_course_post_column' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'register_gradebook_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'init', array( $this, 'process' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_filter( 'learn_press_user_profile_tabs', array( $this, 'gradebook_tab' ), 105, 2 );
		add_filter( 'learn_press_profile_tab_endpoints', array( $this, 'profile_tab_endpoints' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load plugin text domain
	 */
	public static function load_text_domain() {
		if ( function_exists( 'learn_press_load_plugin_text_domain' ) ) {
			learn_press_load_plugin_text_domain( LP_GRADEBOOK_PATH, true );
		}
	}
	
	public function accessable( $user, $current_user = null ) {
		if( !$current_user ) {
			$current_user = learn_press_get_current_user();
		}
		if(!is_user_logged_in()){
			return false;
		}
		if( $user->id == $current_user->id ) {
			return true;
		}
		$current_user_roles = $current_user->user->roles;
		$allow_roles 		= array( 'administrator', 'lp_teacher' );
		$intersect 			= array_intersect( $current_user_roles, $allow_roles );
		return !empty( $intersect );
	}

	function gradebook_tab( $tabs, $user ) {

		if(!$this->accessable($user)){
			return $tabs;
		}

		$tabs[ $this->get_tab_slug() ] = array(
			'title'    => __( 'Gradebook', 'learnpress-gradebook' ),
			'callback' => array( $this, 'gradebook_tab_content' )
		);
		return $tabs;
	}

	function gradebook_tab_content( $tab, $tabs, $user ) {
		learn_press_gradebook_template(
			'profile/gradebook.php', array(
				'tab'  => $tab,
				'tabs' => $tabs,
				'user' => $user
			)
		);
	}

	function profile_tab_endpoints( $endpoints ) {
		$endpoints[] = $this->get_tab_slug();

		return $endpoints;
	}

	function get_tab_slug() {
		return 'gradebook';
	}

	function process() {
		$course_id = ! empty( $_REQUEST['course_id'] ) ? $_REQUEST['course_id'] : 0;
		if ( learn_press_gradebook_verify_nonce( $course_id ) ) {
			if ( ! empty( $_REQUEST['export'] ) ) {
				$this->export( $course_id,
					array(
						'type' => learn_press_get_request( 'export' ),
						's'    => learn_press_get_request( 's' ),
						'from' => learn_press_get_request( 'date-from' ),
						'to'   => learn_press_get_request( 'date-to' )
					)
				);
			}
		}
	}

	function scripts() {
		wp_enqueue_style( 'learn-press-gradebook', plugins_url( '/', __FILE__ ) . 'assets/gradebook.css' );
		if ( is_admin() ) {
			wp_enqueue_script( 'learn-press-gradebook', plugins_url( '/', __FILE__ ) . 'assets/gradebook.js', array(
				'jquery',
				'jquery-ui-datepicker'
			) );
		}
	}

	function admin_head() {
	}

	/**
	 * The gradebook page
	 *
	 * @return mix
	 */
	function register_gradebook_page() {
		$hook = add_submenu_page(
			'',
			__( 'Course Gradebook', 'learnpress-gradebook' ),
			'',
			'edit_published_lp_courses',
			'course-gradebook',
			array( $this, 'gradebook_page' )
		);
		add_action( "load-$hook", array( $this, 'add_options' ) );
	}

	function add_options() {
		$args = array(
			'label'   => __( 'Number of items per page', 'learnpress-gradebook' ),
			'default' => 20,
			'option'  => 'users_per_page'
		);
		add_screen_option( 'per_page', $args );
	}

	function gradebook_page() {
		$course_id = ! empty( $_REQUEST['course_id'] ) ? $_REQUEST['course_id'] : 0;

		global $post;
		$post = get_post( $course_id );
		setup_postdata( $post );
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}
		require_once LP_GRADEBOOK_PATH . '/inc/admin/class-gradebook-list-table.php';
		require LP_GRADEBOOK_PATH . '/inc/admin/gradebook.php';
		wp_reset_postdata();
	}

	/**
	 * Add gradebook column to course page in admin
	 *
	 * @param  array $column
	 *
	 * @return array
	 */
	function manage_course_posts_columns( $column ) {
		$date                = ! empty( $column['date'] ) ? $column['date'] : null;
		$column['gradebook'] = __( 'Gradebook', 'learnpress-gradebook' );
		if ( $date ) {
			unset( $column['date'] );
			$column['date'] = $date;
		}

		return $column;
	}

	/**
	 * Add the gradebook column content
	 *
	 * @param  string $column
	 * @param  number $post_id
	 *
	 * @return mix
	 */
	function manage_course_post_column( $column, $post_id ) {
		switch ( $column ) {
			case 'gradebook':
				printf( '<a href="%s" >%s</a>',
					learn_press_gradebook_nonce_url( array( 'course_id' => $post_id ) ),
					__( 'View', 'learnpress-gradebook' )
				);
				break;
		}
	}

	public static function admin_notice() {
		?>
        <div class="error">
            <p><?php printf( __( '<strong>Gradebook</strong> addon version %s requires LearnPress version %s or higher', 'learnpress-gradebook' ), LP_GRADEBOOK_VER, LP_GRADEBOOK_REQUIRE_VER ); ?></p>
        </div>
		<?php
	}

	/**
	 * @return LP_GradeBook|null
	 */
	public static function instance() {
		if ( ! defined( 'LEARNPRESS_VERSION' ) || ( version_compare( LEARNPRESS_VERSION, LP_GRADEBOOK_REQUIRE_VER, '<' ) ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'admin_notice' ) );

			return false;
		}
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Load plugin textdomain.
	 * @since 1.0.0
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'learnpress-gradebook', false, basename( __DIR__ ) . DIRECTORY_SEPARATOR . 'languages' );
	}

}

add_action( 'learn_press_loaded', array( 'LP_GradeBook', 'instance' ) );
