<?php
/**
 * Reviews functions
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @param int     $course_id
 * @param int     $paged
 * @param int     $per_page
 * @param boolean $force
 *
 * @return mixed|void
 */
function learn_press_get_course_review( $course_id, $paged = 1, $per_page = LP_ADDON_COURSE_REVIEW_PER_PAGE, $force = false ) {
	static $course_reviews = array();
	if ( !$force && !empty( $course_reviews[$course_id] ) ) {
		return $course_reviews[$course_id];
	}
	global $wpdb;
	$per_page = absint( apply_filters( 'learn_press_course_reviews_per_page', $per_page ) );
	$paged    = absint( $paged );
	if ( $per_page == 0 ) {
		$per_page = 9999999;
	}
	if ( $paged == 0 ) {
		$paged = 1;
	}
	$start    = ( $paged - 1 ) * $per_page;
	$start    = max( $start, 0 );
	$per_page = max( $per_page, 1 );
	$results  = array(
		'reviews'  => array(),
		'paged'    => $paged,
		'total'    => 0,
		'per_page' => $per_page
	);

	$query         = $wpdb->prepare( "
        SELECT SQL_CALC_FOUND_ROWS u.*, c.comment_ID as comment_id, cm1.meta_value as title, c.comment_content as content, cm2.meta_value as rate
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->comments} c ON p.ID = c.comment_post_ID
        INNER JOIN {$wpdb->users} u ON u.ID = c.user_id
        INNER JOIN {$wpdb->commentmeta} cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key = %s
        INNER JOIN {$wpdb->commentmeta} cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key = %s
        WHERE p.ID = %d AND c.comment_type = %s AND c.comment_approved = %d
        ORDER BY c.comment_date DESC
        LIMIT %d, %d
    ", '_lpr_review_title', '_lpr_rating', $course_id, 'review', 1, $start, $per_page );
	$course_review = $wpdb->get_results( $query );
	if ( $course_review ) {
		$ratings            = _learn_press_get_ratings( $course_id );
		$results['reviews'] = $course_review;
		$results['total']   = $ratings[$course_id]['total'];// $wpdb->get_var( "SELECT FOUND_ROWS();" );
		if ( $results['total'] <= $start + $per_page ) {
			$results['finish'] = true;
		}
	}
	$course_reviews[$course_id] = $results;
	return $results;
}

function _learn_press_get_ratings( $course_id ) {
	global $wpdb;
	static $course_rates = array();
	if ( !is_array( $course_id ) ) {
		$ids = array( $course_id );
	} else {
		$ids = $course_id;
	}
	foreach ( $ids as $cid ) {
		if ( !isset( $course_rates[$cid] ) ) {
			$course_rates[$cid] = leanr_press_get_ratings_result( $cid );
		}
	}
	return $course_rates;
}


/**
 * @param $course_id
 * @param $field
 *
 * @return mixed|void
 */
function learn_press_get_course_rate( $course_id, $field = 'rated' ) {
	$ratings = _learn_press_get_ratings( $course_id );
	$rate    = ($field && array_key_exists( $field, $ratings[$course_id] )) ? $ratings[$course_id][$field] : $ratings[$course_id];
	return apply_filters( 'learn_press_get_course_rate', $rate );
}

function learn_press_get_course_rate_total( $course_id, $field = 'total' ) {
	/*global $wpdb;
	$query = $wpdb->prepare( "
        SELECT COUNT(*)
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->comments} c ON p.ID = c.comment_post_ID
        INNER JOIN {$wpdb->users} u ON u.ID = c.user_id
        INNER JOIN {$wpdb->commentmeta} cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key=%s
        INNER JOIN {$wpdb->commentmeta} cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
        WHERE p.ID=%d AND c.comment_type=%s and c.comment_approved = %d
        ORDER BY c.comment_date DESC",
		'_lpr_review_title', '_lpr_rating', $course_id, 'review', 1
	);
	$total = $wpdb->get_var( $query );*/

	$ratings = _learn_press_get_ratings( $course_id );
	$total   = array_key_exists( $field, $ratings[$course_id] ) ? $ratings[$course_id][$field] : $ratings;
	return apply_filters( 'learn_press_get_course_rate_total', $total );
}

/**
 * @param $course_id
 * @param $user_id
 *
 * @return mixed|void
 */
function learn_press_get_user_review_title( $course_id, $user_id ) {
	$course_review = get_post_meta( $course_id, '_lpr_course_review', true );
	if ( $course_review && array_key_exists( $user_id, $course_review['review_title'] ) ) {
		return apply_filters( 'learn_press_get_user_review', $course_review['review_title'][$user_id] );
	}
	return false;
}

/**
 * @param $course_id
 * @param $user_id
 *
 * @return mixed|void
 */
