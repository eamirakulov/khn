<?php
/**
 * Custom functions for LearnPress 3.x
 *
 * @package thim
 */


if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_begin_meta', 10 );
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_price', 20 );
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_instructor', 25 );
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_end_meta', 30 );
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_course_loop_item_buttons', 35 );
        remove_action( 'learn-press/after-courses-loop-item', 'learn_press_course_loop_item_user_progress', 40 );
        remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb', 10 );
        remove_action( 'learn-press/before-main-content', 'learn_press_search_form', 15 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_start_wrapper', 5 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_students', 10 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_end_wrapper', 15 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_price', 25 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_buttons', 30 );
        remove_action( 'learn-press/content-landing-summary', 'learn_press_course_instructor', 35 );
        remove_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'learn_press_item_meta_duration', 5 );
        remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_item_meta_duration', 10 );
        remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_quiz_meta_questions', 5 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_start_wrapper', 10 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_status', 15 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_students', 20 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_end_wrapper', 25 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_progress', 30 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_buttons', 40 );
        remove_action( 'learn-press/content-learning-summary', 'learn_press_course_instructor', 45 );
        remove_action( 'learn-press/course-buttons', 'learn_press_course_continue_button', 25 );
        remove_action( 'learn-press/parse-course-item', 'learn_press_control_displaying_course_item' );
        remove_action( 'learn-press/before-profile-nav', 'learn_press_profile_mobile_menu', 5 );
        remove_action( 'learn-press/quiz-buttons', 'learn_press_course_finish_button', 50 );
        remove_action( 'learn-press/quiz-buttons', 'learn_press_quiz_result_button', 35 );
        remove_action( 'learn-press/after-content-item-summary/lp_quiz', 'learn_press_content_item_summary_question_numbers', 10 );
        add_action( 'learn-press/parse-course-item', function (){
            remove_action('wp_print_scripts', 'learn_press_content_item_script');
        }, 10 );
        add_action('wp_enqueue_scripts', function(){
            wp_dequeue_style('learn-press');
        }, 10000);

        if ( thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' && class_exists( 'LP_Addon_Wishlist' ) ) && is_user_logged_in() ) {
            $addon_wishlist = LP_Addon_Wishlist::instance();
            remove_action( 'learn_press_content_learning_summary', array( $addon_wishlist, 'wishlist_button' ), 100 );
        }

        add_action('learn_press_before_single_course_curriculum', 'learn_press_course_buttons', 10);
	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );

if ( ! function_exists( 'thim_learnpress_page_title' ) ) {
	function thim_learnpress_page_title( $echo = true ) {
		$title = '';
		if ( get_post_type() == 'lp_course' && ! is_404() && ! is_search() || learn_press_is_courses() || learn_press_is_course_taxonomy() ) {
			if ( learn_press_is_course_taxonomy() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'All Courses', 'eduma' );
			}
		}
		if ( get_post_type() == 'lp_quiz' && ! is_404() && ! is_search() ) {
			if ( is_tax() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'Quiz', 'eduma' );
			}
		}
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 * Breadcrumb for LearnPress
 */
if ( ! function_exists( 'thim_learnpress_breadcrumb' ) ) {
	function thim_learnpress_breadcrumb() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_single() ) {

			$categories = get_the_terms( $post, 'course_category' );

			if ( get_post_type() == 'lp_course' ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '" title="' . esc_attr( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '"><span itemprop="name">' . esc_html( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '</span></a></li>';
			}

			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $categories[0] ) ) . '" title="' . esc_attr( $categories[0]->name ) . '"><span itemprop="name">' . esc_html( $categories[0]->name ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';

		} else if ( learn_press_is_course_taxonomy() || learn_press_is_course_tag() ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( learn_press_single_term_title( '', false ) ) . '">' . esc_html( learn_press_single_term_title( '', false ) ) . '</span></li>';
		} else if ( ! empty( $_REQUEST['s'] ) && ! empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Search result
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'All courses', 'eduma' ) . '">' . esc_html__( 'All courses', 'eduma' ) . '</span></li>';
		}

		echo '</ul>';
	}
}

//learn_press_is_courses() || learn_press_is_course_taxonomy()

