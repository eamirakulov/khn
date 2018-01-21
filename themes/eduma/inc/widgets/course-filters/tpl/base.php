<?php

global $wpdb;
$show_price    = !empty( $instance['show_price'] ) ? $instance['show_price'] : false;
$show_featured = !empty( $instance['show_featured'] ) ? $instance['show_featured'] : false;
$show_price    = !empty( $instance['show_price'] ) ? $instance['show_price'] : false;

$is_course   = is_post_type_archive( 'lp_course' );
$is_category = is_tax( 'course_category' );

if ( !$is_course && !$is_category ) {
	return;
}

$checked_price    = !empty( $_REQUEST['lp_price'] ) ? $_REQUEST['lp_price'] : '';
$checked_featured = !empty( $_REQUEST['lp_featured'] ) ? $_REQUEST['lp_featured'] : '';
$checked_orderby  = !empty( $_REQUEST['lp_orderby'] ) ? $_REQUEST['lp_orderby'] : '';
?>

<?php if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
} ?>
<div class="widget-course-filters-contain">
	<?php if ( $show_price ) : ?>
		<h5><?php esc_html_e( 'Price', 'eduma' ); ?></h5>
		<ul class="course_filter_price">
			<li>
				<input type="radio" value="" name="lp_price" <?php echo $checked_price == '' ? 'checked="checked"' : ''; ?> /> <?php esc_html_e( 'All', 'eduma' ); ?>
			</li>
			<li>
				<input type="radio" value="free" name="lp_price" <?php echo $checked_price == 'free' ? 'checked="checked"' : ''; ?> /> <?php esc_html_e( 'Free', 'eduma' ); ?>
			</li>
			<li>
				<input type="radio" value="paid" name="lp_price" <?php echo $checked_price == 'paid' ? 'checked="checked"' : ''; ?> /> <?php esc_html_e( 'Paid', 'eduma' ); ?>
			</li>
		</ul>
	<?php endif; ?>

	<?php if ( $show_featured ) : ?>
		<h5><?php esc_html_e( 'Featured', 'eduma' ); ?></h5>
		<ul class="course_filter_featured">
			<li>
				<input type="checkbox" value="yes" name="lp_featured" <?php echo $checked_featured == 'yes' ? 'checked="checked"' : ''; ?> /> <?php esc_html_e( 'Featured', 'eduma' ); ?>
			</li>
		</ul>
	<?php endif; ?>

	<h5><?php esc_html_e( 'Sorting', 'eduma' ); ?></h5>
	<ul class="course_filter_orderby">
		<select name="lp_orderby" class="orderby">
			<option value=""><?php esc_html_e( 'Default Sorting', 'eduma' ); ?></option>
			<option value="title" <?php echo $checked_orderby == 'title' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by title', 'eduma' ); ?></option>
			<?php if ( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) : ?>
				<option value="rating" <?php echo $checked_orderby == 'rating' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by average rating', 'eduma' ); ?></option>
			<?php endif; ?>
			<option value="date" <?php echo $checked_orderby == 'date' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by newness', 'eduma' ); ?></option>
			<option value="price" <?php echo $checked_orderby == 'price' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by price: low to high', 'eduma' ); ?></option>
			<option value="price-desc" <?php echo $checked_orderby == 'price-desc' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by price: high to low', 'eduma' ); ?></option>
			<option value="students" <?php echo $checked_orderby == 'students' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by students: low to high', 'eduma' ); ?></option>
			<option value="students-desc" <?php echo $checked_orderby == 'students-desc' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sort by students: high to low', 'eduma' ); ?></option>
		</select>
	</ul>

</div>