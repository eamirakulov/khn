<?php
/**
 * Template for displaying tab nav of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/tabs.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$review_is_enable = thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' );
$student_list_enable = thim_plugin_active( 'learnpress-students-list/learnpress-students-list.php' );
$theme_options_data = get_theme_mods();
$group_tab = isset($theme_options_data['group_tabs_course']) ? $theme_options_data['group_tabs_course'] : array('description', 'curriculum', 'instructor', 'review');
$active_tab = isset($theme_options_data['default_tab_course']) ? $theme_options_data['default_tab_course'] : 'description';
if ( learn_press_is_learning_course() ) $active_tab = 'curriculum';
$arr_variable = array();
$arr_variable['description'] = array("title"=>esc_html__( 'Description', 'eduma' ), "icon"=>"fa-bookmark");
$arr_variable['curriculum'] = array("title"=>esc_html__( 'Curriculum', 'eduma' ), "icon"=>"fa-cube");
$arr_variable['instructor'] = array("title"=>esc_html__( 'Instructors', 'eduma' ), "icon"=>"fa-user");
$arr_variable['review'] = array("title"=>esc_html__( 'Review', 'eduma' ), "icon"=>"fa-comments");
?>

<?php $tabs = learn_press_get_course_tabs(); ?>

<?php if ( empty( $tabs ) ) {
	return;
} ?>

<div id="learn-press-course-tabs" class="course-tabs">

    <ul class="nav nav-tabs">
        <?php for( $i=0; $i<count($group_tab); $i++ ) {?>
            <?php
            switch ($group_tab[$i]) {
                case "description":
                    $tab_name = 'tab-overview';
                    break;
                case "curriculum":
                    $tab_name = 'tab-curriculum';
                    break;
                case "instructor":
                    $tab_name = 'tab-co-instructors';
                    break;
                case "review":
                    $tab_name = 'tab-reviews';
                    break;
            }
            ?>
            <?php if( $group_tab[$i]!='review' || ( $group_tab[$i]=='review' && $review_is_enable ) ) {?>
                <li role="presentation" <?php if($active_tab==$group_tab[$i]) echo 'class="active"';?>>
                    <?php
                    //var_dump($arr_variable[$group_tab[$i]]["title"]);
                    ?>
                    <a href="#<?php echo $tab_name;?>" data-toggle="tab">
                        <i class="fa <?php echo $arr_variable[$group_tab[$i]]["icon"];?>"></i>
                        <span><?php echo $arr_variable[$group_tab[$i]]["title"]; ?></span>
                    </a>
                </li>
            <?php }?>
        <?php }?>
        <?php if ( $student_list_enable ) : ?>
            <li role="presentation">
                <a href="#tab-course-student-list" data-toggle="tab">
                    <i class="fa fa-list"></i>
                    <span><?php esc_html_e( 'Student List', 'eduma' ); ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <?php foreach ( $tabs as $key => $tab ) { ?>
            <div class="tab-pane course-tab-panel-<?php echo esc_attr( $key ); ?> course-tab-panel<?php echo ! empty( $tab['active'] ) && $tab['active'] ? ' active' : ''; ?>"
                 id="<?php echo esc_attr( $tab['id'] ); ?>">
                <?php
                if ( apply_filters( 'learn_press_allow_display_tab_section', true, $key, $tab ) ) {
                    if ( is_callable( $tab['callback'] ) ) {
                        call_user_func( $tab['callback'], $key, $tab );
                    } else {
                        /**
                         * @since 3.0.0
                         */
                        do_action( 'learn-press/course-tab-content', $key, $tab );
                    }
                }
                ?>
            </div>
        <?php } ?>

    </div>

</div>