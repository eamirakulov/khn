<div class="thim-link-login thim-login-popup">
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
		<?php if ( !empty( $instance['text_logout'] ) ): ?>
			<a class="logout" href="<?php echo esc_url( wp_logout_url( apply_filters( 'thim_default_logout_redirect', (!empty($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ); ?>"><?php echo esc_html( $instance['text_logout'] ); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<?php
		$registration_enabled = get_option( 'users_can_register' );
		if ( $registration_enabled && !empty( $instance['text_register'] ) ) :
			?>
			<a class="register" href="<?php echo esc_url( thim_get_register_url() ); ?>"><?php echo esc_html( $instance['text_register'] ); ?></a>
		<?php endif; ?>
		<?php if ( !empty( $instance['text_login'] ) ): ?>
			<a class="login" href="<?php echo esc_url( thim_get_login_page_url() ); ?>"><?php echo esc_html( $instance['text_login'] ); ?></a>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ( !is_user_logged_in() ): ?>
	<div id="thim-popup-login" class="<?php echo ( !empty( $instance['shortcode'] ) ) ? 'has-shortcode' : ''; ?>">
		<div class="thim-login-container">
			<?php
			if ( !empty( $instance['shortcode'] ) ) {
				echo do_shortcode( $instance['shortcode'] );
			}
			?>
			<div class="thim-login">
				<?php
				$login_redirect = get_theme_mod( 'thim_login_redirect', false );
				if ( empty( $login_redirect ) ) {
					$login_redirect = apply_filters( 'thim_default_login_redirect', (!empty($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
				}
				$redirect = !empty( $_REQUEST['redirect_to'] ) ? esc_url( $_REQUEST['redirect_to'] ) : $login_redirect ;
				?>
				<h2 class="title"><?php esc_html_e( 'Login with your site account', 'eduma' ); ?></h2>
				<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
					<p class="login-username">
						<input type="text" name="user_login" placeholder="<?php esc_html_e( 'Username or email', 'eduma' ); ?>" id="thim_login" class="input" value="" size="20" />
					</p>
					<p class="login-password">
						<input type="password" name="user_password" placeholder="<?php esc_html_e( 'Password', 'eduma' ); ?>" id="thim_pass" class="input" value="" size="20" />
					</p>
					<?php
					/**
					 * Fires following the 'Password' field in the login form.
					 *
					 * @since 2.1.0
					 */
					do_action( 'login_form' );
					?>
					<?php echo '<a class="lost-pass-link" href="' . thim_get_lost_password_url() . '" title="' . esc_attr__( 'Lost Password', 'eduma' ) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>'; ?>
					<p class="forgetmenot login-remember">
						<label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember Me', 'eduma' ); ?>
						</label></p>
					<p class="submit login-submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Login', 'eduma' ); ?>" />
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>" />
						<input type="hidden" name="testcookie" value="1" />
					</p>
				</form>
				<?php
				$registration_enabled = get_option( 'users_can_register' );
				if ( $registration_enabled ) {
					echo '<p class="link-bottom">' . esc_html__( 'Not a member yet? ', 'eduma' ) . '<a class="register" href="' . esc_url( thim_get_register_url() ) . '">' . esc_html__( 'Register now', 'eduma' ) . '</a></p>';
				}
				?>
			</div>
			<span class="close-popup"><i class="fa fa-times" aria-hidden="true"></i></span>
		</div>
	</div>
<?php endif;