/**
 * Display co instructors
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_co_instructors' ) ) {
    function thim_co_instructors( $course_id, $author_id ) {
        if ( ! $course_id ) {
            return;
        }

        if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {
            $instructors = get_post_meta( $course_id, '_lp_co_teacher' );
            $instructors = array_diff( $instructors, array( $author_id ) );
            if ( $instructors ) {
                foreach ( $instructors as $instructor ) {
                    //Check if instructor not exist
                    $user = get_userdata( $instructor );
                    if ( $user === false ) {
                        break;
                    }
                    $lp_info = get_the_author_meta( 'lp_info', $instructor );
                    $link    = learn_press_user_profile_link( $instructor );
                    ?>
                    <div class="thim-about-author thim-co-instructor" itemprop="contributor" itemscope
                         itemtype="http://schema.org/Person">
                        <div class="author-wrapper">
                            <div class="author-avatar">
                                <?php echo get_avatar( $instructor, 110 ); ?>
                            </div>
                            <div class="author-bio">
                                <div class="author-top">
                                    <a itemprop="url" class="name" href="<?php echo esc_url( $link ); ?>">
                                        <span itemprop="name"><?php echo get_the_author_meta( 'display_name', $instructor ); ?></span>
                                    </a>
                                    <?php if ( isset( $lp_info['major'] ) && $lp_info['major'] ) : ?>
                                        <p class="job"
                                           itemprop="jobTitle"><?php echo esc_html( $lp_info['major'] ); ?></p>
                                    <?php endif; ?>
                                </div>
                                <ul class="thim-author-social">
                                    <?php if ( isset( $lp_info['facebook'] ) && $lp_info['facebook'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['facebook'] ); ?>" class="facebook"><i
                                                    class="fa fa-facebook"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( isset( $lp_info['twitter'] ) && $lp_info['twitter'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['twitter'] ); ?>" class="twitter"><i
                                                    class="fa fa-twitter"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( isset( $lp_info['google'] ) && $lp_info['google'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['google'] ); ?>"
                                               class="google-plus"><i class="fa fa-google-plus"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( isset( $lp_info['linkedin'] ) && $lp_info['linkedin'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['linkedin'] ); ?>" class="linkedin"><i
                                                    class="fa fa-linkedin"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( isset( $lp_info['youtube'] ) && $lp_info['youtube'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['youtube'] ); ?>" class="youtube"><i
                                                    class="fa fa-youtube"></i></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>

                            </div>
                            <div class="author-description" itemprop="description">
                                <?php echo get_the_author_meta( 'description', $instructor ); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
}


if ( ! function_exists( 'thim_course_wishlist_button' ) ) {
    function thim_course_wishlist_button( $course_id = null ) {
        if ( ! thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' ) ) {
            return;
        }
        LP_Addon_Wishlist::instance()->wishlist_button( $course_id );

    }
}

/**
 * Display ratings count
 */

if ( ! function_exists( 'thim_course_ratings_count' ) ) {
    function thim_course_ratings_count( $course_id = null ) {
        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }
        if ( ! $course_id ) {
            $course_id = get_the_ID();
        }
        $ratings = learn_press_get_course_rate_total( $course_id ) ? learn_press_get_course_rate_total( $course_id ) : 0;
        echo '<div class="course-comments-count">';
        echo '<div class="value"><i class="fa fa-comment"></i>';
        echo esc_html( $ratings );
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings' ) ) {
    function thim_course_ratings() {

        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        $course_id   = get_the_ID();
        $course_rate = learn_press_get_course_rate( $course_id );
        $ratings     = learn_press_get_course_rate_total( $course_id );
        ?>
        <div class="course-review">
            <label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

            <div class="value">
                <?php thim_print_rating( $course_rate ); ?>
                <span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : esc_html_e( '(0 review)', 'eduma' ); ?></span>
            </div>
        </div>
        <?php
    }
}

if ( ! function_exists( 'thim_print_rating' ) ) {
    function thim_print_rating( $rate ) {
        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        ?>
        <div class="review-stars-rated">
            <ul class="review-stars">
                <li><span class="fa fa-star-o"></span></li>
                <li><span class="fa fa-star-o"></span></li>
                <li><span class="fa fa-star-o"></span></li>
                <li><span class="fa fa-star-o"></span></li>
                <li><span class="fa fa-star-o"></span></li>
            </ul>
            <ul class="review-stars filled" style="<?php echo esc_attr( 'width: calc(' . ( $rate * 20 ) . '% - 2px)' ) ?>">
                <li><span class="fa fa-star"></span></li>
                <li><span class="fa fa-star"></span></li>
                <li><span class="fa fa-star"></span></li>
                <li><span class="fa fa-star"></span></li>
                <li><span class="fa fa-star"></span></li>
            </ul>
        </div>
        <?php

    }
}

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings_meta' ) ) {
    function thim_course_ratings_meta() {

        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        $course_id   = get_the_ID();
        $course_rate = learn_press_get_course_rate( $course_id );
        $ratings     = learn_press_get_course_rate_total( $course_id );
        ?>
        <div class="course-review">
            <label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

            <div class="value">
                <?php echo $course_rate; ?> <?php esc_html_e( 'Stars', 'eduma' ); ?>
                <span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : esc_html_e( '(0 review)', 'eduma' ); ?></span>
            </div>
        </div>
        <?php
    }
}

