<?php
/**
 * Template for displaying Redo quiz button.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/buttons/redo.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course    = LP_Global::course();
$user      = LP_Global::user();
$quiz      = LP_Global::course_item_quiz();
$quiz_data = $user->get_item_data( $quiz->get_id(), $course->get_id() );
$remain = $user->can( 'retake-quiz', $quiz->get_id() );
?>

<?php do_action( 'learn-press/before-quiz-redo-button' ); ?>

<form name="redo-quiz" class="quiz-buttons" method="post" enctype="multipart/form-data">

	<?php do_action( 'learn-press/begin-quiz-redo-button' ); ?>

    <button type="submit" class="button-retake-quiz"
            data-counter="<?php echo $quiz_data->can_retake_quiz(); ?> ">
        <?php echo esc_html( sprintf( '%s (+%d)', __( 'Retake', 'eduma' ), $remain ) ); ?>
    </button>

	<?php do_action( 'learn-press/end-quiz-redo-button' ); ?>

	<?php LP_Nonce_Helper::quiz_action( 'redo', $quiz->get_id(), $course->get_id() ); ?>

</form>

<?php do_action( 'learn-press/after-quiz-redo-button' ); ?>
