<select name="<?php echo $field['name']; ?>">
	<?php if ( !empty( $field['options'] ) ) foreach ( $field['options'] as $name => $text ) { ?>
		<option value="<?php echo esc_attr( $name ); ?>" <?php selected( !empty( $field['std'] ) && $field['std'] == $name ? 1 : 0, 1 ); ?>><?php echo esc_html( $text ); ?></option>
	<?php } ?>
</select>