/**
 * Display price html
 */

if ( ! function_exists( 'thim_course_loop_price_html' ) ) {
    function thim_course_loop_price_html( $course ) {
        $class = ( $course->has_sale_price() ) ? ' has-origin' : '';
        if ( $course->is_free() ) {
            $class .= ' free-course';
        }
        ?>
        <div class="course-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
            <?php if ( $price_html = $course->get_price_html() ) { ?>
                <div class="value <?php echo $class; ?>" itemprop="price">
                    <?php if ( $course->get_origin_price() != $course->get_price() ) { ?>
                        <?php $origin_price_html = $course->get_origin_price_html(); ?>
                        <span class="course-origin-price"><?php echo $origin_price_html; ?></span>
                    <?php } ?>
                    <?php echo $price_html; ?>
                </div>
                <meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency_symbol(); ?>" />
            <?php } ?>
        </div>
        <?php
    }
}

/**
 * Display thumbnail course
 */

if( !function_exists( 'thim_courses_loop_item_thumbnail' ) ) {
    function thim_courses_loop_item_thumbnail( $course = null ) {
        $course = LP_Global::course();
        echo '<div class="course-thumbnail">';
        echo '<a class="thumb" href="' . esc_url(get_the_permalink($course->get_id())) . '" >';
        echo thim_get_feature_image(get_post_thumbnail_id($course->get_id()), 'full', apply_filters('thim_course_thumbnail_width', 450), apply_filters('thim_course_thumbnail_height', 450), $course->get_title());
        echo '</a>';
        thim_course_wishlist_button($course->get_id());
        echo '<a class="course-readmore" href="' . esc_url(get_the_permalink($course->get_id())) . '">' . esc_html__('Read More', 'eduma') . '</a>';
        echo '</div>';
    }
}
add_action( 'thim_courses_loop_item_thumb', 'thim_courses_loop_item_thumbnail');

/**
 * Show thumbnail single course
 */
if( !function_exists( 'thim_course_thumbnail_item' ) ) {
    function thim_course_thumbnail_item() {
        learn_press_get_template( 'single-course/thumbnail.php' );
    }
}
add_action( 'learn-press/single-course-summary', 'thim_course_thumbnail_item', 2);

/**
 * Display the link to course forum
 */
if ( ! function_exists( 'thim_course_forum_link' ) ) {
    function thim_course_forum_link() {

        if ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) {
            LP_Addon_BBPress_Course_Forum::instance()->forum_link();
        }
    }
}

/**
 * Add some meta data for a course
 *
 * @param $meta_box
 */
if ( ! function_exists( 'thim_add_course_meta' ) ) {
    function thim_add_course_meta( $meta_box ) {
        $fields             = $meta_box['fields'];
        $fields[]           = array(
            'name' => esc_html__( 'Duration', 'eduma' ),
            'id'   => 'thim_course_duration',
            'type' => 'text',
            'desc' => esc_html__( 'Course duration', 'eduma' ),
            'std'  => esc_html__( '50 hours', 'eduma' )
        );
        $fields[]           = array(
            'name' => esc_html__( 'Skill Levels', 'eduma' ),
            'id'   => 'thim_course_skill_level',
            'type' => 'text',
            'desc' => esc_html__( 'A possible level with this course', 'eduma' ),
            'std'  => esc_html__( 'All levels', 'eduma' )
        );
        $fields[]           = array(
            'name' => esc_html__( 'Languages', 'eduma' ),
            'id'   => 'thim_course_language',
            'type' => 'text',
            'desc' => esc_html__( 'Language\'s used for studying', 'eduma' ),
            'std'  => esc_html__( 'English', 'eduma' )
        );
        $fields[]           = array(
            'name' => esc_html__( 'Media Intro', 'eduma' ),
            'id'   => 'thim_course_media_intro',
            'type' => 'textarea',
            'desc' => esc_html__( 'Enter media intro', 'eduma' ),
        );
        $meta_box['fields'] = $fields;

        return $meta_box;
    }

}

add_filter( 'learn_press_course_settings_meta_box_args', 'thim_add_course_meta' );


if ( ! function_exists( 'thim_add_lesson_meta' ) ) {
    function thim_add_lesson_meta( $meta_box ) {
        $fields             = $meta_box['fields'];
        $fields[]           = array(
            'name' => esc_html__( 'Media', 'eduma' ),
            'id'   => '_lp_lesson_video_intro',
            'type' => 'textarea',
            'desc' => esc_html__( 'Add an embed link like video, PDF, slider...', 'eduma' ),
        );
        $meta_box['fields'] = $fields;

        return $meta_box;
    }
}
add_filter( 'learn_press_lesson_meta_box_args', 'thim_add_lesson_meta' );


