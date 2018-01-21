<?php
/**
 * Created by PhpStorm.
 * User: tu
 * Date: 12/14/17
 * Time: 2:56 PM
 */

$args = array( 'item' => $item, 'section' => $section );
$user = LP_Global::user();

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-section-loop-item', $item );

if ( $user->can_view_item( $item->get_id() ) ) {
    ?>
    <a class="lesson-title course-item-title button-load-item" href="<?php echo $item->get_permalink(); ?>">
        <?php learn_press_get_template( "single-course/section/" . $item->get_template(), $args ); ?>
    </a>
<?php } else { ?>
    <div class="section-item-link">
        <?php learn_press_get_template( "single-course/section/" . $item->get_template(), $args ); ?>
    </div>
<?php }

/**
 * @since 3.0.0
 *
 * @see   learn_press_section_item_meta()
 */
do_action( 'learn-press/after-section-loop-item', $item, $section );
?>