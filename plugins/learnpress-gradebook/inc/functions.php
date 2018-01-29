<?php

function learn_press_gradebook_nonce_url( $args = array(), $field = 'gradebook-nonce' ) {
	$args = wp_parse_args( $args, array( 'course_id' => get_the_ID() ) );
	return wp_nonce_url( add_query_arg( $args, 'admin.php?page=course-gradebook' ), 'learn-press-gradebook-' . $args['course_id'], $field );
}

function learn_press_gradebook_verify_nonce( $course_id = 0, $nonce = 'gradebook-nonce' ) {
	if ( !$course_id ) {
		$course_id = get_the_ID();
	}
	return !empty( $_REQUEST[$nonce] ) ? wp_verify_nonce( $_REQUEST[$nonce], 'learn-press-gradebook-' . $course_id ) : false;
}

function learn_press_gradebook_get_lessons( $course_id, $args = array() ) {
	global $wpdb;
	static $graded_courses = array();

	$args      = wp_parse_args( $args, array( 's' => '', 'count' => true, 'from' => '', 'to' => '', 'start' => 0, 'limit' => 10 ) );
	$cache_key = md5( serialize( $args ) );
	if ( !empty( $graded_courses[$cache_key] ) ) {
		$results = $graded_courses[$cache_key];
	} else {
		$results = array(
			'items' => array(),
			'count' => 0
		);
		$where   = 'WHERE 1 ';

		// if is search
		$search = '';
		if ( $args['s'] ) {
			$s = '%' . $wpdb->esc_like( $args['s'] ) . '%';
			$search .= $wpdb->prepare( "AND (
				`u`.`user_login` LIKE %s
				OR `u`.`user_email` LIKE %s
				OR `u`.`display_name` LIKE %s
			)", $s, $s, $s );
		}

		// limit enroll time
		if ( !empty( $args['from'] ) && !empty( $args['to'] ) ) {
			$from_time = strtotime( $args['from'] );
			$to_time   = strtotime( $args['to'] );

			$from = date( 'Y-m-d H:i:s', mktime( 0, 0, 0, date( 'm', $from_time ), date( 'd', $from_time ), date( 'Y', $from_time ) ) );
			$to   = date( 'Y-m-d H:i:s', mktime( 23, 59, 59, date( 'm', $to_time ), date( 'd', $to_time ), date( 'Y', $to_time ) ) );
			$where .= $wpdb->prepare( "
				AND ( `ui`.`start_time` >= %s AND `ui`.`start_time` <= %s)
			", $from, $to );
		}

		// limit users per page
		if ( $args['limit'] > 0 ) {
			$limit = " LIMIT " . $args['start'] . ", " . $args['limit'];
		} else {
			$limit = '';
		}

		$query_count = "SELECT SQL_CALC_FOUND_ROWS user_id FROM("
			. $wpdb->prepare( "
				SELECT `ui`.`user_id`
				FROM `{$wpdb->prefix}learnpress_user_items` AS `ui`
				INNER JOIN `{$wpdb->prefix}users` AS `u` ON `u`.`ID` = `ui`.`user_id`
				$where 
					AND `ui`.`item_type` = 'lp_course' 
					AND ( `ui`.`status` = 'enrolled' OR `ui`.`status` = 'completed' OR `ui`.`status` = 'finished' )
					AND `ui`.`item_id` = %d
				" , $course_id ) . $search . " GROUP BY `ui`.`user_id`
			) as c
		" . $limit;
		$user_ids    = $wpdb->get_col( $query_count );

		if ( $user_ids ) {
			$rows_count = $wpdb->get_var( "SELECT FOUND_ROWS();" );
			$where .= " AND `ui`.`ref_id` = %d AND `ui`.`ref_type`='lp_course' AND `ui`.`item_type`='lp_lesson' ";
//			$where .= " AND uc.user_id IN(" . join( ',', array_fill( 0, sizeof( $user_ids ), '%d' ) ) . ")";
			$query = $wpdb->prepare( "
				SELECT si.item_id
				FROM {$wpdb->prefix}learnpress_sections cs
				INNER JOIN {$wpdb->prefix}learnpress_section_items si ON cs.section_id = si.section_id
				WHERE si.item_type = %s
				AND cs.section_course_id = %d
			", 'lp_lesson', $course_id );
			$lesson_ids = $wpdb->get_col( $query );

			$prepare_args = $user_ids;
			array_unshift( $prepare_args, $course_id );

			// `ul`.`lesson_id`, `ul`.`course_id`, `ul`.`start_time`, `ul`.`end_time`, `ul`.`status` ,uc.start_time as course_start_time, uc.status as course_status, uc.user_id, 
			$query = $wpdb->prepare( "
				SELECT 
					`ui`.*,
					`ui`.`item_id` `lesson_id`,
					`uix`.`user_id`, 
					`uix`.`item_type`,
					`uix`.`start_time` `course_start_time` ,
					`uix`.`status` `course_status`,
					`u`.`user_login`, `u`.`user_email`, `u`.`display_name`
				FROM `{$wpdb->prefix}learnpress_user_items` AS `ui`
				INNER JOIN `{$wpdb->prefix}learnpress_user_items` AS `uix` ON `uix`.`item_id`=`ui`.`ref_id` AND `ui`.`user_id`=`uix`.`user_id`
				INNER JOIN `{$wpdb->prefix}users` AS `u` ON `u`.`ID` = `ui`.`user_id`
				$where
			", $prepare_args ) . $search . " ORDER BY ui.start_time DESC";

			$rows = $wpdb->get_results( $query );

			if ( $lesson_ids && $rows = $wpdb->get_results( $query ) ) {
				$user_lessons = array_fill_keys( $lesson_ids, '' );
				for ( $i = 0, $n = sizeof( $rows ); $i < $n; $i ++ ) {
					$row = $rows[$i];
					if ( !array_key_exists( $row->user_id, $results['items'] ) ) {
						$results['items'][$row->user_id] = array(
							'user'              => array(
								'id'           => $row->user_id,
								'user_login'   => $row->user_login,
								'user_email'   => $row->user_email,
								'display_name' => $row->display_name
							),
							'items'             => $user_lessons,
							'items_completed'   => 0,
							'course_start_time' => $row->course_start_time,
							'status'            => $row->course_status
						);
					}
					if ( $row->lesson_id ) {
						$results['items'][$row->user_id]['items'][$row->item_id] = array(
							'type'   => 'lesson',
							'status' => $row->status,
							'start'  => $row->start_time,
							'end'    => $row->end_time
						);

						if ( $row->status == 'completed' ) {
							$results['items'][$row->user_id]['items_completed'] ++;
						}
					}
				}
				$results['count'] = $rows_count;
			}
		}
	}
	return $args['count'] ? $results : $results['items'];
}

function learn_press_gradebook_get_quizzes( $course_id, $args = array() ) {

	global $wpdb;
	static $graded_courses = array();

	$args      = wp_parse_args( $args, array( 's' => '', 'count' => true, 'from' => '', 'to' => '', 'start' => 0, 'limit' => 10 ) );
	$cache_key = md5( serialize( $args ) );
	if ( !empty( $graded_courses[$cache_key] ) ) {
		$results = $graded_courses[$cache_key];
	} else {
		$results = array(
			'items' => array(),
			'count' => 0
		);
		$where   = 'WHERE 1 ';

		// if is search
		$search = '';
		if ( $args['s'] ) {
			$s = '%' . $wpdb->esc_like( $args['s'] ) . '%';
			$search .= $wpdb->prepare( "AND (
				u.user_login LIKE %s
				OR u.user_email LIKE %s
				OR u.display_name LIKE %s
			)", $s, $s, $s );
		}

		// limit enroll time
		if ( !empty( $args['from'] ) && !empty( $args['to'] ) ) {
			$from_time = strtotime( $args['from'] );
			$to_time   = strtotime( $args['to'] );

			$from = date( 'Y-m-d H:i:s', mktime( 0, 0, 0, date( 'm', $from_time ), date( 'd', $from_time ), date( 'Y', $from_time ) ) );
			$to   = date( 'Y-m-d H:i:s', mktime( 23, 59, 59, date( 'm', $to_time ), date( 'd', $to_time ), date( 'Y', $to_time ) ) );
			$where .= $wpdb->prepare( "
			AND ( uc.start_time >= %s AND uc.start_time <= %s)
		", $from, $to );
		}

		// limit users per page
		if ( $args['limit'] > 0 ) {
			$limit = " LIMIT " . $args['start'] . ", " . $args['limit'];
		} else {
			$limit = '';
		}
// 		$where .= " AND uc.course_id = %d";
// 		$where .= " AND ui.item_id = %d";
		$where .= " AND uc.item_id = %d";


		# # # # # # # # # # # # # # # # # # # # # # # # # # # # #
		# 			GET ID OF USER ENROLLED COURSE 				#
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # #
		$query_count = "SELECT SQL_CALC_FOUND_ROWS user_id FROM("
			. $wpdb->prepare(
				"
					SELECT uc.user_id 
					FROM {$wpdb->prefix}learnpress_user_items uc
					$where AND uc.item_type='lp_course'
				", $course_id ) . $search . " GROUP BY uc.user_id
			) as u
		" . $limit;

		$user_ids 	= $wpdb->get_col( $query_count );
		$rows_count = $wpdb->get_var( "SELECT FOUND_ROWS();" );

		if ( $user_ids ) {

			$where .= " AND uc.user_id IN(" . join( ',', array_fill( 0, sizeof( $user_ids ), '%d' ) ) . ")";
			$query    = $wpdb->prepare( "
				SELECT si.item_id
				FROM {$wpdb->prefix}learnpress_sections cs
				INNER JOIN {$wpdb->prefix}learnpress_section_items si ON cs.section_id = si.section_id
				WHERE si.item_type = %s
				AND cs.section_course_id = %d
			", 'lp_quiz', $course_id );
			$quiz_ids = $wpdb->get_col( $query );
			$user_quizzes = array_fill_keys( $quiz_ids, '' );

			$prepare_args = $user_ids;
			array_unshift( $prepare_args, $course_id );

			// get list
			$query = $wpdb->prepare( "
				SELECT 
					`uq`.`item_id` AS `quiz_id`,
					`uc`.`item_id` AS `course_id`,
					`uc`.`start_time` AS `course_start_time`,
					`uc`.`status` AS `course_status`,
					`uc`.`end_time`,
					`uc`.`user_id`,
					`u`.`user_login`,
					`u`.`user_email`,
					`u`.`display_name`
				FROM
					`{$wpdb->prefix}learnpress_user_items` `uq`
						INNER JOIN
					`{$wpdb->prefix}learnpress_user_items` `uc` 
						ON `uc`.`item_id` = `uq`.`ref_id` 
							AND `uq`.`user_id` = `uc`.`user_id`
							AND `uc`.`item_type` = 'lp_course' 
						RIGHT JOIN
					`{$wpdb->prefix}users` `u` ON `u`.`ID` = `uq`.`user_id`
				$where
			", $prepare_args ) . $search . " ORDER BY uc.start_time DESC";

			$rows = $wpdb->get_results( $query );
			if ( $quiz_ids && !empty( $rows ) ) {
				$sizeofrows = sizeof( $rows );
				for ( $i = 0; $i < $sizeofrows; $i ++ ) {
					$row = $rows[$i];
					if ( ! isset( $results['items'][$row->user_id] ) ) {
						$results['items'][$row->user_id] = array(
							'user'              => array(
								'id'           => $row->user_id,
								'user_login'   => $row->user_login,
								'user_email'   => $row->user_email,
								'display_name' => $row->display_name
							),
							'items'             => $user_quizzes,
							'items_completed'   => 0,
							'course_start_time' => $row->course_start_time,
							'status'            => $row->course_status
						);
					}


					$user = learn_press_get_user( $row->user_id );
					if ( $user ) {
						$f = $user->get_quiz_results( $row->quiz_id, $course_id );
						if ( $f ) {
							$results['items'][$row->user_id]['items'][$row->quiz_id] = array(
								'type'   => 'quiz',
								'status' => $f->status
							);
							if ( $f->status == 'completed' ) {
								$results['items'][$row->user_id]['items_completed'] ++;
							}
						}
					}
				}
				$results['count'] = $rows_count;
			}
		}
	}
// exit(''.__LINE__);

	return $args['count'] ? $results : $results['items'];
}

/**
 * @param       $user_id
 * @param array $args
 *
 * @return array
 */
function learn_press_gradebook_get_user_lessons( $user_id, $args = array() ) {
	static $courses = array();
	if ( !empty( $courses[$user_id] ) ) {
		$results = $courses[$user_id];
	} else {
		$results = array(
			'items' => array(),
			'count' => 0
		);
		global $wpdb;
		$user = learn_press_get_user( $user_id );
		if ( $enrolled_courses = $user->get( 'enrolled-courses' ) ) {
			$enrolled_courses = array_keys( $enrolled_courses );
		}
		$args  = wp_parse_args( $args, array( 's' => '', 'count' => true, 'from' => '', 'to' => '', 'start' => 0, 'limit' => 10 ) );
		$where = '';

		if ( $args['limit'] > 0 ) {
			$limit = " LIMIT " . $args['start'] . ", " . $args['limit'];
		} else {
			$limit = '';
		}
		
		$where .= " AND `ui`.`user_id` = %d AND `ui`.`ref_type`='lp_course' AND `ui`.`item_type`='lp_lesson' ";
		$query = $wpdb->prepare( "
				SELECT 
					`ui`.*,
					`ui`.`ref_id` `course_id`,
					`ui`.`item_id` `lesson_id`,
					`uix`.`user_id`, 
					`uix`.`item_type`,
					`uix`.`start_time` `course_start_time` ,
					`uix`.`status` `course_status`,
					`u`.`user_login`, `u`.`user_email`, `u`.`display_name`
				FROM `{$wpdb->prefix}learnpress_user_items` AS `ui`
				INNER JOIN `{$wpdb->prefix}learnpress_user_items` AS `uix` ON `uix`.`item_id`=`ui`.`ref_id` AND `ui`.`user_id`=`uix`.`user_id`
				INNER JOIN `{$wpdb->prefix}users` AS `u` ON `u`.`ID` = `ui`.`user_id`
				$where
			", $user_id ) . " ORDER BY ui.start_time DESC";

		$key   = md5( $query );
		
		$rows = $wpdb->get_results( $query );

		if ( !empty($rows) ) {
			$rows_count = count($rows);
			//$user_lessons = array_fill_keys( $lesson_ids, '' );
//			$rows_count = $wpdb->get_var( "SELECT FOUND_ROWS();" );
			$items = array();
			for ( $i = 0, $n = sizeof( $rows ); $i < $n; $i ++ ) {
				$row = $rows[$i];
				if ( !array_key_exists( $row->course_id, $items ) ) {
					$items[$row->course_id] = array(
						'items'             => array(),//$user_lessons,
						'items_completed'   => 0,
						'course_start_time' => $row->course_start_time,
						'status'            => $row->course_status
					);
				}

				if ( $row->course_id ) {
					$items[$row->course_id]['items'][$row->lesson_id] = array(
						'type'   => 'lesson',
						'status' => $row->status,
						'start'  => $row->start_time,
						'end'    => $row->end_time
					);

					if ( $row->status == 'completed' ) {
						$items[$row->course_id]['items_completed'] ++;
					}
				}
			}
			
			return $items;
		}
	}
	return $args['count'] ? $results : $results['items'];
}

function learn_press_get_user_graded_courses( $user_id ) {
	global $wpdb;
	$user              = learn_press_get_user( $user_id );
	$purchased_courses = $user->get( 'purchased-course' );

	$query = $wpdb->prepare( "

	" );
}

function learn_press_gradebook_template( $name, $args = null ) {
	learn_press_get_template( $name, $args, learn_press_template_path() . '/addons/gradebook/', LP_GRADEBOOK_TMPL . '/' );
}

function learn_press_gradebook_get_user_courses_enrolled($user_id=null){
	global $wpdb;
	if( !$user_id ){
		$user_id = learn_press_get_current_user_id();
	}
	$sql = "SELECT ui.item_id, ui.* "
			. " FROM {$wpdb->prefix}learnpress_user_items as ui "
			. " WHERE ui.item_type='lp_course' and ui.user_id=%d";
	$query = $wpdb->prepare( $sql, $user_id );
	$results = $wpdb->get_results($query, OBJECT_K);
	return $results;
}

/**
 * Get data to show in gradebook tab in profile
 * @global type $wpdb
 * @param type $user_id
 * @param type $course_id
 * @return array
 */
function learn_press_gradebook_get_user_datas( $user_id=null, $course_ids=array() ){
	global $wpdb;
	if( !$user_id ){
		$user_id = learn_press_get_current_user_id();
	}

	$user		= learn_press_get_user( $user_id );
	$course_ids = is_array($course_ids)?$course_ids:array($course_ids);
	if( empty( $course_ids ) ){
		$courses_enrolled	= learn_press_gradebook_get_user_courses_enrolled($user_id);
		$items = array();
		if(empty($courses_enrolled)){
			return $items;
		}

		$course_ids			= array_keys( $courses_enrolled );
	}

	$query_course_ids	= implode(',', $course_ids);
	$sql = "
			SELECT 
				`p`.`ID` 
				,`p`.`post_title` 
				,ui.user_id 
				,`ui`.* 
				,`s`.* 
				,`p`.`post_status` 
				#`p`.`post_name`, 
				,`p`.`post_type` 
				,ui.item_id 

			FROM `{$wpdb->prefix}posts` as `p`
				left join `{$wpdb->prefix}learnpress_section_items` as `si` on `p`.`ID`=`si`.`item_id`
				left join `{$wpdb->prefix}learnpress_sections` as `s` on `si`.`section_id`=`s`.`section_id` 
				left join `{$wpdb->prefix}learnpress_user_items` as `ui` on `p`.`ID`=`ui`.`item_id` and `p`.`post_type`=LOWER(CONVERT(`ui`.`item_type` USING latin1)) and ui.user_id=%d
			WHERE 
				`p`.`post_status` = 'publish' 
				AND (p.post_type='lp_lesson' or p.post_type='lp_quiz') 
				AND s.section_course_id 
					IN ({$query_course_ids})
			ORDER BY section_course_id, section_order , item_order, ui.user_item_id DESC";
	$query 	= $wpdb->prepare( $sql, $user_id );
	$rows 	= $wpdb->get_results( $query );
	$items 	= array();
	if ( !empty( $rows ) ) {
		for ( $i = 0, $n = sizeof( $rows ); $i < $n; $i ++ ) {
			$row		= $rows[$i];
			$course_id  = $row->section_course_id;
			if( isset($items[$course_id]['items'][$row->ID])) {
				continue;
			}
			if ( !array_key_exists( $course_id, $items ) ) {
				$course = $user->get_course_info($course_id);
				$course_result = get_post_meta( $course_id, '_lp_course_result', true );
				$items[$course_id] = array(
					'items'             => array(),//$user_lessons,
					'course_result'		=> $course_result,
					'items_completed'	=> 0,
					'items_started'		=> 0,
					'items_number'		=> 0,
					'items_pass'		=> 0,
					'final_quiz_id'		=> 0,
					'course_start_time' => $course['start'],
					'status'            => $course['status'],
					'course_completed'	=> 0,
					'processed'			=> 0
				);
				if( $course_result == 'evaluate_final_quiz' ) {
					$quiz_id	= learn_press_gradebook_get_final_quiz_id( $course_id );
					$items[$course_id]['final_quiz_id'] = $quiz_id;
					$quiz_res = $user->get_quiz_results( $quiz_id , $course_id);
					$items[$course_id]['processed'] = ( $quiz_res && $quiz_res->status=='completed')?100:0;
				}
			}

			// - - - - - - - - - - - - - - - - - - - - - - - -
			if( !isset($items[$course_id]['items'][$row->ID]) ) {
				$items[$course_id]['items'][$row->ID] = $row;
			} else {
				continue;
			}

			if($row->post_type=='lp_quiz' && $row->status == 'completed'){
				$quiz_id = $row->ID;
				$quiz_res = $user->get_quiz_results( $quiz_id, $course_id );
				$passing_grade = (int)get_post_meta($quiz_id, '_lp_passing_grade', true);
				$quiz_res->passing_grade = ( $quiz_res->correct_percent >= $passing_grade ) ? 'pass' : 'fail';
				$items[$course_id]['items'][$row->ID]->quiz_res = $quiz_res;
			}
			if ( $items[$course_id]['course_result'] == 'evaluate_lesson' && $row->post_type=='lp_lesson' ) {
				
				if ( $row->status == 'completed' ) {
					$items[$course_id]['items_completed'] ++;
				}
				if ( $row->status == 'started' ) {
					$items[$course_id]['items_started'] ++;
				}
				$items[$course_id]['items_number']++;
			}elseif( $items[$course_id]['course_result'] == 'evaluate_quizzes' && $row->post_type=='lp_quiz' ){
				if( isset($items[$course_id]['items'][$row->ID]->quiz_res) 
					&& $items[$course_id]['items'][$row->ID]->quiz_res->passing_grade=='pass' ) {
					$items[$course_id]['items_completed'] ++;
				}
				$items[$course_id]['items_number']++;
			}

			if( $i > 0 && ( $course_id!=$rows[$i-1]->section_course_id || (int)$i == (int)($n-1) ) ){
				if( $items[$course_id]['course_result'] == 'evaluate_quizzes' ) {

				}
				if( in_array( $items[$course_id]['course_result'], array('evaluate_quizzes', 'evaluate_lesson')) && $items[$rows[$i-1]->section_course_id]['items_completed'] ) {
					$items[$rows[$i-1]->section_course_id]['processed']= (int)$items[$rows[$i-1]->section_course_id]['items_completed']/(int)$items[$rows[$i-1]->section_course_id]['items_number']*100;
				}
			}
			
		}

		return $items;
	}

}


function learn_press_gradebook_get_final_quiz_id( $course_id ) {
	global $wpdb;
	$sql = "SELECT p.ID 
			FROM `{$wpdb->prefix}learnpress_sections` as `s`
				INNER join `{$wpdb->prefix}learnpress_section_items` as `si` 
					on `s`.`section_id`=`si`.`section_id` 
				RIGHT join `{$wpdb->prefix}posts` as `p`
					on si.item_id=p.ID and p.post_type='lp_quiz'
			WHERE `s`.`section_course_id`=%d 
			ORDER BY `s`.`section_order` DESC, `si`.`item_order` DESC LIMIT 1";
	$query	= $wpdb->prepare( $sql, $course_id );
	$res	= $wpdb->get_var( $query );
	return $res;
}


function learn_press_gradebook_get_user_quiz_answers($quiz_id, $course_id, $user_id=null){
	global $wpdb;
	if(!$user_id){
		$user_id = learn_press_get_current_user_id();
	}
	$sql = "
		SELECT 
			*
		FROM
			`{$wpdb->prefix}learnpress_user_items` `ui`
				INNER JOIN
			`{$wpdb->prefix}learnpress_user_itemmeta` `uim` ON `ui`.`user_item_id` = `uim`.`learnpress_user_item_id`
				AND `uim`.`meta_key` = 'question_answers'
		WHERE `ui`.`item_type` = 'lp_quiz' 
			AND `ui`.`item_id` = %d 
			AND `ui`.`ref_id` = %d 
			AND `ui`.`user_id` = %d
		ORDER BY `ui`.`user_item_id` DESC
	";
	$query	= $wpdb->prepare( $sql, array($quiz_id, $course_id, $user_id) );
	$row	= $wpdb->get_row( $query );
	return $row;
}

/**
 * get quiz result
 * @global type $wpdb
 * @param type $quiz_id
 * @param type $course_id
 * @param type $user_id
 * @return type array
 */
function learn_press_gradebook_get_user_quiz_result($quiz_id, $course_id=null, $user_id=null){
	global $wpdb;
	$debug = isset($_REQUEST['_lp_debug_']);
	$quiz_id = (int)$quiz_id;
	if($debug ){
		echo '<b>$quiz_id:</b><pre>'.print_r( $quiz_id, true ) . '</pre>';
	}
	# GET QUESTIONS ID AND RIGHT ANSWER
	$sql = "SELECT 
				*
			FROM
				{$wpdb->prefix}learnpress_quiz_questions qq
					LEFT JOIN
				{$wpdb->prefix}learnpress_question_answers qa ON qq.question_id = qa.question_id
					AND qa.answer_data LIKE '%\"yes\";}'
			WHERE qq.quiz_id={$quiz_id} ORDER BY `qq`.`question_id`";

	$rows = $wpdb->get_results( $sql );
	if( !$rows || empty($rows) ){
		return array(
			'questions_total' => 0
			,'answered' => 0
			,'corrected' => 0
			,'wrong' => 0
			,'corrected_percent' => 0
			,'status' => null
		);
	}
	if($debug){
		echo '<b>$sql:</b><pre>'.print_r($sql, true).'</pre>';
//		echo '<b>$rows:</b><pre>'.print_r($rows, true).'</pre>';
	}
	
	# get quiz questions
	$user_answers	= learn_press_gradebook_get_user_quiz_answers( $quiz_id, $course_id, $user_id );
	$answers = array();
	if( $user_answers && isset($user_answers->meta_value)){
		$answers		= unserialize( $user_answers->meta_value );
	}
	
	if( $debug ) {
		echo '<b>$answers:</b><pre>' . print_r( $answers, true ) . '</pre>';
	}

	$questions = array();
	foreach ( $rows as $row ){
		if( !isset( $questions[$row->question_id] ) ) {
			$questions[$row->question_id] = array();
		}
		$answer_data = unserialize( $row->answer_data );
		$questions[$row->question_id][]=$answer_data['value'];
	}
	
	$answered		= count( array_keys( $answers ) );
	$result			= array(
		'questions_total' => count($questions)
		,'answered'=>$answered
		,'corrected' => 0
		,'wrong' =>0
		,'corrected_percent' =>0
	);
	
	foreach ( $answers as $answer_item ) {
		$answer_item = is_array( $answer_item ) ? $answer_item : array( $answer_item );
		if( in_array( $answer_item, $questions ) ){
			$result['corrected']++;
		}
	}

	echo '<pre>'.print_r($questions, true).'</pre>';
	echo '<pre>'.print_r($answers, true).'</pre>';
	
//	var_dump( $result['questions_total'] );
//	
//	var_dump($result['answered']);
	echo "\n<br/>---------------------------<br/>";
	$result['questions'] = $questions;
	$result['answers'] = $answers;
	$result['wrong'] = (int)$result['answered']	- (int)$result['corrected'];
	$result['empty'] = (int)$result['questions_total'] - (int)$result['answered'];
	$result['corrected_percent'] = (int)$result['answered']/$result['questions_total']*100;
	
	$passing_grade = (int)get_post_meta($quiz_id, '_lp_passing_grade', true);
	$result['status'] = $result['corrected_percent']>$passing_grade?'pass':'fail';

	return $result;
}


function learn_press_gradebook_set_transient($key, $value){
	$temp_dir = get_temp_dir();
	$export_dir = untrailingslashit($temp_dir).DIRECTORY_SEPARATOR.'gradebook-export'.DIRECTORY_SEPARATOR;
	if( !( file_exists($export_dir) && is_dir($export_dir) ) ) {
		wp_mkdir_p($export_dir);
	}
	$content = json_encode($value, true);
	$file_tmp = $export_dir.$key;
	$return = file_put_contents($file_tmp, $content);
	return $return;
}

function learn_press_gradebook_get_transient($key, $cache_time=-1 ){
	$temp_dir = get_temp_dir();
	$export_dir = $temp_dir.DIRECTORY_SEPARATOR.'gradebook-export'.DIRECTORY_SEPARATOR;
	if( !( file_exists($export_dir) && is_dir($export_dir) ) ) {
		return false;
	}
	$file_tmp = $export_dir.$key;
	$content = file_get_contents($file_tmp);
	$value = json_decode($content);
	return $value;
}

function learn_press_gradebook_map_callback( $item ) {
	return $item->item_id;
}