/**
 * Display course info
 */
if ( ! function_exists( 'thim_course_info' ) ) {
    function thim_course_info() {
        $course    = LP()->global['course'];
        $course_id = get_the_ID();

        $course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
        $course_language    = get_post_meta( $course_id, 'thim_course_language', true );

        ?>
        <div class="thim-course-info">
            <h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
            <ul>
                <li class="lectures-feature">
                    <i class="fa fa-files-o"></i>
                    <span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
                    <span class="value"><?php echo $course->get_curriculum_items('lp_lesson') ? count( $course->get_curriculum_items('lp_lesson') ) : 0; ?></span>
                </li>
                <li class="quizzes-feature">
                    <i class="fa fa-puzzle-piece"></i>
                    <span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
                    <span class="value"><?php echo $course->get_curriculum_items('lp_quiz') ? count( $course->get_curriculum_items('lp_quiz') ) : 0; ?></span>
                </li>
                <?php if ( ! empty( $course_duration ) ): ?>
                    <li class="duration-feature">
                        <i class="fa fa-clock-o"></i>
                        <span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
                        <span class="value"><?php echo $course->get_duration(); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $course_skill_level ) ): ?>
                    <li class="skill-feature">
                        <i class="fa fa-level-up"></i>
                        <span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
                        <span class="value"><?php echo esc_html( $course_skill_level ); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $course_language ) ): ?>
                    <li class="language-feature">
                        <i class="fa fa-language"></i>
                        <span class="label"><?php esc_html_e( 'Language', 'eduma' ); ?></span>
                        <span class="value"><?php echo esc_html( $course_language ); ?></span>
                    </li>
                <?php endif; ?>
                <li class="students-feature">
                    <i class="fa fa-users"></i>
                    <span class="label"><?php esc_html_e( 'Students', 'eduma' ); ?></span>
                    <?php $user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0; ?>
                    <span class="value"><?php echo esc_html( $user_count ); ?></span>
                </li>
                <?php thim_course_certificate( $course_id ); ?>
                <li class="assessments-feature">
                    <i class="fa fa-check-square-o"></i>
                    <span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
                    <span class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
                </li>
            </ul>
            <?php thim_course_wishlist_button(); ?>
        </div>
        <?php
    }
}

/**
 * Display feature certificate
 *
 * @param $course_id
 */
function thim_course_certificate( $course_id ) {

    if ( thim_plugin_active( 'learnpress-certificates/learnpress-certificates.php' ) ) {
        ?>
        <li class="cert-feature">
            <i class="fa fa-rebel"></i>
            <span class="label"><?php esc_html_e( 'Certificate', 'eduma' ); ?></span>
            <span class="value"><?php echo ( get_post_meta( $course_id, '_lp_cert', true ) ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'No', 'eduma' ); ?></span>
        </li>
        <?php
    }
}

/**
 * Display course review
 */
