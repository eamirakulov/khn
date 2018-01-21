<?php

global $post, $wpdb;
$limit        = $instance['limit'];
$item_visible = $instance['slider-options']['item_visible'];
$pagination   = $instance['slider-options']['show_pagination'] ? $instance['slider-options']['show_pagination'] : 0;
$navigation   = $instance['slider-options']['show_navigation'] ? $instance['slider-options']['show_navigation'] : 0;
$autoplay     = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;
$featured     = !empty( $instance['featured'] ) ? true : false;
$condition    = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);
$sort         = $instance['order'];

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {

	}
} elseif ( $sort == 'popular' ) {
    $curd = new LP_Course_CURD();
    $query = $curd->get_popular_courses( array( 'limit' => (int) $limit ) );
} else {
    $query = $wpdb->prepare(
        "SELECT DISTINCT p.ID FROM $wpdb->posts AS p
						WHERE p.post_type = %s
						AND p.post_status = %s
						ORDER BY p.post_date {$order}
						LIMIT %d
					",
        LP_COURSE_CPT, 'publish', $limit
    );
}

$courses = learn_press_get_widget_course_object( $query );
if ( $courses ) :
    if ( $instance['title'] ) {
        echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
    }

    ?>
    <div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="<?php echo esc_attr( $item_visible ); ?>"
         data-pagination="<?php echo esc_attr( $pagination ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
        <?php foreach ( $courses as $course ) { ?>
            <div class="course-item">
                <?php
                echo '<div class="course-thumbnail">';
                echo '<a class="thumb" href="' . esc_url(get_the_permalink($course->get_id())) . '" >';
                echo thim_get_feature_image(get_post_thumbnail_id($course->get_id()), 'full', apply_filters('thim_course_thumbnail_width', 450), apply_filters('thim_course_thumbnail_height', 450), $course->get_title());
                echo '</a>';
                thim_course_wishlist_button($course->get_id());
                echo '<a class="course-readmore" href="' . esc_url(get_the_permalink($course->get_id())) . '">' . esc_html__('Read More', 'eduma') . '</a>';
                echo '</div>';
                ?>
                <div class="thim-course-content">
                    <?php
                    echo $course->get_instructor_html();
                    ?>
                    <h2 class="course-title">
                        <a href="<?php echo esc_url(get_the_permalink($course->get_id())); ?>"> <?php echo $course->get_title(); ?></a>
                    </h2>

                    <div class="course-meta">
                        <?php echo $course->get_users_enrolled(); ?>
                        <?php thim_course_ratings_count(); ?>
                        <?php thim_course_loop_price_html( $course );?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
endif;

wp_reset_postdata();
