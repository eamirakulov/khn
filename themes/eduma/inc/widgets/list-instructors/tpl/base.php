<?php

$total = wp_count_posts( 'lp_course' );
$total = $total->publish;
if ( ! $total ) {
	echo '<p class="message message-error">' . esc_html__( 'There are no publish courses available yet.', 'eduma' ) . '</p>';

	return;
}

$autoplay     = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;

global $wpdb;
$query = $wpdb->prepare( "
	SELECT COUNT( u.ID ) as courses, u . *
	FROM {$wpdb->users} u
	INNER JOIN {$wpdb->postmeta} c ON u.ID = c.meta_value
	WHERE c.meta_key =  %s
	GROUP BY u.ID
	ORDER BY courses DESC
", '_lp_co_teacher');
$co_instructors = $wpdb->get_results($query);

$visible_item = 3;
if ( $instance['visible_item'] && $instance['visible_item'] != '' ) {
	$visible_item = (int) $instance['visible_item'];
}

if( count($co_instructors) < $visible_item ) {
	$visible_item = count($co_instructors);
}

$pagination = ( !empty($instance['show_pagination']) && $instance['show_pagination'] !== 'no' ) ? 1 : 0;

//$cout = _learn_press_count_users_enrolled_courses();
// Using $co_instructors
if ( ! empty( $co_instructors ) ) {
	$html = '<div class="thim-carousel-wrapper thim-carousel-list-instructors" data-visible="'.$visible_item.'" data-navigation="1" data-pagination="'.$pagination.'" data-autoplay="' . esc_attr( $autoplay ) . '">';
	foreach ( $co_instructors as $key => $instructor ) {
		$query_courses = $wpdb->prepare( "
            SELECT post_id
            FROM {$wpdb->postmeta}
            WHERE meta_value =  %s and meta_key = %s
        ", $instructor->ID, '_lp_co_teacher');
		$courses_of_ins = $wpdb->get_col($query_courses);
		$num_students = 0;
		for($i=0;$i<count($courses_of_ins);$i++) {
			$course = learn_press_get_course($courses_of_ins[$i]);
			$num_students += $course->count_users_enrolled();
			$ratings     = learn_press_get_course_rate_total( $courses_of_ins[$i] );
		}
		$text_review = ( number_format_i18n( $ratings ) > 1 ) ? number_format_i18n( $ratings ) . ' Reviews' : number_format_i18n( $ratings ) . ' Review ';

		$lp_info = get_the_author_meta( 'lp_info', $instructor->ID );
		$link    = learn_press_user_profile_link( $instructor->ID );
		$html .= '<div class="instructor-item"><div class="wrap-item">';
		$html .= '<div class="avatar_item">' . get_avatar( $instructor->ID, 450 ) . '</div>';
		$html .= '<div class="instructor-info">';
		$html .= '<h4 class="name" ><a href="'.$link.'">' . get_the_author_meta( 'display_name', $instructor->ID ) . '</a></h4>';
		if( isset($lp_info['major']) ){
			$html .= '<p class="job">' . $lp_info['major'] . '</p>';
		}
		$html .= '<div class="description">' . get_the_author_meta( 'description', $instructor->ID ) . '</div>';
		$html .= '<div class="info_ins">';
		$html .= '<div class="row">';
		$html .= '<div class="col-sm-6 reviews"><span class="lnr lnr-star"></span> (' . $text_review . ')</div>';
		$html .= '<div class="col-sm-6 students"><span class="lnr lnr-users"></span> ' . $num_students . ' ' . esc_html__( 'Students', 'eduma' ) . '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';


		$html .= '</div></div>';
	}
	$html .= '</div>';
}

echo  ent2ncr($html);

?>