if ( ! function_exists( 'thim_course_review' ) ) {
    function thim_course_review() {
        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        $course_id     = get_the_ID();
        $course_review = learn_press_get_course_review( $course_id, isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1, 5, true );
        $course_rate   = learn_press_get_course_rate( $course_id );
        $total         = learn_press_get_course_rate_total( $course_id );
        $reviews       = $course_review['reviews'];

        ?>
        <div class="course-rating">
            <h3><?php esc_html_e( 'Reviews', 'eduma' ); ?></h3>

            <div class="average-rating" itemprop="aggregateRating" itemscope=""
                 itemtype="http://schema.org/AggregateRating">
                <p class="rating-title"><?php esc_html_e( 'Average Rating', 'eduma' ); ?></p>

                <div class="rating-box">
                    <div class="average-value"
                         itemprop="ratingValue"><?php echo ( $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></div>
                    <div class="review-star">
                        <?php thim_print_rating( $course_rate ); ?>
                    </div>
                    <div class="review-amount" itemprop="ratingCount">
                        <?php $total ? printf( _n( '%1$s rating', '%1$s ratings', $total, 'eduma' ), number_format_i18n( $total ) ) : esc_html_e( '0 rating', 'eduma' ); ?>
                    </div>
                </div>
            </div>
            <div class="detailed-rating">
                <p class="rating-title"><?php esc_html_e( 'Detailed Rating', 'eduma' ); ?></p>

                <div class="rating-box">
                    <?php thim_detailed_rating( $course_id, $total ); ?>
                </div>
            </div>
        </div>

        <div class="course-review">
            <div id="course-reviews" class="content-review">
                <ul class="course-reviews-list">
                    <?php foreach ( $reviews as $review ) : ?>
                        <li>
                            <div class="review-container" itemprop="review" itemscope
                                 itemtype="http://schema.org/Review">
                                <div class="review-author">
                                    <?php echo get_avatar( $review->ID, 70 ); ?>
                                </div>
                                <div class="review-text">
                                    <h4 class="author-name"
                                        itemprop="author"><?php echo esc_html( $review->display_name ); ?></h4>

                                    <div class="review-star">
                                        <?php thim_print_rating( $review->rate ); ?>
                                    </div>
                                    <p class="review-title"><?php echo esc_html( $review->title ); ?></p>

                                    <div class="description" itemprop="reviewBody">
                                        <p><?php echo esc_html( $review->content ); ?></p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php if ( empty( $course_review['finish'] ) && $total ) : ?>
            <div class="review-load-more">
                <span id="course-review-load-more" data-paged="<?php echo esc_attr( $course_review['paged'] ); ?>"><i
                            class="fa fa-angle-double-down"></i></span>
            </div>
        <?php endif; ?>
        <?php thim_review_button( $course_id ); ?>
        <?php
    }
}

/**
 * Display review button
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_review_button' ) ) {
    function thim_review_button( $course_id ) {
        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        if ( ! get_current_user_id() ) {
            return;
        }
        if ( LP()->user->has( 'enrolled-course', $course_id ) || get_post_meta( $course_id, '_lp_required_enroll', true ) == 'no' ) {
            if ( ! learn_press_get_user_rate( $course_id ) ) {
                ?>
                <div class="add-review">
                    <h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

                    <p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
                    <?php do_action( 'learn_press_before_review_fields' ); ?>
                    <form method="post">
                        <div>
                            <label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
                                <span class="required">*</span></label>
                            <input required type="text" id="review-title" name="review-course-title"/>
                        </div>
                        <div>

                            <label><?php esc_html_e( 'Rating', 'eduma' ); ?>
                                <span class="required">*</span></label>

                            <div class="review-stars-rated">
                                <ul class="review-stars">
                                    <li><span class="fa fa-star-o"></span></li>
                                    <li><span class="fa fa-star-o"></span></li>
                                    <li><span class="fa fa-star-o"></span></li>
                                    <li><span class="fa fa-star-o"></span></li>
                                    <li><span class="fa fa-star-o"></span></li>
                                </ul>
                                <ul class="review-stars filled" style="width: 100%">
                                    <li><span class="fa fa-star"></span></li>
                                    <li><span class="fa fa-star"></span></li>
                                    <li><span class="fa fa-star"></span></li>
                                    <li><span class="fa fa-star"></span></li>
                                    <li><span class="fa fa-star"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <label for="review-content"><?php esc_html_e( 'Comment', 'eduma' ); ?>
                                <span class="required">*</span></label>
                            <textarea required id="review-content" name="review-course-content"></textarea>
                        </div>
                        <input type="hidden" id="review-course-value" name="review-course-value" value="5"/>
                        <input type="hidden" id="comment_post_ID" name="comment_post_ID"
                               value="<?php echo get_the_ID(); ?>"/>
                        <button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
                    </form>
                    <?php do_action( 'learn_press_after_review_fields' ); ?>
                </div>
                <?php
            }
        }
    }
}

/**
 * Process review
 */
if ( ! function_exists( 'thim_process_review' ) ) {
    function thim_process_review() {

        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        $user_id     = get_current_user_id();
        $course_id   = isset ( $_POST['comment_post_ID'] ) ? $_POST['comment_post_ID'] : 0;
        $user_review = learn_press_get_user_rate( $course_id, $user_id );
        if ( ! $user_review && $course_id ) {
            $review_title   = isset ( $_POST['review-course-title'] ) ? $_POST['review-course-title'] : 0;
            $review_content = isset ( $_POST['review-course-content'] ) ? $_POST['review-course-content'] : 0;
            $review_rate    = isset ( $_POST['review-course-value'] ) ? $_POST['review-course-value'] : 0;
            learn_press_add_course_review( array(
                'title'     => $review_title,
                'content'   => $review_content,
                'rate'      => $review_rate,
                'user_id'   => $user_id,
                'course_id' => $course_id
            ) );
        }
    }
}
add_action( 'learn_press_before_main_content', 'thim_process_review' );


/**
 * Display table detailed rating
 *
 * @param $course_id
 * @param $total
 */
if ( ! function_exists( 'thim_detailed_rating' ) ) {
    function thim_detailed_rating( $course_id, $total ) {
        global $wpdb;
        $query = $wpdb->get_results( $wpdb->prepare(
            "
		SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
		INNER JOIN $wpdb->comments AS c ON p.ID = c.comment_post_ID
		INNER JOIN $wpdb->users AS u ON u.ID = c.user_id
		INNER JOIN $wpdb->commentmeta AS cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key=%s
		INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
		WHERE p.ID=%d AND c.comment_type=%s AND c.comment_approved=%s
		GROUP BY cm2.meta_value",
            '_lpr_review_title',
            '_lpr_rating',
            $course_id,
            'review',
            '1'
        ), OBJECT_K
        );
        ?>
        <div class="detailed-rating">
            <?php for ( $i = 5; $i >= 1; $i -- ) : ?>
                <div class="stars">
                    <div class="key"><?php ( $i === 1 ) ? printf( esc_html__( '%s star', 'eduma' ), $i ) : printf( esc_html__( '%s stars', 'eduma' ), $i ); ?></div>
                    <div class="bar">
                        <div class="full_bar">
                            <div style="<?php echo ( $total && ! empty( $query[ $i ]->quantity ) ) ? esc_attr( 'width: ' . ( $query[ $i ]->quantity / $total * 100 ) . '%' ) : 'width: 0%'; ?>"></div>
                        </div>
                    </div>
                    <div class="value"><?php echo empty( $query[ $i ]->quantity ) ? '0' : esc_html( $query[ $i ]->quantity ); ?></div>
                </div>
            <?php endfor; ?>
        </div>
        <?php
    }
}

/**
 * Display related courses
 */
if ( ! function_exists( 'thim_related_courses' ) ) {
    function thim_related_courses() {
        $related_courses = thim_get_related_courses( 5 );
        $theme_options_data = get_theme_mods();
        $style_content = isset($theme_options_data['thim_layout_content_page']) ? $theme_options_data['thim_layout_content_page'] : 'normal';

        if ( $related_courses ) {
            $layout_grid = get_theme_mod('thim_learnpress_cate_layout_grid', '');
            $cls_layout = ($layout_grid!='' && $layout_grid!='layout_courses_1') ? ' cls_courses_2' : ' ';
            ?>
            <div class="thim-ralated-course <?php echo $cls_layout;?>">

                <?php if( $style_content == 'new-1' ) {?>
                    <div class="sc_heading clone_title  text-left">
                        <h2 class="title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h2>
                        <div class="clone"><?php esc_html_e( 'You May Like', 'eduma' ); ?></div>
                    </div>
                <?php } else {?>
                    <h3 class="related-title">
                        <?php esc_html_e( 'You May Like', 'eduma' ); ?>
                    </h3>
                <?php }?>

                <div class="thim-course-grid">
                    <div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1" data-pagination="1">
                        <?php foreach ( $related_courses as $course_item ) : ?>
                            <?php
                            $course = learn_press_get_course( $course_item->ID );
                            $is_required = $course->is_required_enroll();
                            ?>
                            <article class="lpr_course">
                                <div class="course-item">
                                    <div class="course-thumbnail">
                                        <a class="thumb" href="<?php echo get_the_permalink( $course_item->ID ); ?>">
                                            <?php
                                            if ( $layout_grid!='' && $layout_grid!='layout_courses_1' ) {
                                                echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 320, 220, get_the_title( $course_item->ID ) );
                                            } else {
                                                echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, get_the_title( $course_item->ID ) );
                                            }
                                            ?>
                                        </a>
                                        <?php thim_course_wishlist_button( $course_item->ID ); ?>
                                        <?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course_item->ID ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
                                    </div>
                                    <div class="thim-course-content">
                                        <div class="course-author">
                                            <?php echo get_avatar( $course_item->post_author, 40 ); ?>
                                            <div class="author-contain">
                                                <div class="value">
                                                    <a href="<?php echo esc_url( learn_press_user_profile_link( $course_item->post_author ) ); ?>">
                                                        <?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="course-title">
                                            <a rel="bookmark"
                                               href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
                                        </h2> <!-- .entry-header -->
                                        <div class="course-meta">
                                            <?php
                                            $count_student = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
                                            ?>
                                            <div class="course-students">
                                                <label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
                                                <?php do_action( 'learn_press_begin_course_students' ); ?>

                                                <div class="value"><i class="fa fa-group"></i>
                                                    <?php echo esc_html( $count_student ); ?>
                                                </div>
                                                <?php do_action( 'learn_press_end_course_students' ); ?>

                                            </div>
                                            <?php thim_course_ratings_count( $course_item->ID ); ?>
                                            <?php if ( $price = $course->get_price_html() ) {

                                                $origin_price = $course->get_origin_price_html();
                                                $sale_price   = $course->get_sale_price();
                                                $sale_price   = isset( $sale_price ) ? $sale_price : '';
                                                $class        = '';
                                                if ( $course->is_free() || ! $is_required ) {
                                                    $class .= ' free-course';
                                                    $price = esc_html__( 'Free', 'eduma' );
                                                }

                                                ?>

                                                <div class="course-price" itemprop="offers" itemscope
                                                     itemtype="http://schema.org/Offer">
                                                    <div class="value<?php echo $class; ?>" itemprop="price">
                                                        <?php
                                                        if ( $sale_price !== '' ) {
                                                            echo '<span class="course-origin-price">' . $origin_price . '</span>';
                                                        }
                                                        ?>
                                                        <?php echo $price; ?>
                                                    </div>
                                                    <meta itemprop="priceCurrency"
                                                          content="<?php echo learn_press_get_currency_symbol(); ?>"/>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'thim_get_related_courses' ) ) {
    function thim_get_related_courses( $limit ) {
        if ( ! $limit ) {
            $limit = 3;
        }
        $course_id = get_the_ID();

        $tag_ids = array();
        $tags    = get_the_terms( $course_id, 'course_tag' );

        if ( $tags ) {
            foreach ( $tags as $individual_tag ) {
                $tag_ids[] = $individual_tag->slug;
            }
        }

        $args = array(
            'posts_per_page'      => $limit,
            'paged'               => 1,
            'ignore_sticky_posts' => 1,
            'post__not_in'        => array( $course_id ),
            'post_type'           => 'lp_course'
        );

        if ( $tag_ids ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'course_tag',
                    'field'    => 'slug',
                    'terms'    => $tag_ids
                )
            );
        }
        $related = array();
        if ( $posts = new WP_Query( $args ) ) {
            global $post;
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $related[] = $post;
            }
        }
        wp_reset_query();

        return $related;
    }
}

