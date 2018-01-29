<?php
/*
 * @author leehld
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;


if ( $course ) {

	do_action( 'learn_press_before_student-list' );
	?>
    <div class="course-students-list">

		<?php if ( $students = $course->get_students_list( true, $limit ) ): ?>

			<?php
			$show_avatar               = apply_filters( 'learn_press_students_list_avatar', true );
			$students_list_avatar_size = apply_filters( 'learn_press_students_list_avatar_size', 32 );
			$passing_condition         = round( $course->passing_condition, 0 );
			?>


            <ul class="students">
				<?php foreach ( $students as $student ) {

					$result = $process = '';
					if ( is_user_logged_in() ) {
						learn_press_setup_user_course_data( $student->ID, $course->ID, true );
						$student = LP_User_Factory::get_user( $student->ID );
						$result  = $student->get_course_info2( $course->ID );
					}
					?>

					<?php if ( $result ) {
						$process .= ( $result['results'] == 100 ) ? 'finished' : 'in-progress';
					} ?>

					<?php if ( $filter == $process || $filter == 'all' ) { ?>

                        <li class="students-enrolled <?php echo ( $result ) ? 'user-login ' . $process : ''; ?>">
                            <div class="user-info">
								<?php if ( $show_avatar ): ?>
									<?php echo get_avatar( $student->ID, $students_list_avatar_size, '', $student->display_name, array( 'class' => 'students_list_avatar' ) ); ?>
								<?php endif; ?>
                                <a class="name" href="<?php echo learn_press_user_profile_link( $student->ID ) ?>" title="<?php echo $student->display_name . ' profile' ?>">
									<?php echo $student->display_name ?>
                                </a>
                            </div>
							<?php if ( $result ): ?>
                                <div class="learn-press-course-results-progress">
                                    <div class="course-progress">
                                        <span class="course-result"><?php echo $result['results'] . '%'; ?></span>
                                        <div class="lp-course-progress">
                                            <div class="lp-progress-bar">
                                                <div class="lp-progress-value" style="width: <?php echo $result['results']; ?>%;">
                                                </div>
                                            </div>
                                            <div class="lp-passing-conditional"
                                                 data-content="<?php printf( esc_html__( 'Passing condition: %s%%', 'learnpress' ), $passing_condition ); ?>"
                                                 style="left: <?php echo esc_attr( $passing_condition ); ?>%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
							<?php endif; ?>
                        </li>
					<?php } ?>
				<?php } ?>
            </ul>
			<?php
			$other_student = $course->students;
			if ( $other_student && $limit == - 1 ) {
				echo '<p class="additional-students">and ' . sprintf( _n( 'one student enrolled.', '%s students enrolled.', $other_student, 'learnpress' ), $other_student ) . '</p>';
			}
			?>
		<?php else: ?>
            <div class="students empty">
				<?php if ( $course->students ) {
					echo apply_filters( 'learn_press_course_count_student', sprintf( _n( 'One student enrolled.', '%s students enrolled.', $course->students, 'learnpress' ), $course->students ) );
				} else {
					echo apply_filters( 'learn_press_course_no_student', __( 'No student enrolled.', 'learnpress' ) );
				} ?>
            </div>
		<?php endif; ?>
    </div>
	<?php do_action( 'learn_press_after_student-list' );
} else {
	echo __( 'Course ID invalid, please check it again.', 'learnpress' );
}