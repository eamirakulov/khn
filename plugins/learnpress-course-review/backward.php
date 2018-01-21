<?php
define( 'LP_ADDON_COURSE_REVIEW_FILE', __FILE__ );
define( 'LP_ADDON_COURSE_REVIEW_PATH', dirname( __FILE__ ) );
define( 'LP_ADDON_COURSE_REVIEW_PER_PAGE', 5 );
define( 'LP_ADDON_COURSE_REVIEW_VER', '2.0.2' );
define( 'LP_ADDON_COURSE_REVIEW_REQUIRE_VER', '2.0' );

/**
 * Class LP_Addon_Course_Review
 */
class LP_Addon_Course_Review {
	/**
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * @var null
	 */

	private static $comment_type = 'review';

	/**
	 * LP_Addon_Course_Review constructor.
	 */
	function __construct() {
		if ( ! function_exists( 'learn_press_load_plugin_text_domain' ) ) {
			return;
		}
		if ( self::$_instance || defined( 'LP_ADDON_COURSE_REVIEW_TMPL' ) ) {
			return;
		}
		define( 'LP_ADDON_COURSE_REVIEW_TMPL', LP_ADDON_COURSE_REVIEW_PATH . '/templates/' );
		define( 'LP_ADDON_COURSE_REVIEW_THEME_TMPL', learn_press_template_path() . '/addons/course-review/' );
		define( 'LP_ADDON_COURSE_REVIEW_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

		add_filter( 'learn_press_course_tabs', array( $this, 'add_course_tab_reviews' ), 5 );

//		add_action( 'learn_press_content_landing_summary', array( $this, 'print_rate' ), 75, 1 );
//		add_action( 'learn_press_content_learning_summary', array( $this, 'print_rate' ), 75, 1 );
//		add_action( 'learn_press_content_landing_summary', array( $this, 'print_review' ), 80 );
//		add_action( 'learn_press_content_learning_summary', array( $this, 'print_review' ), 80 );
//		add_action( 'learn_press_content_learning_summary', array( $this, 'add_review_button' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'review_assets' ) );
		add_action( 'wp', array( $this, 'course_review_init' ) );
//add_action( 'parse_comment_query', array( $this, 'exclude_rating' ) );

		add_action( 'init', array( $this, 'load_text_domain' ) );

//add_action('restrict_manage_comments', array($this, 'add_comment_post_type_filter'));
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

		require_once LP_ADDON_COURSE_REVIEW_PATH . '/incs/review-functions.php';
		require_once LP_ADDON_COURSE_REVIEW_PATH . '/incs/review-widgets.php';

		LP_Request_Handler::register_ajax( 'add_review', array( $this, 'add_review' ) );

		$this->init_comment_table();

		add_shortcode( 'learnpress', array( $this, 'shortcode_review' ) );
		add_action( 'widgets_init', array( $this, 'load_widget' ) );
		self::$_instance = $this;
	}

	function print_rate() {
		learn_press_course_review_template( 'course-rate.php' );
	}

	function print_review() {
		learn_press_course_review_template( 'course-review.php' );
	}

	function add_review_button() {
		if ( ! learn_press_get_user_rate( get_the_ID() ) ) {
			learn_press_course_review_template( 'review-form.php' );
		}
	}

	function admin_enqueue_assets() {
		wp_enqueue_style( 'ourse-review', LP_ADDON_COURSE_REVIEW_URL . '/assets/admin.css' );
	}

	function review_assets() {
		if ( learn_press_is_course() ) {
			wp_enqueue_script( 'course-review', LP_ADDON_COURSE_REVIEW_URL . '/assets/course-review.js', array( 'jquery' ), '', true );
			wp_enqueue_style( 'course-review', LP_ADDON_COURSE_REVIEW_URL . '/assets/course-review.css' );
			wp_enqueue_style( 'dashicons' );
			wp_localize_script( 'course-review', 'learn_press_course_review',
				array(
					'localize' => array(
						'empty_title'   => __( 'Please enter the review title', 'learnpress-course-review' ),
						'empty_content' => __( 'Please enter the review content', 'learnpress-course-review' ),
						'empty_rating'  => __( 'Please select your rating', 'learnpress-course-review' )
					)
				)
			);
		}
	}

	function course_review_init() {

		$paged = ! empty( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : 1;
		learn_press_get_course_review( get_the_ID(), $paged );
	}

	function exclude_rating( $query ) {
		$query->query_vars['type__not_in'] = 'review';
	}

	function add_review() {
		$response = array( 'result' => 'success' );
		$nonce    = ! empty( $_REQUEST['review_nonce'] ) ? $_REQUEST['review_nonce'] : '';
		$id       = ! empty( $_REQUEST['comment_post_ID'] ) ? absint( $_REQUEST['comment_post_ID'] ) : 0;
		$rate     = ! empty( $_REQUEST['rating'] ) ? $_REQUEST['rating'] : '0';
		$title    = ! empty( $_REQUEST['review_title'] ) ? $_REQUEST['review_title'] : '';
		$content  = ! empty( $_REQUEST['review_content'] ) ? $_REQUEST['review_content'] : '';

		if ( wp_verify_nonce( $nonce, 'learn_press_course_review_' . $id ) ) {
			$response['result']  = 'fail';
			$response['message'] = __( 'Error', 'learnpress-course-review' );
		}

		if ( get_post_type( $id ) != 'lp_course' ) {
			$response['result']  = 'fail';
			$response['message'] = __( 'Invalid course', 'learnpress-course-review' );
		}

		$return              = learn_press_add_course_review(
			array(
				'user_id'   => get_current_user_id(),
				'course_id' => $id,
				'rate'      => $rate,
				'title'     => $title,
				'content'   => $content
			)
		);
		$response['comment'] = $return;
		learn_press_send_json( $response );
	}

	function init_comment_table() {
		add_filter( 'admin_comment_types_dropdown', array( $this, 'add_comment_type_filter' ) );
		add_filter( 'comment_text', array( $this, 'add_comment_content_filter' ) );
	}

	function add_comment_type_filter( $cmt_types ) {
		$cmt_types[ self::$comment_type ] = __( 'Course review', 'learnpress-course-review' );

		return $cmt_types;
	}

	function add_comment_content_filter( $cmt_text ) {
		global $comment;
		if ( ! $comment || $comment->comment_type != 'review' ) {
			return $cmt_text;
		}

		ob_start();
		$rated = get_comment_meta( $comment->comment_ID, '_lpr_rating', true );
		echo '<div class="course-rate">';
		learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $rated ) );
		echo '</div>';
		$cmt_text .= ob_get_clean();

		return $cmt_text;
	}