/**
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( ! function_exists( 'thim_add_format_icon' ) ) {
    function thim_add_format_icon( $item ) {
        $format = get_post_format( $item->get_id() );
        if ( get_post_type( $item->get_id() ) == 'lp_quiz' ) {
            echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
        } elseif ( $format == 'video' ) {
            echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
        } else {
            echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
        }
    }
}

add_action( 'learn_press_before_section_item_title', 'thim_add_format_icon', 10, 1 );

/**
 * @param LP_Quiz|LP_Lesson $item
 */
if( !function_exists( 'thim_item_meta_duration' ) ) {
    function thim_item_meta_duration( $item ) {
        $duration = $item->get_duration();

        if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
            $format = array(
                'day'    => _x( '%s day', 'duration', 'learnpress' ),
                'hour'   => _x( '%s hour', 'duration', 'learnpress' ),
                'minute' => _x( '%s min', 'duration', 'learnpress' ),
                'second' => _x( '%s sec', 'duration', 'learnpress' ),
            );
            echo '<span class="meta duration">' . $duration->to_timer( $format, true ) . '</span>';
        } elseif ( is_string( $duration ) && strlen( $duration ) ) {
            echo '<span class="meta duration">' . $duration . '</span>';
        }
    }
}
add_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'thim_item_meta_duration', 5 );

