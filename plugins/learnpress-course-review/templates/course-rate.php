<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course_id       = get_the_ID();
$course_rate_res = learn_press_get_course_rate( $course_id, false );
$course_rate     = $course_rate_res['rated'];
$total           = $course_rate_res['total'];

?>
<div class="course-rate">
	<?php
	learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $course_rate ) );
	?>
	<p class="review-number">
		<?php do_action( 'learn_press_before_total_review_number' ); ?>
		<?php printf( __( ' %1.2f average based on ', 'learnpress-course-review' ), $course_rate ); ?>
		<?php printf( _n( ' %d rating', '%d ratings', $total, 'learnpress-course-review' ), $total ); ?>
		<?php do_action( 'learn_press_after_total_review_number' ); ?>
	</p>
	<div>
		<?php
		if ( isset( $course_rate_res['items'] ) && !empty( $course_rate_res['items'] ) ):
			foreach ( $course_rate_res['items'] as $item ):
				$percent = round( $item['percent'], 0 );
				?>
				<div class="course-rate">
					<span><?php esc_html_e( $item['rated'] ); ?><?php _e( ' Star', 'learnpress-course-review' ); ?></span>
					<!--					<span>--><?php //esc_html_e( $item['total'] );
					?><!----><?php //_e( 'Rate', 'learnpress-course-review' );
					?><!--</span>-->
					<div class="review-bar">
						<div class="rating" style="width:<?php echo $percent; ?>% "></div>
					</div>
					<span><?php echo esc_html( $percent ); ?>%</span>
				</div>
				<?php
			endforeach;
		endif;
		?>
	</div>
</div>