	function add_comment_post_type_filter() {
		?>
        <label class="screen-reader-text"
               for="filter-by-comment-post-type"><?php _e( 'Filter by post type' ); ?></label>
        <select id="filter-by-comment-post-type" name="post_type">
			<?php
			$comment_post_types = apply_filters( 'learn_press_admin_comment_post_type_types_dropdown', array(
				''          => __( 'All post type', 'learnpress-course-review' ),
				'lp_course' => __( 'Course comments', 'learnpress-course-review' ),
			) );

			foreach ( $comment_post_types as $type => $label ) {
				echo "\t" . '<option value="' . esc_attr( $type ) . '"' . selected( $comment_post_types, $type, false ) . ">$label</option>\n";
			}

			?>

        </select>
		<?php
	}


	function shortcode_review( $atts ) {

		$atts = shortcode_atts( array(
			'course_id'   => 0,
			'show_rate'   => 'yes',
			'show_review' => 'yes'
		), $atts, 'shortcode_review' );

		$course_id = $atts['course_id'];
		if ( ! $course_id ) {
			$course_id = get_the_ID();
		}

		ob_start();
		if ( $atts['show_rate'] ) {
			$course_rate = learn_press_get_course_rate( $course_id, false );
			$total       = learn_press_get_course_rate_total( $course_id, false );
			$rate_args   = array(
				'course_id'   => $course_id
			,
				'course_rate' => $course_rate
			,
				'total'       => $total
			);
			learn_press_course_review_template( 'shortcode-course-rate.php', $rate_args );
		}

		if ( $atts['show_review'] ) {
			$course_review = learn_press_get_course_review( $course_id );
			learn_press_course_review_template( 'shortcode-course-review.php', array(
				'course_id'     => $course_id,
				'course_review' => $course_review
			) );
		}

		$content = ob_get_contents();
		ob_clean();

		return $content;
	}

	function load_widget() {
		register_widget( 'LearnPress_Course_Review_Widget' );
	}


	public function add_course_tab_reviews( $tabs ) {
		$course          = LP()->global['course'];
		$user            = learn_press_get_current_user();
		$tabs['reviews'] = array(
			'title'    => __( 'Reviews', 'learnpress-course-review' ),
			'priority' => 0,
			'callback' => array( $this, 'add_course_tab_reviews_callback' )
		);


		return $tabs;
	}

	public function add_course_tab_reviews_callback() {
		$user      = learn_press_get_current_user();
		$course_id = learn_press_get_course_id();
		$this->print_rate();
		$this->print_review();
		if ( $user->has_course_status( $course_id, array( 'enrolled', 'completed', 'finished' ) ) ) {
			$this->add_review_button();
		}
	}

	public static function admin_notice() {
		?>
        <div class="error">
            <p><?php printf( __( '<strong>Course Review</strong> addon version %s requires <strong>LearnPress</strong> version %s or higher', 'learnpress-course-review' ), LP_ADDON_COURSE_REVIEW_VER, LP_ADDON_COURSE_REVIEW_REQUIRE_VER ); ?></p>
        </div>
		<?php
	}

	/**
	 * Return unique instance of LP_Addon_Course_Review object
	 */
	static function instance() {
		if ( ! defined( 'LEARNPRESS_VERSION' ) || ( version_compare( LEARNPRESS_VERSION, LP_ADDON_COURSE_REVIEW_REQUIRE_VER, '<' ) ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'admin_notice' ) );

			return false;
		}

		if ( ! self::$_instance ) {
			self::$_instance = new self();

		}

		return self::$_instance;
	}

	/**
	 * Load text domain
	 */
	static function load_text_domain() {
		if ( function_exists( 'learn_press_load_plugin_text_domain' ) ) {
			learn_press_load_plugin_text_domain( LP_ADDON_COURSE_REVIEW_PATH, true );
		}
	}

}