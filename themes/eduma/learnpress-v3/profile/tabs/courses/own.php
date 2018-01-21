<?php
/**
 * Template for displaying own courses in courses tab of user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/courses/own.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();


$limit             = LP()->settings->get( 'profile_courses_limit', 10 );
$limit             = apply_filters( 'learn_press_profile_tab_courses_all_limit', $limit );
$profile       = learn_press_get_profile();
$filter_status = LP_Request::get_string( 'filter-status' );
$query         = $profile->query_courses( 'own', array( 'status' => $filter_status, 'limit'  => $limit ) );
?>

<div class="learn-press-subtab-content">

	<?php if ( $filters = $profile->get_own_courses_filters( $filter_status ) ) { ?>
        <ul class="lp-sub-menu">
			<?php foreach ( $filters as $class => $link ) { ?>
                <li class="<?php echo $class; ?>"><?php echo $link; ?></li>
			<?php } ?>
        </ul>
	<?php } ?>

	<?php if ( ! $query['total'] ) {
		learn_press_display_message( __( 'No courses!', 'learnpress' ) );
	} else { ?>

        <div class="thim-course-grid profile-courses-list">
			<?php
			global $post;
			foreach ( $query['items'] as $item ) {
				$course = learn_press_get_course( $item );
				$post   = get_post( $item );
				setup_postdata( $post );
				learn_press_get_template( 'content-course.php' );
			}
			wp_reset_postdata();
			?>
        </div>

        <nav class="learn-press-pagination navigation pagination">
            <?php
            echo paginate_links( apply_filters( 'learn_press_pagination_args', array(
                'base'         => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
                'format'       => '',
                'add_args'     => '',
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'total'        => $query['total'],
                'prev_text'    => '&larr;',
                'next_text'    => '&rarr;',
                'type'         => 'list',
                'end_size'     => 3,
                'mid_size'     => 3
            ) ) );
            ?>
        </nav>

	<?php } ?>
</div>
