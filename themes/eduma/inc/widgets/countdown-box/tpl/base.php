<?php

$text_days    = ( isset( $instance['text_days'] ) && '' != $instance['text_days'] ) ? $instance['text_days'] : 'days';
$text_hours   = ( isset( $instance['text_hours'] ) && '' != $instance['text_hours'] ) ? $instance['text_hours'] : 'hours';
$text_minutes = ( isset( $instance['text_minutes'] ) && '' != $instance['text_minutes'] ) ? $instance['text_minutes'] : 'minutes';
$text_seconds = ( isset( $instance['text_seconds'] ) && '' != $instance['text_seconds'] ) ? $instance['text_seconds'] : 'seconds';

if ( $instance['time_year'] != '' ) {
	$year = ( (int) ( $instance['time_year'] ) != '' ) ? (int) ( $instance['time_year'] ) : date( "Y", time() );
}
if ( $instance['time_month'] != '' ) {
	$month = ( (int) ( $instance['time_month'] ) != '' ) ? (int) ( $instance['time_month'] ) : date( "m", time() );
}
if ( $instance['time_day'] != '' ) {
	$day = ( (int) ( $instance['time_day'] ) != '' ) ? (int) ( $instance['time_day'] ) : date( "d", time() );
}
if ( $instance['time_hour'] != '' ) {
	$hour = ( (int) ( $instance['time_hour'] ) != '' ) ? (int) ( $instance['time_hour'] ) : date( "G", time() );
}
$style_color = 'color-white';
if ( $instance['style_color'] != '' ) {
	$style_color = 'color-' . $instance['style_color'];
}
$text_align = '';
if ( $instance['text_align'] != '' ) {
	$text_align = $instance['text_align'];
}
$id = uniqid();
echo '<div class="' . $text_align . ' ' . $style_color . '" id="coming-soon-counter' . $id . '"></div>';

?>
<script type="text/javascript">
jQuery(function () {
	jQuery(document).ready(function () {
		jQuery("#coming-soon-counter<?php echo esc_js($id); ?>").mbComingsoon({
			expiryDate  : new Date(<?php echo ent2ncr($year. ','. ( $month - 1 ) .',' . $day . ',' . $hour ); ?>),
			localization: {
				days   : "<?php echo esc_js($text_days); ?>",
				hours  : "<?php echo esc_js($text_hours); ?>",
				minutes: "<?php echo esc_js($text_minutes); ?>",
				seconds: "<?php echo esc_js($text_seconds); ?>"
			},
			speed       : 100
		});
		setTimeout(function () {
			jQuery(window).resize();
		}, 200);
	});
});
</script>