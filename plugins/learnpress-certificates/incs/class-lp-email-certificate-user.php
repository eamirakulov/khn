<?php

/**
 * Class LP_Email_Certificate_User
 *
 * @author  ThimPress
 * @package LearnPress/Classes
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit();

class LP_Email_Certificate_User extends LP_Email {
	/**
	 * LP_Email_Drip_Content constructor.
	 */
	public function __construct() {
		$this->id    = 'certificate_user';
		$this->title = __( 'Certificate', 'learnpress-certificates' );

		$this->template_base = LP_ADDON_CERTIFICATES_PATH . '/templates/';

		$this->template_html  = 'emails/certificate-user.php';
		$this->template_plain = 'emails/plain/certificate-user.php';

		$this->default_subject = __( '[{{site_title}}] You have received a certificate', 'learnpress-certificates' );
		$this->default_heading = __( 'Certificate', 'learnpress-certificates' );

		$this->template_path = 'learnpress-certificates';
		$this->template_path = learn_press_template_path() . '/addons/certificates';

		$this->support_variables = array(
			'{{site_url}}',
			'{{site_title}}',
			'{{admin_email}}',
			'{{lesson_id}}',
			'{{lesson_name}}',
			'{{lesson_url}}',
			'{{available_date}}',
			'{{user_id}}',
			'{{username}}',
			'{{login_url}}',
			'{{course_id}}',
			'{{course_name}}',
			'{{course_url}}',
			'{{header}}',
			'{{footer}}',
			'{{email_heading}}',
			'{{footer_text}}',
			'{{user_certificate_link}}',
			'{{user_certificate_raw_link}}'
		);

		//add_action( 'learn_press_user_' . $this->id . '_notification', array( $this, 'trigger' ), 99, 3 );
		add_filter( 'learn_press_section_emails_' . $this->id, array( $this, 'admin_options' ) );

		parent::__construct();
	}

	public function send_email() {

	}

	public function admin_options( $obj ) {
		$settings_class = LP_Settings_Emails::instance();
		$settings       = LP()->settings;
		$view           = LP_ADDON_CERTIFICATES_PATH . '/incs/html/email-options.php';
		include_once $view;
	}

	public function trigger( $course_id, $user_id, $result ) {
		if ( !$this->enable ) {
			return;
		}
		$user = learn_press_get_user( $user_id );

		$this->recipient = $user->user_email;

		$this->object = $this->get_common_template_data(
			$this->email_format == 'plain_text' ? 'plain' : 'html',
			array(
				'user_id'     => $user->id,
				'user_name'   => learn_press_get_profile_display_name( $user ),
				'course_id'   => $course_id,
				'course_name' => get_the_title( $course_id ),
				'course_url'  => get_the_permalink( $course_id )
			)
		);

		$this->variables = $this->data_to_variables( $this->object );

		$return = $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

		return $return;
	}

	/*
	public function get_content_html() {
		echo parent::get_content_html();
		ob_start();
		learn_press_get_template( $this->template_html, $this->get_template_data( 'html' ), '', dirname( LP_ADDON_CONTENT_DRIP_FILE ) . '/templates' );
		return ob_get_clean();
	}

	public function get_content_plain() {
		echo parent::get_content_plain();
		ob_start();
		learn_press_get_template( $this->template_plain, $this->get_template_data( 'plain' ), '', dirname( LP_ADDON_CONTENT_DRIP_FILE ) . '/templates' );
		return ob_get_clean();
	}*/

	public function get_template_data( $format = 'plain' ) {
		return $this->object;
	}
}

return new LP_Email_Certificate_User();