/**
 * @param LP_Quiz|LP_Lesson $item
 */
function thim_item_quiz_meta_duration( $item ) {
    $duration = $item->get_duration();

    if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
        $format = array(
            'day'    => _x( '%s day', 'duration', 'learnpress' ),
            'hour'   => _x( '%s hour', 'duration', 'learnpress' ),
            'minute' => _x( '%s min', 'duration', 'learnpress' ),
            'second' => _x( '%s sec', 'duration', 'learnpress' ),
        );
        echo '<span class="meta duration">' . $duration->to_timer( $format, true ) . '</span>';
    } elseif ( is_string( $duration ) && strlen( $duration ) ) {
        echo '<span class="meta duration">' . $duration . '</span>';
    }
}
add_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'thim_item_quiz_meta_duration', 10 );

/**
 * @param LP_Quiz $item
 */
function thim_item_quiz_meta_questions( $item ) {
    $count = $item->count_questions();
    echo '<span class="meta count-questions">' . sprintf( $count ? _n( '%d question', '%d questions', 'learnpress' ) : __( '%d question', 'learnpress' ), $count ) . '</span>';
}
add_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'thim_item_quiz_meta_questions', 5 );

/**
 * Add class course item
 */
if(!function_exists('thim_add_class_course_item')) {
    function thim_add_class_course_item( $defaults, $item_type, $item_id, $course_id ) {
        $item_type = str_replace( 'lp_', '', $item_type );
        $defaults[] = 'course-' . $item_type;
        return $defaults;
    }
}
add_filter( 'learn-press/course-item-class', 'thim_add_class_course_item', 1000, 4 );

/**
 * Create ajax handle for courses searching
 */
