<?php
$options = LP_Certificate_Field::get_options( $field_options['fieldType'] );
?>

<?php foreach ( $options as $field ): ?>
	<li>
		<label>
			<?php echo $field['title']; ?>
		</label>
		<?php
		$field['std'] = !empty( $field_options[$field['name']] ) ? $field_options[$field['name']] : $field['std'];
		$option_name  = $field['type'];
		$option_slug  = LP_Certificate_Field::name_to_slug( $option_name );
		require LP_ADDON_CERTIFICATES_PATH . "/incs/html/fields/{$option_slug}.php";
		?>
	</li>
<?php endforeach; ?>
