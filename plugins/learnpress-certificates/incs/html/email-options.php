<?php
defined( 'ABSPATH' ) || exit();
?>
<table class="form-table">
	<tbody>
	<?php do_action( 'learn_press_before_' . $settings_class->id . '_' . $settings_class->section['id'] . '_settings_fields', $settings ); ?>
	<tr>
		<th scope="row">
			<label for="learn-press-emails-certificate-user-enable"><?php _e( 'Enable', 'learnpress-certificates' ); ?></label>
		</th>
		<td>
			<input type="hidden" name="<?php echo $settings_class->get_field_name( 'emails_certificate_user[enable]' ); ?>" value="no" />
			<input id="learn-press-emails-certificate-user-enable" type="checkbox" name="<?php echo $settings_class->get_field_name( 'emails_certificate_user[enable]' ); ?>" value="yes" <?php checked( $settings->get( 'emails_certificate_user.enable' ) == 'yes' ); ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="learn-press-emails-certificate-user-subject"><?php _e( 'Subject', 'learnpress-certificates' ); ?></label>
		</th>
		<td>
			<input id="learn-press-emails-certificate-user-subject" class="regular-text" type="text" name="<?php echo $settings_class->get_field_name( 'emails_certificate_user[subject]' ); ?>" value="<?php echo $settings->get( 'emails_certificate_user.subject', $this->default_subject ); ?>" />

			<p class="description">
				<?php printf( __( 'Email subject, default: <code>%s</code>', 'learnpress-certificates' ), $this->default_subject ); ?>
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="learn-press-emails-certificate-user-heading"><?php _e( 'Heading', 'learnpress-certificates' ); ?></label>
		</th>
		<td>
			<input id="learn-press-emails-certificate-user-heading" class="regular-text" type="text" name="<?php echo $settings_class->get_field_name( 'emails_certificate_user[heading]' ); ?>" value="<?php echo $settings->get( 'emails_certificate_user.heading', $this->default_heading ); ?>" />

			<p class="description">
				<?php printf( __( 'Email heading, default: <code>%s</code>', 'learnpress-certificates' ), $this->default_heading ); ?>
			</p>
		</td>
	</tr>
	<?php
	$view = learn_press_get_admin_view( 'settings/emails/email-template.php' );
	include_once $view;
	?>
	<?php do_action( 'learn_press_after_' . $this->id . '_' . $this->section['id'] . '_settings_fields', $settings ); ?>
	</tbody>
</table>