<?php
/**
 *
 */
defined( 'ABSPATH' ) || exit();
$items = $datas[$this->course->id]['items'];
?>
<table class="form-table">
	<thead>
	<tr>
		<td><?php _e( 'Title', 'learnpress-gradebook' ); ?></td>
		<td><?php _e( 'Type', 'learnpress-gradebook' ); ?></td>
		<td><?php _e( 'Status', 'learnpress-gradebook' ); ?></td>
	</tr>
	<thead>
	<tbody>
	<?php
	if ( !empty( $items ) ):
		foreach ( $items as $data_item ):
			?>
			<tr>
				<td>
					<?php esc_html_e( $data_item->post_title ); ?>
				</td>
				<td>
					<?php
					if ( $data_item->post_type == 'lp_lesson' ) {
						_e( 'lesson', 'learnpress-gradebook' );
					} elseif ( $data_item->post_type == 'lp_quiz' ) {
						_e( 'quiz', 'learnpress-gradebook' );
					}
					?>
				</td>
				<td>
					<?php esc_html_e( $data_item->status ); ?>
					<?php
					if ( $data_item->post_type == 'lp_quiz' && $data_item->status == 'completed' ) { ?>
						<br>
						<p class="course-result">
							<?php echo $data_item->quiz_res->correct_percent; ?>%
							<?php echo ' ' . $data_item->quiz_res->passing_grade; ?>
						</p>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
		endforeach;
	endif;
	?>
	</tbody>
</table>