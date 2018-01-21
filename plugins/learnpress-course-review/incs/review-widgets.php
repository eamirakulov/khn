<?php
/**
 * Reviews functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Creating the widget
class LearnPress_Course_Review_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'lpr_course_review',
			__( 'Course Review', 'learnpress-course-review' ),
			array( 'description' => __( 'Display ratings and reviews of course', 'learnpress-course-review' ), )
		);
	}


	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$course_id   = isset( $instance['course_id'] ) ? $instance['course_id'] : 0;
		$show_rate   = isset( $instance['show_rate'] ) ? $instance['show_rate'] : 'no';
		$show_review = isset( $instance['show_review'] ) ? $instance['show_review'] : 'no';

		if ( ! $course_id ) {
			$course_id = get_the_ID();
		}

		ob_start();
		if ( $show_rate == 'yes' ) {
			$course_rate = learn_press_get_course_rate( $course_id, false );
			$rate_args   = array(
				'course_id'   => $course_id
			,
				'course_rate' => $course_rate
			);
			learn_press_course_review_template( 'shortcode-course-rate.php', $rate_args );
		}

		if ( $show_review == 'yes' ) {
			$course_review = learn_press_get_course_review( $course_id );
			learn_press_course_review_template( 'shortcode-course-review.php', array( 'course_id'     => $course_id,
			                                                                          'course_review' => $course_review
			) );
		}

		$content = ob_get_contents();
		ob_clean();
		echo $content;
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? $instance['title'] : __( 'New title', 'wpb_widget_domain' );
		$course_id   = isset( $instance['course_id'] ) ? $instance['course_id'] : '';
		$show_rate   = isset( $instance['show_rate'] ) ? $instance['show_rate'] : 'no';
		$show_review = isset( $instance['show_review'] ) ? $instance['show_review'] : 'no';
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'learnpress-course-review' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'course_id' ); ?>"><?php _e( 'Course Id:', 'learnpress-course-review' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'course_id' ); ?>"
                   name="<?php echo $this->get_field_name( 'course_id' ); ?>" type="text"
                   value="<?php echo esc_attr( $course_id ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_rate' ); ?>"><?php _e( 'Show Rate:', 'learnpress-course-review' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'show_rate' ); ?>"
                   name="<?php echo $this->get_field_name( 'show_rate' ); ?>" type="checkbox"
                   value="yes" <?php checked( $show_rate, 'yes', true ); ?>/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_review' ); ?>"><?php _e( 'Show Review:', 'learnpress-course-review' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'show_review' ); ?>"
                   name="<?php echo $this->get_field_name( 'show_review' ); ?>" type="checkbox"
                   value="yes" <?php checked( $show_review, 'yes', true ); ?> />
        </p>
		<?php
	}

// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		var_dump( $new_instance );
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['course_id']   = ( ! empty( $new_instance['course_id'] ) ) ? strip_tags( $new_instance['course_id'] ) : '';
		$instance['show_rate']   = ( ! empty( $new_instance['show_rate'] ) ) ? strip_tags( $new_instance['show_rate'] ) : '';
		$instance['show_review'] = ( ! empty( $new_instance['show_review'] ) ) ? strip_tags( $new_instance['show_review'] ) : '';
		var_dump( $instance );

		return $instance;
	}

}

// Class wpb_widget ends here
// Register and load the widget

