<?php
/*
 * @author leehld
 * @package FITUET
 * @version 1.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

/**
 * Adds LP_Students_List widget.
 */
class LP_Students_List extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'students_list_widget',
			__( 'Learnpress - Students List', 'learnpress-collections' ),
			array( 'description' => __( 'Display students list of course.', 'learnpress' ) )
		);
	}

	/**
	 * Front-end display
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( !empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( $instance['course_id'] ) {

			$course        = LP_Course::get_course( $instance['course_id'] );
			$student_limit = $instance['number_student'] ? ( $instance['number_student'] > 0 ? $instance['number_student'] : '-1' ) : '-1';
			$filter        = $instance['filter'] ? $instance['filter'] : 'all';

			learn_press_get_template(
				'list-students.php',
				array(
					'course' => $course,
					'limit'  => $student_limit,
					'filter' => $filter
				),
				learn_press_template_path() . '/addons/students-list/',
				LP_ADDON_STUDENTS_LIST_TEMPLATE
			);

		} else {
			echo __( 'Please enter Course ID.', 'learnpress' );
		}


		echo $args['after_widget'];
	}

	/**
	 * Back-end form
	 *
	 * @param array $instance
	 *
	 * @return mixed
	 */
	public function form( $instance ) {
		$title     = !empty( $instance['title'] ) ? $instance['title'] : __( 'Students List', 'learnpress' );
		$course_id = !empty( $instance['course_id'] ) ? $instance['course_id'] : '';
		$number    = !empty( $instance['number_student'] ) ? $instance['number_student'] : '';
		$filter    = !empty( $instance['filter'] ) ? $instance['filter'] : '';
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'learnpress' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'course_id' ); ?>"><?php _e( 'Course ID:', 'learnpress' ); ?></label>
            <input type="text" size="3" value="<?php echo esc_attr( $course_id ); ?>" id="<?php echo $this->get_field_id( 'course_id' ); ?>" name="<?php echo $this->get_field_name( 'course_id' ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number_student' ); ?>"><?php _e( 'Number students to show:', 'learnpress' ); ?></label>
            <input type="number" class="tiny-text" size="3" min="1" step="1" value="<?php echo esc_attr( $number ); ?>" id="<?php echo $this->get_field_id( 'number_student' ); ?>" name="<?php echo $this->get_field_name( 'number_student' ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Filter:', 'learnpress' ); ?>
                <select class='widefat' id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" type="text">
					<?php
					$filters_students = learn_press_get_students_list_filter();

					if ( is_array( $filters_students ) ) {
						foreach ( $filters_students as $key => $_filter ) { ?>
                            <option value="<?php echo esc_attr( $key ) ?>" <?php echo ( $filter == $key ) ? ' selected' : ''; ?>><?php esc_html_e( $_filter, 'learnpress' ); ?></option>
						<?php }
					}
					?>
                </select>
            </label>
        </p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                   = array();
		$instance['title']          = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['course_id']      = ( !empty( $new_instance['course_id'] ) ) ? strip_tags( $new_instance['course_id'] ) : '';
		$instance['number_student'] = ( !empty( $new_instance['number_student'] ) ) ? strip_tags( $new_instance['number_student'] ) : '';
		$instance['filter']         = ( !empty( $new_instance['filter'] ) ) ? strip_tags( $new_instance['filter'] ) : '';

		return $instance;
	}

}

// register widget
function register_students_list_widget() {
	register_widget( 'LP_Students_List' );
}

add_action( 'lp_widgets_init', 'register_students_list_widget' );
