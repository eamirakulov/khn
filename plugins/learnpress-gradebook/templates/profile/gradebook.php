<?php
$datas = learn_press_gradebook_get_user_datas();
if( !$datas ) {
	return;
}

$course_ids = array_keys( $datas );

if ( !$course_ids ) {
	return;
}
?>
<table>
	<thead>
	<tr>
		<th><?php _e( 'Course', 'learnpress-gradebook' ); ?></th>
		<th width="250"><?php _e( 'Results', 'learnpress-gradebook' ); ?></th>
		<td width="100"><?php _e('Action','learnpress-gradebook'); ?></td>
	<tr>
	</thead>
	<tbody>
<?php 
	foreach ( $course_ids as $course_id ):
		$course			= learn_press_get_course( $course_id ); 
		$course_result	= get_post_meta( $course_id, '_lp_course_result', true );
?>
		<tr>
			<td>
				<a href="<?php echo get_the_permalink( $course->id ); ?>"><?php echo $course->get_title(); ?></a>
			</td>
			<td>
			<?php
				$course_info = $user->get_course_info($course->id, null, true );
				echo round( $course_info['results'], 2 ) . '%';
				$status = __( 'In Progress', 'learnpress-gradebook' );
				$class  = 'lp-label-in-progress';
				if ( $course_info['status'] == 'finished' ) {
					if ( $user->has_passed_course( $course_id ) ) {
						$status = __( 'Passed', 'learnpress-gradebook' );
						$class  = 'lp-label-completed';
					} else {
						$status = __( 'Failed', 'learnpress-gradebook' );
						$class  = 'lp-label-failed';
					}
				}
				?>
				<span class="lp-label <?php echo $class; ?>">
					<?php echo esc_html( $status ); ?>
				</span>
			</td>
			<td><a href="javascript:jQuery('#course_details_<?php esc_attr_e( $course_id); ?>').toggle();void(0);"><?php _e('Show/Hide','learnpress-gradebook'); ?></a></td>
		</tr>
		<tr class="course_details" id="course_details_<?php esc_attr_e( $course_id); ?>" style="display:none;">
			<td colspan="3">
				<table>
					<thead>
						<tr>
							<td><?php _e('Title', 'learnpress-gradebook'); ?></td>
							<td><?php _e('Type', 'learnpress-gradebook'); ?></td>
							<td><?php _e('Status', 'learnpress-gradebook'); ?></td>
							<td><?php _e('Result', 'learnpress-gradebook'); ?></td>
						</tr>
					<thead>
					<tbody>
<?php
	if( !empty($datas[$course_id]['items']) ):
		foreach( $datas[$course_id]['items'] as $data_item ):
?>
						<tr>
							<td><?php esc_html_e( $data_item->post_title);?></td>
							<td><?php 
							if( $data_item->post_type == 'lp_lesson'){
								_e('lesson', 'learnpress-gradebook');
							} elseif ( $data_item->post_type == 'lp_quiz' ){
								_e('quiz', 'learnpress-gradebook');
							}
							?></td>
							<td>
							<?php 
								if ( $data_item->status == 'viewed' ) {
									_e('viewed', 'learnpress-gradebook');
								} elseif ( $data_item->status == 'completed' ) {
									_e('completed', 'learnpress-gradebook');
								} else {
									esc_html_e( $data_item->status);
								}
							?>
							</td>
							<td><?php
							$status = '';
							if( $data_item->post_type == LP_QUIZ_CPT ) {
								$quiz_progress = $user->get_quiz_progress( $data_item->ID, $course_id );
								if( $quiz_progress->correct_percent ) {
									$status .= $quiz_progress->correct_percent.'% ';
								}
								if( $quiz_progress->_quiz_grade == 'passed' ) {
									$status .= __('Passed', 'learnpress-gradebook' );
								} elseif( $quiz_progress->_quiz_grade == 'failed' ) {
									$status .= __('Failed', 'learnpress-gradebook' );
								}
							}
							echo $status;
							?></td>
						</tr>
<?php
		endforeach;
	endif;
?>
					</tbody>
				</table>
				<?php
				# End show lesson & quiz status
				?>
			</td>
		</tr>
		
	<?php 
	endforeach;
	?>
	</tbody>
</table>