if ( ! function_exists( 'thim_courses_searching_callback' ) ) {
    function thim_courses_searching_callback() {
        ob_start();
        $keyword = $_REQUEST['keyword'];
        if ( $keyword ) {
            $keyword   = strtoupper( $keyword );
            $arr_query = array(
                'post_type'           => 'lp_course',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                's'                   => $keyword,
                'posts_per_page'      => '-1'
            );

            $search = new WP_Query( $arr_query );

            $newdata = array();
            foreach ( $search->posts as $post ) {
                $newdata[] = array(
                    'id'    => $post->ID,
                    'title' => $post->post_title,
                    'guid'  => get_permalink( $post->ID ),
                );
            }

            ob_end_clean();
            if ( count( $search->posts ) ) {
                echo json_encode( $newdata );
            } else {
                $newdata[] = array(
                    'id'    => '',
                    'title' => '<i>' . esc_html__( 'No course found', 'eduma' ) . '</i>',
                    'guid'  => '#',
                );
                echo json_encode( $newdata );
            }
            wp_reset_postdata();
        }
        die();
    }
}

add_action( 'wp_ajax_nopriv_courses_searching', 'thim_courses_searching_callback' );
add_action( 'wp_ajax_courses_searching', 'thim_courses_searching_callback' );

/*
 * Before Curiculumn on item page
 */
if( !function_exists( 'thim_before_curiculumn_item_func' ) ) {
    function thim_before_curiculumn_item_func() {
        $args = array();
        $args = wp_parse_args( $args, apply_filters( 'learn_press_breadcrumb_defaults', array(
            'delimiter'   => '<i class="fa-angle-right fa"></i>',
            'wrap_before' => '<nav class="thim-font-heading learn-press-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
        ) ) );

        $breadcrumbs = new LP_Breadcrumb();


        $args['breadcrumb'] = $breadcrumbs->generate();

        learn_press_get_template( 'global/breadcrumb.php', $args );
    }
}
add_action( 'thim_before_curiculumn_item', 'thim_before_curiculumn_item_func' );

/*
 * Add media for lesson
 */
if( !function_exists( 'thim_content_item_lesson_media' ) ) {
    function thim_content_item_lesson_media() {
        $item = LP_Global::course_item();

        $media_intro = get_post_meta( $item->get_id(), '_lp_lesson_video_intro', true );
        if ( !empty( $media_intro ) ) {
            ?>
            <div class="learn-press-video-intro">
                <div class="video-content">
                    <?php echo $media_intro; ?>
                </div>
            </div>
            <?php
        }
    }
}
add_action( 'learn-press/before_course_item_content', 'thim_content_item_lesson_media', 5 );

/**
 * Filter profile title
 *
 * @param $tab_title
 * @param $key
 *
 * @return string
 */
function thim_tab_profile_filter_title( $tab_title, $key ) {
    switch ( $key ) {
        case 'courses':
            $tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Courses', 'eduma' ) . '</span>';
            break;
        case 'quizzes':
            $tab_title = '<i class="fa fa-check-square-o"></i><span class="text">' . esc_html__( 'Quiz Results', 'eduma' ) . '</span>';
            break;
        case 'orders':
            $tab_title = '<i class="fa fa-shopping-cart"></i><span class="text">' . esc_html__( 'Orders', 'eduma' ) . '</span>';
            break;
        case 'wishlist':
            $tab_title = '<i class="fa fa-heart-o"></i><span class="text">' . esc_html__( 'Wishlist', 'eduma' ) . '</span>';
            break;
        case 'gradebook':
            $tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Gradebook', 'eduma' ) . '</span>';
            break;
        case 'settings':
            $tab_title = '<i class="fa fa-cog"></i><span class="text">' . esc_html__( 'Settings', 'eduma' ) . '</span>';
            break;
        case 'certificates':
            $tab_title = '<i class="fa fa-bookmark-o"></i><span class="text">' . esc_html__( 'Certificates', 'eduma' ) . '</span>';
            break;
        case 'edit':
            $tab_title = '<i class="fa fa-user"></i><span class="text">' . esc_html__( 'Account', 'eduma' ) . '</span>';
            break;
    }

    return $tab_title;
}

add_filter( 'learn_press_profile_edit_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_courses_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_quizzes_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_orders_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_wishlist_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_gradebook_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_settings_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_certificates_tab_title', 'thim_tab_profile_filter_title', 100, 2 );

/**
 * Change tabs profile
 */
if(!function_exists('thim_change_tabs_course_profile')) {
    function thim_change_tabs_course_profile( $defaults ) {
        unset($defaults['dashboard']);
        $defaults['courses']['priority'] = 2;
        $defaults['orders']['priority'] = 3;
        $defaults['order-details']['priority'] = 4;
        $defaults['settings']['priority'] = 1;
        return $defaults;
    }
}
add_filter( 'learn-press/profile-tabs', 'thim_change_tabs_course_profile', 1000 );