<?php
$fonts = array(
	'Arial'     => 'Arial',
	'Georgia'   => 'Georgia',
	'Helvetica' => 'Helvetica',
	'Verdana'   => 'Verdana'
);

if ( !empty( $field['google_font'] ) ) {
	$google_fonts = LP_Certificate_Field::google_fonts();
} else {
	$google_fonts = null;
}
?>
<select name="<?php echo $field['name']; ?>">
	<?php if ( $google_fonts ){ ?>
	<optgroup label="<?php _e( 'System fonts', 'learnpress-certificates' ); ?>">
		<?php } ?>
		<?php foreach ( $fonts as $name => $text ) { ?>
			<option value="<?php echo esc_attr( $name ); ?>" <?php selected( !empty( $field['std'] ) && $field['std'] == $name ? 1 : 0, 1 ); ?>><?php echo esc_html( $text ); ?></option>
		<?php } ?>
		<?php if ( $google_fonts ){ ?>
	</optgroup>
<?php } ?>

	<?php if ( $google_fonts ) { ?>
		<optgroup label="<?php _e( 'Google fonts', 'learnpress-certificates' ); ?>">
			<?php foreach ( $google_fonts as $name => $text ) { ?>
				<option value="::<?php echo esc_attr( $text ); ?>" <?php selected( !empty( $field['std'] ) && $field['std'] == $text ? 1 : 0, 1 ); ?>><?php echo esc_html( $text ); ?></option>
			<?php } ?>

		</optgroup>
	<?php } ?>
</select>