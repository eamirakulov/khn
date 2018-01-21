<div class="thim-link-login">
	<?php if ( is_user_logged_in() ): ?>
		<?php if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) : ?>
			<?php if ( thim_is_new_learnpress( '1.0' ) ) : ?>
				<?php 
				$profile_endpoints = LP()->settings->get('profile_endpoints');
				$profile_endpoints_course = isset( $profile_endpoints['profile-courses'] ) ?$profile_endpoints['profile-courses'] : '';
				?>
				<a class="profile" href="<?php echo esc_url( learn_press_user_profile_link( 0, $profile_endpoints_course ) ); ?>"><?php esc_html_e( 'Profile', 'eduma' ); ?></a>
			<?php else: ?>
				<a class="profile" href="<?php echo esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', get_current_user_id(), '' ) ); ?>"><?php esc_html_e( 'Profile', 'eduma' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>

		<a class="logout" href="<?php echo esc_url( wp_logout_url( apply_filters( 'thim_default_logout_redirect', thim_get_login_page_url() ) ) ); ?>"><?php echo esc_html( $instance['text_logout'] ); ?></a>
	<?php else : ?>
		<?php
		$registration_enabled = get_option( 'users_can_register' );
		if ( $registration_enabled ) :
			?>
			<a class="register" href="<?php echo esc_url( thim_get_register_url() ); ?>"><?php echo esc_html( $instance['text_register'] ); ?></a>
		<?php endif; ?>
		<a class="login" href="<?php echo esc_url( thim_get_login_page_url() ); ?>"><?php echo esc_html( $instance['text_login'] ); ?></a>
	<?php endif; ?>
</div>