function learn_press_get_user_rate( $course_id = null, $user_id = null ) {
	if ( !$user_id ) $user_id = get_current_user_id();
	if ( !$course_id ) $course_id = get_the_ID();

	$comment = false;
	global $wpdb;
	$query    = $wpdb->prepare( "
    	SELECT *
    	FROM {$wpdb->posts} p
    	INNER JOIN {$wpdb->comments} c ON c.comment_post_ID = p.ID
    INNER JOIN
        {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
            AND cm.meta_key = '_lpr_rating'
    	WHERE c.comment_post_ID = %d AND user_id = %d
    ", $course_id, $user_id );
	
	$comments = $wpdb->get_results( $query );
	if ( $comments ) {
		$comment                = $comments[0];
		$comment->comment_title = get_comment_meta( $comment->comment_ID, '_lpr_review_title', true );
		$comment->rating        = get_comment_meta( $comment->comment_ID, '_lpr_rating', true );
	}
	return $comment;
}

/**
 * @param array
 *
 * @return int
 */
function learn_press_add_course_review( $args = array() ) {
	$args        = wp_parse_args(
		$args,
		array(
			'title'     => '',
			'content'   => '',
			'rate'      => '',
			'user_id'   => 0,
			'course_id' => 0
		)
	);
	$user_id     = $args['user_id'];
	$course_id   = $args['course_id'];
	$user_review = learn_press_get_user_rate( $course_id, $user_id );
	$comment_id  = 0;
	if ( !$user_review ) {
		$user       = get_user_by( 'id', $user_id );
		$comment_id = wp_new_comment(
			array(
				'comment_post_ID'      => $course_id,
				'comment_author'       => 'LearnPress',
				'comment_author_email' => $user->user_email,
				'comment_author_url'   => '',
				'comment_content'      => $args['content'],
				'comment_parent'       => 0,
				'user_id'              => $user->ID,
				'comment_approved'     => 1,
				'comment_type'         => 'review' // let filter to not display it as comments
			)
		);
	}
	if ( $comment_id ) {
		add_comment_meta( $comment_id, '_lpr_rating', $args['rate'] );
		add_comment_meta( $comment_id, '_lpr_review_title', $args['title'] );
	}

	return $comment_id;
}

function learn_press_course_review_template( $name, $args = null ) {
	learn_press_get_template( $name, $args, learn_press_template_path() . '/addons/course-review/', LP_ADDON_COURSE_REVIEW_TMPL );
}

/*
add_action( 'learn_press_after_courses_loop_item', function () {
	global $post;
	echo "[", learn_press_get_course_rate_total( $post->ID ), "]";
} );*/

/*
 * If we are viewing archive course page, so load all ratings of courses
 * into cache to reduce queries and time rather than load separate course
 * in loop
 */
add_filter( 'the_posts', 'learn_press_init_courses_review' );
function learn_press_init_courses_review( $posts ) {
	if ( $posts ) {
		$pIds = array();
		foreach ( $posts as $p ) {
			$pIds[] = $p->ID;
		}
		_learn_press_get_ratings( $pIds );
	}
	return $posts;
}

/*
function learn_press_add_course_review( $args = array() ) {
	$args        = wp_parse_args(
		$args,
		array(
			'title'     => '',
			'content'   => '',
			'rate'      => '',
			'user_id'   => 0,
			'course_id' => 0
		)
	);
	$user_id     = $args['user_id'];
	$course_id   = $args['course_id'];
	$user_review = learn_press_get_user_rate( $course_id, $user_id );//learn_press_get_user_review_title( $course_id, $user_id );
	if ( !$user_review ) {
		$review_title   = $_POST['review_title'];
		$review_content = $_POST['review_content'];
		$review_rate    = $_POST['review_rate'];
		learn_press_save_course_review( $course_id, $review_rate, $review_title, $review_content );
	}
	global $post;
	$post = get_post( $course_id );
	setup_postdata( $post );
	learn_press_course_review_template( 'course-rate.php' );
	die();
}

add_action( 'wp_ajax_learn_press_add_course_review', 'learn_press_add_course_review' );
*/
/**
 * Print rate for course
 */
function leanr_press_get_ratings_result( $course_id = '', $get_items = false ) {
	global $wpdb;
	$query = $wpdb->prepare( "
			SELECT 
				COUNT(*) `count` ,AVG(cm.meta_value) `avg`
			FROM
				{$wpdb->comments} c
					INNER JOIN
				{$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND meta_key='_lpr_rating'
			WHERE
				c.comment_approved = 1
					AND c.comment_type = 'review'
					AND c.user_id > 0
					AND c.comment_post_ID =%d "
		, $course_id );
	$row   = $wpdb->get_row( $query );

	$count = 0;
	$avg   = 0;
	if ( $row ) {
		$count = $row->count;
		$avg   = $row->avg;
	}

	$rows = array();
	if ( $count != 0 ):
		$query = $wpdb->prepare( "
				SELECT 
					cm.meta_value `rate`, COUNT(*) `count`
				FROM
					{$wpdb->comments} c
						INNER JOIN
					{$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND meta_key='_lpr_rating'
				WHERE
					c.comment_approved = 1
						AND c.comment_type = 'review'
						AND c.user_id > 0
						AND c.comment_post_ID =%d 
				GROUP BY `cm`.`meta_value`
				ORDER BY `cm`.`meta_value` DESC
			"
			, $course_id );
		$rows  = $wpdb->get_results( $query, OBJECT_K );
	endif;

	$items = array();
	for ( $i = 5; $i > 0; $i -- ) {
		if ( isset( $rows[$i] ) ) {
			$items[] = array(
				'rated' => $rows[$i]->rate
			, 'total'   => $rows[$i]->count
			, 'percent' => $rows[$i]->count / $count * 100
			);
		} else {
			$items[] = array(
				'rated' => $i
			, 'total'   => 0
			, 'percent' => 0
			);
		}
	}
	$result = array(
		'course_id' => $course_id
	, 'total'       => $count
	, 'rated'       => $avg
	, 'items'       => $items
	);

	return $result;
}