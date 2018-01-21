<?php
/**
 * Template for displaying content and items of section in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/content.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $section ) ) {
	return;
}

$user = LP_Global::user();
$course = LP()->global['course'];
$index = 0;
?>

<?php if ( $items = $section->get_items() ) { ?>

    <ul class="section-content">

		<?php foreach ( $items as $item ) { ?>

            <li class="<?php echo join( ' ', $item->get_class() ); ?>" data-type="<?php echo $item->get_item_type(); ?>">

				<?php if ( ! $item->is_visible() ) {
					continue;
				}
				//var_dump($item);

                $index++;

				/**
				 * @since 3.0.0
				 */
				do_action( 'learn-press/begin-section-loop-item', $item );

				?>

                <div class="meta-left">
                    <?php do_action( 'learn_press_before_section_item_title', $item, $section, $course ); ?>
                    <div class="index"><?php echo '<span class="label">' . esc_html__( 'Lecture', 'eduma' ) . '</span>' . $index; ?></div>
                </div>

                <?php learn_press_get_template( 'single-course/section/content-item.php', array( 'item'    => $item,
                    'section' => $section
                ) ); ?>

                <?php do_action( 'learn_press_after_section_item_title', $item, $section, $course ); ?>

				<?php
				/**
				 * @since 3.0.0
				 */
				do_action( 'learn-press/end-section-loop-item', $item );
				?>

            </li>

		<?php } ?>

    </ul>

<?php } else { ?>

	<?php learn_press_display_message( __( 'No items in this section', 'learnpress' ) ); ?>

<?php } ?>