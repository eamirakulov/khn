<?php
if(mo_openid_is_customer_registered()) {
/*
* Login Widget
*
*/
class mo_openid_login_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_login_wid',
			'miniOrange Social Login Widget',
			array( 
				'description' => __( 'Login using Social Apps like Google, Facebook, LinkedIn, Microsoft, Instagram.', 'flw' ), 
				'customize_selective_refresh' => true,
			)
		);
	 }


	public function widget( $args, $instance ) {
		extract( $args );

		echo $args['before_widget'];
			$this->openidloginForm();

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}

	
	public function openidloginForm(){
		global $post;
		//$this->error_message();
		$selected_theme = get_option('mo_openid_login_theme');
		$appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') | get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable');
		$spacebetweenicons = get_option('mo_login_icon_space');
		$customWidth = get_option('mo_login_icon_custom_width');
		$customHeight = get_option('mo_login_icon_custom_height');
		$customSize = get_option('mo_login_icon_custom_size');
		$customBackground = get_option('mo_login_icon_custom_color');
		$customTheme = get_option('mo_openid_login_custom_theme');
		$customTextofTitle = get_option('mo_openid_login_button_customize_text');
		$customBoundary = get_option('mo_login_icon_custom_boundary');
		$customLogoutName = get_option('mo_openid_login_widget_customize_logout_name_text');
		$customLogoutLink = get_option('mo_openid_login_widget_customize_logout_text');
		$customTextColor=get_option('mo_login_openid_login_widget_customize_textcolor');
		$customText=get_option('mo_openid_login_widget_customize_text');
		
		if( ! is_user_logged_in() ) {

			if( $appsConfigured ) {
				$this->mo_openid_load_login_script();
			?>			
				 <div class="mo-openid-app-icons">

				 <p style="color:#<?php echo $customTextColor ?>"><?php   echo $customText ?>
				</p>
				<?php
				if($customTheme == 'default'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button"  > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
				<?php }
				else{ ?>
					<a rel='nofollow' title="<?php echo $customTextofTitle ?> Facebook" onClick="moOpenIdLogin('facebook');"><img alt='Facebook' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>

				<?php }

				}
				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
				<a  rel='nofollow' onClick="moOpenIdLogin('google');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;' class='btn btn-block btn-social btn-google btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-google-plus'></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
				<?php }
				else{ ?>
				<a rel='nofollow' onClick="moOpenIdLogin('google');"  title="<?php echo $customTextofTitle ?> Google" ><img alt='Google' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
				<a  rel='nofollow' onClick="moOpenIdLogin('vkontakte');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;' class='btn btn-block btn-social btn-vk btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-vk'></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
				<?php }
				else{ ?>
				<a rel='nofollow' onClick="moOpenIdLogin('vkontakte');"  title="<?php echo $customTextofTitle ?> Vkontakte" ><img alt='Vkontakte' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php
				}
				}

				
				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow'  onClick="moOpenIdLogin('twitter');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-twitter btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-twitter"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
				<?php }
				else{ ?>


				<a rel='nofollow' title="<?php echo $customTextofTitle ?> Twitter" onClick="moOpenIdLogin('twitter');"><img alt='Twitter' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}
				
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ ?>
					<a rel='nofollow'  onClick="moOpenIdLogin('linkedin');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-linkedin"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
				<?php }
				else{ ?>
					<a rel='nofollow' title="<?php echo $customTextofTitle ?> LinkedIn" onClick="moOpenIdLogin('linkedin');"><img alt='LinkedIn' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
						<?php }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	?>
				 <a  rel='nofollow' onClick="moOpenIdLogin('instagram');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-instagram btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-instagram"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
				<?php }
				else{ ?>


				<a rel='nofollow' title="<?php echo $customTextofTitle ?> Instagram" onClick="moOpenIdLogin('instagram');"><img alt='Instagram' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow'  onClick="moOpenIdLogin('amazon');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-soundcloud btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-amazon"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
				<?php }
				else{ ?>

				<a rel='nofollow' title="<?php echo $customTextofTitle ?> Amazon" onClick="moOpenIdLogin('amazon');"><img alt='Amazon' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/amazon.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('salesforce');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-vimeo btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-cloud"></i> <?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
				<?php }
				else{ ?>


				<a rel='nofollow' title="<?php echo $customTextofTitle ?> Salesforce" onClick="moOpenIdLogin('salesforce');"><img alt='Salesforce' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/salesforce.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php }
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('windowslive');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-microsoft btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-windows"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Microsoft</a>
				<?php }
				else{ ?>


				<a rel='nofollow' title="<?php echo $customTextofTitle ?> Microsoft" onClick="moOpenIdLogin('windowslive');"><img alt='Windowslive' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/windowslive.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}
				
				}
				?>
				
				
				
				<?php
				if($customTheme == 'custom'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  rel='nofollow' onClick="moOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
				<?php }
				else{ ?>

					<a rel='nofollow' onClick="moOpenIdLogin('facebook');" title="<?php echo $customTextofTitle ?> Facebook"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;" class="fa fa-facebook custom-login-button <?php echo $selected_theme; ?>" ></i></a>

				<?php }

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a rel='nofollow'  onClick="moOpenIdLogin('google');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-google-plus"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
				<?php }
				else{ ?>
				<a rel='nofollow' onClick="moOpenIdLogin('google');" title="<?php echo $customTextofTitle ?> Google"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-google-plus custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a rel='nofollow'  onClick="moOpenIdLogin('vkontakte');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-vk"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
				<?php }
				else{ ?>
				<a rel='nofollow' onClick="moOpenIdLogin('vkontakte');" title="<?php echo $customTextofTitle ?> Vkontakte"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-vk custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a rel='nofollow'  onClick="moOpenIdLogin('twitter');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-twitter"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
				<?php }
				else{ ?>
				<a  rel='nofollow'onClick="moOpenIdLogin('twitter');" title="<?php echo $customTextofTitle ?> Twitter"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-twitter custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ ?>
					<a rel='nofollow' onClick="moOpenIdLogin('linkedin');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-linkedin"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
				<?php }
				else{ ?>
					<a rel='nofollow' onClick="moOpenIdLogin('linkedin');" title="<?php echo $customTextofTitle ?> LinkedIn"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-linkedin custom-login-button <?php echo $selected_theme; ?>" ></i></a>
						<?php }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	?>
				 <a rel='nofollow' onClick="moOpenIdLogin('instagram');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-instagram btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-instagram"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
				<?php }
				else{ ?>


				<a rel='nofollow' onClick="moOpenIdLogin('instagram');" title="<?php echo $customTextofTitle ?> Instagram"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class="fa fa-instagram custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('amazon');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" ><i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-amazon"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
				<?php }
				else{ ?>

				<a rel='nofollow' onClick="moOpenIdLogin('amazon');" title="<?php echo $customTextofTitle ?> Amazon"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class="fa fa-amazon custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('salesforce');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" ><i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-cloud"></i> <?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
				<?php }
				else{ ?>


				<a rel='nofollow' onClick="moOpenIdLogin('salesforce');" title="<?php echo $customTextofTitle ?> Salesforce"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px " class="fa fa-cloud custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php }
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a rel='nofollow' onClick="moOpenIdLogin('windowslive');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-microsoft btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-windows"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Microsoft</a>
				<?php }
				else{ ?>


				<a  rel='nofollow' onClick="moOpenIdLogin('windowslive');" title="<?php echo $customTextofTitle ?> Microsoft"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class=" fa fa-windows custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}
				
				
				}
				?>
				<br>
				</div> <br>
				<?php


			} else {
				?>
				<div>No apps configured. Please contact your administrator.</div>
			<?php
			}
		}else {
			global $current_user;
			$current_user = wp_get_current_user();
			$customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
			$link_with_username = $customLogoutName;
			if (empty($customLogoutName)  || empty($customLogoutLink)) {
			?>
			<div id="logged_in_user" class="mo_openid_login_wid">
				<li><?php echo $link_with_username;?> <a href="<?php echo wp_logout_url( site_url() ); ?>" title="<?php _e('Logout','flw');?>"><?php _e($customLogoutLink,'flw');?></a></li>
			</div>
			<?php
				
			}
			else {
			?>
			<div id="logged_in_user" class="mo_openid_login_wid">
				<li><?php echo $link_with_username;?> <a href="<?php echo wp_logout_url( site_url() ); ?>" title="<?php _e('Logout','flw');?>"><?php _e($customLogoutLink,'flw');?></a></li>
			</div>
			<?php
			}		
		}
	}

	
	public function openidloginFormShortCode( $atts ){
		global $post;
		$html = '';
		//$this->error_message();
		$selected_theme = isset( $atts['shape'] )? $atts['shape'] : get_option('mo_openid_login_theme');
		$appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') |get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable');
		$spacebetweenicons = isset( $atts['space'] )? $atts['space'] : get_option('mo_login_icon_space');
		$customWidth = isset( $atts['width'] )? $atts['width'] : get_option('mo_login_icon_custom_width');
		$customHeight = isset( $atts['height'] )? $atts['height'] : get_option('mo_login_icon_custom_height');
		$customSize = isset( $atts['size'] )? $atts['size'] : get_option('mo_login_icon_custom_size');
		$customBackground = isset( $atts['background'] )? $atts['background'] : get_option('mo_login_icon_custom_color');
		$customTheme = isset( $atts['theme'] )? $atts['theme'] : get_option('mo_openid_login_custom_theme');
		$customText = get_option('mo_openid_login_widget_customize_text');
		$buttonText = get_option('mo_openid_login_button_customize_text');
		$customTextofTitle = get_option('mo_openid_login_button_customize_text');
		$logoutUrl = wp_logout_url( site_url() );
		$customBoundary = isset( $atts['edge'] )? $atts['edge'] : get_option('mo_login_icon_custom_boundary');
		$customLogoutName = get_option('mo_openid_login_widget_customize_logout_name_text');
		$customLogoutLink = get_option('mo_openid_login_widget_customize_logout_text');
		$customTextColor= isset( $atts['color'] )? $atts['color'] : get_option('mo_login_openid_login_widget_customize_textcolor');
		$customText=isset( $atts['heading'] )? $atts['heading'] :get_option('mo_openid_login_widget_customize_text');
		
		if($selected_theme == 'longbuttonwithtext'){
			$selected_theme = 'longbutton';
		}
		if($customTheme == 'custombackground'){
			$customTheme = 'custom';
		}
		
		if( ! is_user_logged_in() ) {

			if( $appsConfigured ) {
				$this->mo_openid_load_login_script();
			$html .= "<div class='mo-openid-app-icons'>

				 
				 <p style='color:#".$customTextColor."'> $customText</p>";
				 
				 
				 
				
				if($customTheme == 'default'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-facebook btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"facebook"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-facebook'></i>" . $buttonText . " Facebook</a>"; }
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Facebook' onClick='moOpenIdLogin(" . '"facebook"' . ");' ><img alt='Facebook' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/facebook.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-google btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"google"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-google-plus'></i>" . $buttonText . " Google</a>"; 
				 }
				else{ 
				
				$html .= "<a rel='nofollow' onClick='moOpenIdLogin(" . '"google"' . ");' title= ' ".$customTextofTitle." Google'><img alt='Google' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/google.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-vk btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"vkontakte"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-vk'></i>" . $buttonText . " Vkontakte</a>"; 
				 }
				else{ 
				
				$html .= "<a rel='nofollow' onClick='moOpenIdLogin(" . '"vkontakte"' . ");' title= ' ".$customTextofTitle." Vkontakte'><img alt='Vkontakte' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/vk.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				
				}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-twitter btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"twitter"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-twitter'></i>" . $buttonText . " Twitter</a>"; }
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Twitter' onClick='moOpenIdLogin(" . '"twitter"' . ");' ><img alt='Twitter' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/twitter.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}

				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ 
					 $html .= "<a  rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-linkedin btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"linkedin"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-linkedin'></i>" . $buttonText . " LinkedIn</a>";
				 }
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." LinkedIn' onClick='moOpenIdLogin(" . '"linkedin"' . ");' ><img alt='LinkedIn' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/linkedin.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				 }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	
				 $html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-instagram btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"instagram"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-instagram'></i>" . $buttonText . " Instagram</a>";
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Instagram' onClick='moOpenIdLogin(" . '"instagram"' . ");' ><img alt='Instagram' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/instagram.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				      $html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-soundcloud btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"amazon"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-amazon'></i>" . $buttonText . " Amazon</a>"; 
				 }
				else{
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Amazon' onClick='moOpenIdLogin(" . '"amazon"' . ");' ><img alt='Amazon' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/amazon.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				 $html .= "<a  rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-vimeo btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"salesforce"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-cloud'></i>" . $buttonText . " Salesforce</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Salesforce' onClick='moOpenIdLogin(" . '"salesforce"' . ");' ><img alt='Salesforce' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/salesforce.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a rel='nofollow' style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-microsoft btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"windowslive"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-windows'></i>" . $buttonText . " Microsoft</a>";
					}
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Microsoft' onClick='moOpenIdLogin(" . '"windowslive"' . ");' ><img alt='Windowslive' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/windowslive.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}
				}
				
				if($customTheme == 'custom'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  rel='nofollow' onClick='moOpenIdLogin(" . '"facebook"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-facebook'></i> " . $buttonText . " Facebook</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Facebook' onClick='moOpenIdLogin(" . '"facebook"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-facebook custom-login-button  " . $selected_theme . "' ></i></a>";
				}

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a rel='nofollow'  onClick='moOpenIdLogin(" . '"google"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-google-plus'></i> " . $buttonText . " Google</a>"; 
					}
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Google' onClick='moOpenIdLogin(" . '"google"' . ");' title= ' ". $customTextofTitle."  Google'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-google-plus custom-login-button  " . $selected_theme . "' ></i></a>";
				
					}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a  rel='nofollow' onClick='moOpenIdLogin(" . '"vkontakte"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-vk'></i> " . $buttonText . " Vkontakte</a>"; 
					}
				else{ 
						$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Vkontakte' onClick='moOpenIdLogin(" . '"vkontakte"' . ");' title= ' ". $customTextofTitle."  Vkontakte'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-vk custom-login-button  " . $selected_theme . "' ></i></a>";
				
					}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  rel='nofollow' onClick='moOpenIdLogin(" . '"twitter"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-twitter'></i> " . $buttonText . " Twitter</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Twitter' onClick='moOpenIdLogin(" . '"twitter"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-twitter custom-login-button  " . $selected_theme . "' ></i></a>";
				}

				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ 
					 $html .= "<a rel='nofollow'  onClick='moOpenIdLogin(" . '"linkedin"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-linkedin'></i> " . $buttonText . " LinkedIn</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." LinkedIn' onClick='moOpenIdLogin(" . '"linkedin"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-linkedin custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){
						 $html .= "<a rel='nofollow'  onClick='moOpenIdLogin(" . '"instagram"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-instagram'></i> " . $buttonText . " Instagram</a>"; 
				 }
				else{
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Instagram' onClick='moOpenIdLogin(" . '"instagram"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-instagram custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  rel='nofollow' onClick='moOpenIdLogin(" . '"amazon"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-amazon'></i> " . $buttonText . " Amazon</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Amazon'  onClick='moOpenIdLogin(" . '"amazon"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-amazon custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				  $html .= "<a rel='nofollow'  onClick='moOpenIdLogin(" . '"salesforce"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-cloud'></i> " . $buttonText . " Salesforce</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Salesforce' onClick='moOpenIdLogin(" . '"salesforce"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-cloud custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				  $html .= "<a  rel='nofollow' onClick='moOpenIdLogin(" . '"windowslive"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-windows'></i> " . $buttonText . " Microsoft</a>"; 
				 }
				else{ 
					$html .= "<a rel='nofollow' title= ' ".$customTextofTitle." Microsoft' onClick='moOpenIdLogin(" . '"windowslive"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-windows custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}
				}
				 $html .= '</div> <br>';


			} else {
				
				$html .= '<div>No apps configured. Please contact your administrator.</div>';
			
			}
		}else {
			global $current_user;
	     	$current_user = wp_get_current_user();
			$customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
			$flw = __($customLogoutLink,"flw");
			if (empty($customLogoutName)  || empty($customLogoutLink)) {
				$html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl .' title=" ' . $flw . '"> ' . $flw . '</a></div>';
			}
			else {
				$html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl .' title=" ' . $flw . '"> ' . $flw . '</a></div>';
			}
		}
		
		return $html;
	}
	
	private function mo_openid_load_login_script() {
		?>
		<script type="text/javascript">
			function moOpenIdLogin(app_name) {
				<?php
					if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
						$http = "https://";
					} else {
						$http =  "http://";
					}
					if ( strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE){
						$redirect_url = site_url() . '/?option=getmosociallogin&app_name=';

					}else{
				    	$redirect_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
						if(strpos($redirect_url, '?') !== false) {
							$redirect_url .= '&option=getmosociallogin&app_name=';
						} else {
							$redirect_url .= '?option=getmosociallogin&app_name=';
						}
					}
				?>
				window.location.href = '<?php echo $redirect_url; ?>' + app_name;
			}
		</script>
		<?php
	}

	/*public function error_message(){
		if(isset($_SESSION['msg']) and $_SESSION['msg']){
			echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION['msg'].'</div>';
			unset($_SESSION['msg']);
			unset($_SESSION['msg_class']);
		}
	}*/

}


/**
 * Sharing Widget Horizontal
 *
 */
class mo_openid_sharing_hor_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_sharing_hor_wid',
			'miniOrange Sharing - Horizontal',
			array( 
				'description' => __( 'Share using horizontal widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ),
				'customize_selective_refresh' => true,
			)
		);
	}


	public function widget( $args, $instance ) {
		extract( $args );

		echo $args['before_widget'];
			$this->show_sharing_buttons_horizontal();

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


    public function show_sharing_buttons_horizontal(){
		global $post;
		$title = str_replace('+', '%20', urlencode($post->post_title));
		$content=strip_shortcodes( strip_tags( get_the_content() ) );
		$post_content=$content;
		$excerpt = '';
		$landscape = 'horizontal';
		include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
	}

}


/**
 * Sharing Vertical Widget
 *
 */
class mo_openid_sharing_ver_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_sharing_ver_wid',
			'miniOrange Sharing - Vertical',
			array( 
				'description' => __( 'Share using a vertical floating widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ), 
				'customize_selective_refresh' => true,
			)
		);
	 }


	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		$alignment = apply_filters( 'alignment', isset($instance['alignment'])? $instance['alignment'] : 'left');
		$left_offset = apply_filters( 'left_offset', isset($instance['left_offset'])? $instance['left_offset'] : '20');
		$right_offset = apply_filters( 'right_offset', isset($instance['right_offset'])? $instance['right_offset'] : '0');
		$top_offset = apply_filters( 'top_offset', isset($instance['top_offset'])? $instance['top_offset'] : '100');
		$space_icons = apply_filters( 'space_icons', isset($instance['space_icons'])? $instance['space_icons'] : '10');

		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
		
		echo "<div class='mo_openid_vertical' style='" .(isset($alignment) && $alignment != '' && isset($instance[$alignment.'_offset']) ? $alignment .': '. ( $instance[$alignment.'_offset'] == '' ? 0 : $instance[$alignment.'_offset'] ) .'px;' : '').(isset($top_offset) ? 'top: '. ( $top_offset == '' ? 0 : $top_offset ) .'px;' : '') ."'>";
		
		$this->show_sharing_buttons_vertical($space_icons);
		
		echo '</div>';

		echo $args['after_widget'];
	}

	/*Called when user changes configuration in Widget Admin Panel*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		$instance['alignment'] = $new_instance['alignment'];
		$instance['left_offset'] = $new_instance['left_offset'];
		$instance['right_offset'] = $new_instance['right_offset'];
		$instance['top_offset'] = $new_instance['top_offset'];
		$instance['space_icons'] = $new_instance['space_icons'];
		return $instance;
	}


    public function show_sharing_buttons_vertical($space_icons){
		global $post;
		if($post->post_title) {
			$title = str_replace('+', '%20', urlencode($post->post_title));
		} else {
			$title = get_bloginfo( 'name' );
		}
		$content=strip_shortcodes( strip_tags( get_the_content() ) );
		$post_content=$content;
		$excerpt = '';
		$landscape = 'vertical';
		
		include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
	}
	
	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array('alignment' => 'left', 'left_offset' => '20', 'right_offset' => '0', 'top_offset' => '100' , 'space_icons' => '10');
		
		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults );
		?> 
		<p> 
			<script>
			function moOpenIDVerticalSharingOffset(alignment){
				if(alignment == 'left'){
					jQuery('.moVerSharingLeftOffset').css('display', 'block');
					jQuery('.moVerSharingRightOffset').css('display', 'none');
				}else{
					jQuery('.moVerSharingLeftOffset').css('display', 'none');
					jQuery('.moVerSharingRightOffset').css('display', 'block');
				}
			}
			</script>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>">Alignment</label> 
			<select onchange="moOpenIDVerticalSharingOffset(this.value)" style="width: 95%" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>>Left</option>
				<option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>>Right</option>
			</select>
			<div class="moVerSharingLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'left_offset' ); ?>">Left Offset</label> 
				<input style="width: 95%" id="<?php echo $this->get_field_id( 'left_offset' ); ?>" name="<?php echo $this->get_field_name( 'left_offset' ); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
			</div>
			<div class="moVerSharingRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'right_offset' ); ?>">Right Offset</label> 
				<input style="width: 95%" id="<?php echo $this->get_field_id( 'right_offset' ); ?>" name="<?php echo $this->get_field_name( 'right_offset' ); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
			</div>
			<label for="<?php echo $this->get_field_id( 'top_offset' ); ?>">Top Offset</label> 
			<input style="width: 95%" id="<?php echo $this->get_field_id( 'top_offset' ); ?>" name="<?php echo $this->get_field_name( 'top_offset' ); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px<br/>
			<label for="<?php echo $this->get_field_id( 'space_icons' ); ?>">Space between icons</label> 
			<input style="width: 95%" id="<?php echo $this->get_field_id( 'space_icons' ); ?>" name="<?php echo $this->get_field_name( 'space_icons' ); ?>" type="text" value="<?php echo $instance['space_icons']; ?>" />px<br/>
		</p>
	<?php
	}
}
 
    function mo_openid_start_session() {
		if( !session_id() ) {
			session_start();
		}
	}

	function mo_openid_end_session() {
		if( session_id() ) {
			session_destroy();
		}
	}
	
	function encrypt_data($data, $key) {
		
		return base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key, OPENSSL_RAW_DATA));
	
	}	

	function decrypt_data($data, $key) {

		return openssl_decrypt( base64_decode($data), 'aes-128-ecb', $key, OPENSSL_RAW_DATA);

	}	

	function mo_openid_login_validate()
	{		
		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'getmosociallogin' ) !== false ){
			$client_name = "wordpress";
			$timestamp = round( microtime(true) * 1000 );
			$api_key = get_option('mo_openid_admin_api_key');
			$token = $client_name . ':' . number_format($timestamp, 0, '', ''). ':' . $api_key;

			$customer_token = get_option('mo_openid_customer_token');
			$encrypted_token = encrypt_data($token,$customer_token);
			$encoded_token = urlencode( $encrypted_token );
			$userdata = get_option('moopenid_user_attributes')?'true':'false';
			
			$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://";

			$parts = parse_url($http . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			parse_str($parts['query'], $query);
			$post = isset( $query['p'] ) ? '?p=' . $query['p'] : '';
			
			$base_return_url =  $http . $_SERVER["HTTP_HOST"] . strtok($_SERVER["REQUEST_URI"],'?') . $post;

			$return_url = strpos($base_return_url, '?') !== false ? urlencode( $base_return_url . '&option=moopenid' ): urlencode( $base_return_url . '?option=moopenid' );

			$url = get_option('mo_openid_host_name') . '/moas/openid-connect/client-app/authenticate?token=' . $encoded_token . '&userdata=' . $userdata. '&id=' . get_option('mo_openid_admin_customer_key') . '&encrypted=true&app=' . $_REQUEST['app_name'] . '_oauth&returnurl=' . $return_url . '&encrypt_response=true';
			wp_redirect( $url );
			exit;
		}
		
		if( isset( $_POST['username_field']) and isset($_POST['email_field']) and $_POST['option'] == 'mo_openid_profile_form_submitted' ){ 
	        
			$username = $_POST['username_field'];
			$user_email = $_POST['email_field'];
			$user_picture = $_POST["user_picture"]; 
			$user_url = $_POST["user_url"];
			$last_name = $_POST["last_name"]; 
			$user_full_name = $_POST["user_full_name"]; 
			$first_name = $_POST["first_name"];
			$decrypted_app_name = $_POST["decrypted_app_name"];
			$decrypted_user_id = $_POST["decrypted_user_id"];
			
			if(!isset($_POST['otp_field'])) {
				$user_email = sanitize_email($user_email);
				$username = preg_replace('/[\x00-\x1F\x7F\x81\x8D\x8F\x90\x9D\xA0\xAD]/', '', $username);
				$username = strtolower(str_replace(" ","",$username));	

				$path = site_url();
				$path .= '/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,buttons,forms,l10n,login&amp;ver=4.8.1';			
				
				global $wpdb;
				$email_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_email = %s", $user_email));
				$username_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_login = %s", $username));
				
				//if email exists, dont check if username is in db or not, send otp and get it over wordpress
				if( isset($email_user_id)){
					
					$send_content = send_otp_token($user_email);
					if($send_content['status']=='FAILURE'){
						$message = 'Either your SMTP is not configured or you have entered an unmailable email. Please go back and try again.'; 
						wp_die($message);						
					}
					
					$transaction_id = $send_content['tId'];
					echo mo_openid_validate_otp_form($path, $username, $user_email, $transaction_id, $user_picture, $user_url,	$last_name, $user_full_name,$first_name, $decrypted_app_name, $decrypted_user_id);
					exit;
				
				}
				//email doesnt exist, check if username is in db or not, acc show form and proceed further
				else {
					
					if( isset($username_user_id) ){	
						echo mo_openid_username_already_exists($path, $last_name, $first_name, $user_full_name, $user_url, $user_picture, $username, $user_email, $decrypted_app_name, $decrypted_user_id);
						exit;					
					}
					else {
						
						$send_content = send_otp_token($user_email);
						if($send_content['status']=='FAILURE'){
							$message = 'Either your SMTP is not configured or you have entered an unmailable email. Please go back and try again.'; 
							wp_die($message);						
						}
						
						$transaction_id = $send_content['tId'];
						echo mo_openid_validate_otp_form($path, $username, $user_email, $transaction_id, $user_picture, $user_url,	$last_name, $user_full_name,$first_name, $decrypted_app_name, $decrypted_user_id);
						exit;
					}				
					
				}
			}		
		}		
	
		if( isset( $_POST['otp_field']) and $_POST['option'] == 'mo_openid_otp_validation' ){       
				
			$username = $_POST["username_field"];
			$user_email = $_POST["email_field"];
			$transaction_id = $_POST["transaction_id"];
			$otp_token = $_POST['otp_field'];
			
			$user_picture = $_POST["user_picture"]; 
			$user_url = $_POST["user_url"];
			$last_name = $_POST["last_name"]; 
			$user_full_name = $_POST["user_full_name"]; 
			$first_name = $_POST["first_name"];
			$decrypted_app_name = $_POST["decrypted_app_name"];
			$decrypted_user_id = $_POST["decrypted_user_id"];			
					
			$validate_content = validate_otp_token($transaction_id, $otp_token);
			$status = $validate_content['status'];
			
			$path = site_url();
			$path .= '/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,buttons,forms,l10n,login&amp;ver=4.8.1';			
					
			if($status == 'FAILURE'){ 
				echo mo_openid_invalid_otp_form($path, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id, $username,$user_email, $transaction_id);exit;
			}
			else{
				
				global $wpdb;
				$email_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_email = %s", $user_email));
				
				// if user exists log him in
				if(isset($email_user_id))
				{
					$user 	= get_user_by('id', $email_user_id );
					if(get_option('moopenid_social_login_avatar') && isset($user_picture))
						update_user_meta($email_user_id, 'moopenid_user_avatar', $user_picture);
					$_SESSION['mo_login'] = true;
					do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
					do_action( 'wp_login', $user->user_login, $user );
					wp_set_auth_cookie( $email_user_id, true );
				}
				// else register him 
				else{
					
					//check if auto-registration is enabled
					if(get_option('mo_openid_auto_register_enable')) {
						
						$random_password 	= wp_generate_password( 10, false );
						$userdata = array(
											'user_login'  =>  $username,
											'user_email'    =>  $user_email,
											'user_pass'   =>  $random_password,
											'display_name' => $user_full_name,
											'first_name' => $first_name,
											'last_name' => $last_name,
											'user_url' => $user_url,
										);
									  
						$user_id 	= wp_insert_user( $userdata); 
						if(is_wp_error( $user_id )) {
							wp_die('There was an error in registration. Please contact your administrator.');
						}
						
						// run the query to add provider and identifier for the user
						$table_name = $wpdb->prefix . 'users';
						
						$provider_column = 'provider';
						$identifier_column = 'identifier';
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$provider_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$provider_column." VARCHAR(20) NOT NULL ");
						}
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$identifier_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$identifier_column." VARCHAR(100) NOT NULL ");
						}						
						
						$result = $wpdb->update( 
									$table_name, 
									array( 
										'provider' => $decrypted_app_name,	// string
										'identifier' => $decrypted_user_id	// string  
									), 
									array( 'ID' => $user_id ), 
									array( 
										'%s',	// value1
										'%s'	// value2
									), 
									array( '%d' ) 
								);	

						if($result === false)
						{
							//$wpdb->show_errors();
							//$wpdb->print_error();
							//exit;	
							wp_die('Error in update query');
						}						
						
						$user	= get_user_by('email', $user_email );
						if(get_option('mo_openid_login_role_mapping') && mo_openid_is_customer_valid()){
							$user->set_role( get_option('mo_openid_login_role_mapping') );
						}
						//Add meta if username in other language
						if(!empty($original_username)) {
							update_user_meta($user_id, 'moopenid_original_username', $original_username);
						}
						//Add meta if email in other language
						if(!empty($original_email)) {
							update_user_meta($user_id, 'moopenid_original_email', $original_email);
						}
						if(get_option('moopenid_social_login_avatar') && isset($user_picture)){
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
						}
						$_SESSION['mo_login'] = true;
						do_action( 'mo_user_register', $user_id);
						do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
						do_action( 'wp_login', $user->user_login, $user );
						wp_set_auth_cookie( $user_id, true );
					}
					$redirect_url = mo_openid_get_redirect_url();
					wp_redirect($redirect_url);
					exit;
				}	
			}
		}	

		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'moopenid' ) !== false ){
			//Decrypt all entries
			$decrypted_email = isset($_POST['email']) ? mo_openid_decrypt_sanitize($_POST['email']): '';
			$decrypted_user_name = isset($_POST['username']) ? mo_openid_decrypt_sanitize($_POST['username']): '';
			$decrypted_user_picture = isset($_POST['profilePic']) ? mo_openid_decrypt_sanitize($_POST['profilePic']): '';
			$decrypted_user_url = isset($_POST['profileUrl']) ? mo_openid_decrypt_sanitize($_POST['profileUrl']): '';
			$decrypted_user_url = urldecode($decrypted_user_url);
			$decrypted_first_name = isset($_POST['firstName']) ? mo_openid_decrypt_sanitize($_POST['firstName']): '';
			$decrypted_last_name = isset($_POST['lastName']) ? mo_openid_decrypt_sanitize($_POST['lastName']): '';
			$decrypted_app_name = isset($_POST['appName']) ? mo_openid_decrypt_sanitize($_POST['appName']): '';
			$decrypted_user_id = isset($_POST['userid']) ? mo_openid_decrypt_sanitize($_POST['userid']): '';
			
			//echo('email: '.$decrypted_email.' user_name'.$decrypted_user_name);exit;
			
			//check to ensure login starts at the click of social login button
			if(empty($decrypted_app_name)){
				wp_die('There was an error during login. Please try to login/register manually.<a href='.site_url().'> Back</a>');
			}
				
			if(isset( $_POST['firstName'] ) && isset( $_POST['lastName'] )){
				if(strcmp($decrypted_first_name, $decrypted_last_name)!=0)
					$user_full_name = $decrypted_first_name.' '.$decrypted_last_name;
				else
					$user_full_name = $decrypted_first_name;
				$first_name = $decrypted_first_name;
				$last_name = $decrypted_last_name;
			}	
			else{
				$user_full_name = $decrypted_user_name;
				$first_name = '';
				$last_name = '';
			}
			//Set Display Picture
			$user_picture = $decrypted_user_picture;
			
			//Set User URL
			$user_url = $decrypted_user_url;			
			
			//if email or username not returned from app
			if ( empty($decrypted_email) || empty($decrypted_user_name) )
			{	
			
				if( empty($decrypted_app_name) || empty($decrypted_user_id)){
					wp_die('There was an error during login. Please try to login manually.');
				}		
				else
				{
				//check if provider + identifier group exists
				global $wpdb;
				$id_returning_user = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where provider = %s AND identifier = %s",$decrypted_app_name,$decrypted_user_id));
				
				mo_openid_start_session();
				
				// if returning user whose appname + identifier exists, log him in
				if(isset($id_returning_user))
				{
					$user 	= get_user_by('id', $id_returning_user );
					if(get_option('moopenid_social_login_avatar') && isset($user_picture))
						update_user_meta($id_returning_user, 'moopenid_user_avatar', $user_picture);
					$_SESSION['mo_login'] = true;
					do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
					do_action( 'wp_login', $user->user_login, $user );
					wp_set_auth_cookie( $id_returning_user, true );
				}
				// if new user and profile completion is enabled
				elseif (get_option('mo_openid_enable_profile_completion')){
					
					$path = site_url();
					$path .= '/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,buttons,forms,l10n,login&amp;ver=4.8.1';
					echo mo_openid_profile_completion_form($path, $last_name, $first_name, $user_full_name, $user_url, $user_picture, $decrypted_user_name, $decrypted_email, $decrypted_app_name, $decrypted_user_id);
					exit;
				}
				// if new user and profile completion is disabled, auto create dummy data and register user
 				else
				{	
					// auto registration is enabled
					if(get_option('mo_openid_auto_register_enable')) {
						
						if(!empty($decrypted_email))
						{   
							$split_email = array();
							$split_email  = explode('@',$decrypted_email);
							$username = $split_email[0];
							$user_email = $decrypted_email;  
						}
						else if(!empty($decrypted_user_name))
						{	
							$split_app_name = array();
							$split_app_name = explode('_',$decrypted_app_name);
							$username = $decrypted_user_name;
							$user_email = $decrypted_user_name.'@'.$split_app_name[0].'.com';
						}
						else
						{
							$split_app_name = array();
							$split_app_name = explode('_',$decrypted_app_name);						
							$username = 'user_'.get_option('mo_openid_user_count');
							$user_email =  'user_'.get_option('mo_openid_user_count').'@'.$split_app_name[0].'.com';
							//update_option('mo_openid_user_count',get_option('mo_openid_user_count')+1); update only if user is successfully created
						}						
						
						
						$random_password 	= wp_generate_password( 10, false );
						
						$userdata = array(
											'user_login'  =>  $username,
											'user_email'    =>  $user_email,
											'user_pass'   =>  $random_password,
											'display_name' => $user_full_name,
											'first_name' => $first_name,
											'last_name' => $last_name,
											'user_url' => $user_url,
										);
						
						  
						$user_id 	= wp_insert_user( $userdata); 
						
						if(is_wp_error( $user_id )) {
							//print_r($user_id);
							wp_die('There was an error in registration. Please contact your administrator.');
						}
					
						update_option('mo_openid_user_count',get_option('mo_openid_user_count')+1);
						// run the query to add provider and identifier for the user
						$table_name = $wpdb->prefix . 'users';
						
						$provider_column = 'provider';
						$identifier_column = 'identifier';
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$provider_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$provider_column." VARCHAR(20) NOT NULL ");
						}
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$identifier_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$identifier_column." VARCHAR(100) NOT NULL ");
						}						
						
						$result = $wpdb->update( 
									$table_name, 
									array( 
										'provider' => $decrypted_app_name,	// string
										'identifier' => $decrypted_user_id	// string  
									), 
									array( 'ID' => $user_id ), 
									array( 
										'%s',	// value1
										'%s'	// value2
									), 
									array( '%d' ) 
								);	

						if($result === false)
						{
							//$wpdb->show_errors();
							//$wpdb->print_error();
							//exit;	
							wp_die('Error in update query');
						}
					
						$user	= get_user_by('email', $user_email );
						if(get_option('mo_openid_login_role_mapping') && mo_openid_is_customer_valid()){
							$user->set_role( get_option('mo_openid_login_role_mapping') );
						}
						if(get_option('moopenid_social_login_avatar') && isset($user_picture)){
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
						}
						$_SESSION['mo_login'] = true;
						
						//registration hook
						do_action( 'mo_user_register', $user_id);
						//login hook
						do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
						do_action( 'wp_login', $user->user_login, $user );
						wp_set_auth_cookie( $user_id, true );
				}
				
				$redirect_url = mo_openid_get_redirect_url();
				wp_redirect($redirect_url);
				exit;
					
				}

			}
					
				
			}
			//email and username are both returned..dont show profile completion 
			else{
				
				global $wpdb;
				$user_email = sanitize_email($decrypted_email);
				$username = strtolower(str_replace(" ","",$decrypted_user_name));

				//Checking if email or username already exist
				$email_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_email = %s", $user_email));
				$username_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_login = %s", $username));

				mo_openid_start_session();
				if( isset($email_user_id)) { // user is a member
					$user 	= get_user_by('id', $email_user_id );
					$user_id 	= $user->ID;
					if(get_option('moopenid_social_login_avatar') && isset($user_picture))
						update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
					$_SESSION['mo_login'] = true;
					do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
					do_action( 'wp_login', $user->user_login, $user );
					wp_set_auth_cookie( $user_id, true );

				} 
				else {  
				// this user is a guest
					
					// auto registration is enabled
					if(get_option('mo_openid_auto_register_enable')) {
						$random_password 	= wp_generate_password( 10, false );
						
						if( isset($username_user_id) ){
							$email = array();
							$email = explode('@', $user_email);
							$username = $email[0];
							$username_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_login = %s", $username));
							if( isset($username_user_id) ){
								echo '<br/>This username already exists. Please ask the administrator to create your account with a unique username';
								exit();
							}
						}
						
						$userdata = array(
											'user_login'  =>  $username,
											'user_email'    =>  $user_email,
											'user_pass'   =>  $random_password,
											'display_name' => $user_full_name,
											'first_name' => $first_name,
											'last_name' => $last_name,
											'user_url' => $user_url,
										);
						
						  
						$user_id 	= wp_insert_user( $userdata); 
						
						if(is_wp_error( $user_id )) {
							//print_r($user_id);
							wp_die('There was an error in registration. Please contact your administrator.');
						}
						
						// run the query to add provider and identifier for the user
						$table_name = $wpdb->prefix . 'users';
						
						$provider_column = 'provider';
						$identifier_column = 'identifier';
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$provider_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$provider_column." VARCHAR(20) NOT NULL ");
						}
						
						$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ".$table_name." AND column_name = ".$identifier_column);
						if(empty($row)){
						   $wpdb->query("ALTER TABLE ".$table_name." ADD ".$identifier_column." VARCHAR(100) NOT NULL ");
						}				
						
						$result = $wpdb->update( 
									$table_name, 
									array( 
										'provider' => $decrypted_app_name,	// string
										'identifier' => $decrypted_user_id	// string  
									), 
									array( 'ID' => $user_id ), 
									array( 
										'%s',	// value1
										'%s'	// value2
									), 
									array( '%d' ) 
								);	

						if($result === false)
						{
							//$wpdb->show_errors();
							//$wpdb->print_error();
							//exit;							
							wp_die('Error in update query');
						}
						
						$user	= get_user_by('email', $user_email );
						if(get_option('mo_openid_login_role_mapping') && mo_openid_is_customer_valid()){
							$user->set_role( get_option('mo_openid_login_role_mapping') );
						}
						//Add meta if username in other language
						if(!empty($original_username)) {
							update_user_meta($user_id, 'moopenid_original_username', $original_username);
						}
						//Add meta if email in other language
						if(!empty($original_email)) {
							update_user_meta($user_id, 'moopenid_original_email', $original_email);
						}
						if(get_option('moopenid_social_login_avatar') && isset($user_picture)){
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
						}
						$_SESSION['mo_login'] = true;
						
						//registration hook
						do_action( 'mo_user_register', $user_id);
						//login hook
						do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
						do_action( 'wp_login', $user->user_login, $user );
						wp_set_auth_cookie( $user_id, true );
					}
					$redirect_url = mo_openid_get_redirect_url();
					wp_redirect($redirect_url);
					exit;					
				}				
			}
		}
		
		if( isset( $_REQUEST['autoregister']) and strpos($_REQUEST['autoregister'],'false') !== false ) {
			if(!is_user_logged_in()) {
				mo_openid_disabled_register_message();
			}
		}
	}
	
	function mo_openid_validate_otp_form($path, $username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name ,$first_name, $decrypted_app_name, $decrypted_user_id){					   
		$html =         '<head><link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>
		  
						<body class="login login-action-login wp-core-ui  locale-en-us">
						<div style="position:fixed;background:#f1f1f1;"></div>
						<div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:130px;">
						<div style="width: 500px; margin: 30px auto;">   
					    <form name="f" method="post" action="">
					    <div style="background: white;margin-top:-15px;padding: 15px;">
					   
						<span style="margin:120px;font-size: 24px;font-family: Arial">Verify your email</span><br>
						<div style="padding: 12px;"></div>
						<div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
					    <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>We have sent a verification code to given email. Please verify your account with it.</div>	<br>					
						<p>
						<label for="user_login">Enter your verification code<br/>
						<input type="text" pattern="\d{4,5}" class="input" name="otp_field" value=""  size="20" required/></label>
						</p>
						<input type="hidden" name="username_field" value='.$username.'>
						<input type="hidden" name="email_field" value='.$user_email.'>						
						<input type="hidden" name="first_name" value='.$first_name.'>
						<input type="hidden" name="last_name" value='.$last_name.'>
						<input type="hidden" name="user_full_name" value='.$user_full_name.'>
						<input type="hidden" name="user_url" value='.$user_url.'>
						<input type="hidden" name="user_picture" value='.$user_picture.'>
						<input type="hidden" name="transaction_id" value='.$transaction_id.'>
						<input type="hidden" name="decrypted_app_name" value='.$decrypted_app_name.'>
						<input type="hidden" name="decrypted_user_id" value='.$decrypted_user_id.'>						
						<input type="hidden" name="option" value="mo_openid_otp_validation">
						</div>
						<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Submit"/>
						</p> 
					    </form>
					    </div>
					    </div>
					    </body>';
		return $html;	
	}
	
	function mo_openid_username_already_exists($path,$last_name,$first_name,$user_full_name,$user_url,$user_picture,$username,$user_email, $decrypted_app_name, $decrypted_user_id){
		$html = '<style>.form-input-validation.is-error {color: #d94f4f;}</style><head><link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>
		  
						<body class="login login-action-login wp-core-ui  locale-en-us">
						<div style="position:fixed;background:#f1f1f1;"></div>
						<div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:130px;">
						<div style="width: 500px; margin: 30px auto;">   
					    <form name="f" method="post" action="">
					    <div style="background: white;margin-top:-15px;padding: 15px;">
					   
						<span style="margin:120px;font-size: 24px;font-family: Arial">Profile Completion</span>
						<p>
						<label for="user_login">Username<br/>
						<input type="text" class="input" name="username_field" value='.$username.'  size="20" required>
						<span align="center" class="form-input-validation is-error">Entered username already exists. Try some other username.</span>
						</label>
						</p>
						<br>
						<p>
						<label for="user_pass">Email<br />
						<input type="email"  name="email_field" class="input" value='.$user_email.' size="20" required></label>						
						</p>
						<input type="hidden" name="first_name" value='.$first_name.'>
						<input type="hidden" name="last_name" value='.$last_name.'>
						<input type="hidden" name="user_full_name" value='.$user_full_name.'>
						<input type="hidden" name="user_url" value='.$user_url.'>
						<input type="hidden" name="user_picture" value='.$user_picture.'>
						<input type="hidden" name="decrypted_app_name" value='.$decrypted_app_name.'>
						<input type="hidden" name="decrypted_user_id" value='.$decrypted_user_id.'>						
						<input type="hidden" name="option" value="mo_openid_profile_form_submitted">
						</div>
						<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Submit"/>
						</p> 
					   </form>
					   </div>
					   </div>
					   </body>';
		return $html;
		
	}
	
	function mo_openid_profile_completion_form($path,$last_name,$first_name,$user_full_name,$user_url,$user_picture, $decrypted_user_name, $decrypted_email, $decrypted_app_name, $decrypted_user_id){
		$html = '		<style>.form-input-validation.note {color: #d94f4f;}</style>
						<head><link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>
		  
						<body class="login login-action-login wp-core-ui  locale-en-us">
						<div style="position:fixed;background:#f1f1f1;"></div>
						<div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:130px;">
						<div style="width: 500px; margin: 30px auto;">   
					    <form name="f" method="post" action="">
					    <div style="background: white;margin-top:-15px;padding: 15px;">
					   
						<span style="margin:120px;font-size: 24px;font-family: Arial">Profile Completion</span><br>
						<div style="padding: 12px;"></div>
						<div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
					    <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>If you are an existing user on this site, enter your registered email and username. If you are a new user, please edit/fill the details</div>	<br>					
						<p>
						<label for="user_login">Username<br/>
						<input type="text" class="input" name="username_field"  size="20" required value='.$decrypted_user_name.'></label>
						</p>
						<p>
						<label for="user_pass">Email<br />
						<input type="email"  class="input" name="email_field"  size="20"  required value='.$decrypted_email.'></label>
						<span align="center" class="form-input-validation note">We will be sending a verification code to this email to verify it. Please enter a valid email address.</span>
						</p>
						<input type="hidden" name="first_name" value='.$first_name.'>
						<input type="hidden" name="last_name" value='.$last_name.'>
						<input type="hidden" name="user_full_name" value='.$user_full_name.'>
						<input type="hidden" name="user_url" value='.$user_url.'>
						<input type="hidden" name="user_picture" value='.$user_picture.'>
						<input type="hidden" name="decrypted_app_name" value='.$decrypted_app_name.'>
						<input type="hidden" name="decrypted_user_id" value='.$decrypted_user_id.'>
						<input type="hidden" name="option" value="mo_openid_profile_form_submitted">
						</div>
						<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Submit"/>
						</p> 
					   </form>
					   </div>
					   </div>
					   </body>';
		return $html;
	}

	function mo_openid_invalid_otp_form($path, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id, $username,$user_email, $transaction_id){
		$html = '<style>.form-input-validation.is-error {color: #d94f4f;}</style>
						<head><link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>
		  
						<body class="login login-action-login wp-core-ui  locale-en-us">
						<div style="position:fixed;background:#f1f1f1;"></div>
						<div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:130px;">
						<div style="width: 500px; margin: 30px auto;">   
					    <form name="f" method="post" action="">
					    <div style="background: white;margin-top:-15px;padding: 15px;">
					   
						<span style="margin:120px;font-size: 24px;font-family: Arial">Verify your email</span>
						
						<p>
						<label for="user_login">Enter verification code<br/>
						<input type="text" pattern="\d{4,5}" class="input" name="otp_field" value=""  size="20" required></label>
						<span align="center" class="form-input-validation is-error">You have entered an invalid verification code. Enter a valid code.</span>
						</p>
						
						<input type="hidden" name="first_name" value='.$first_name.'>
						<input type="hidden" name="last_name" value='.$last_name.'>
						<input type="hidden" name="user_full_name" value='.$user_full_name.'>
						<input type="hidden" name="user_url" value='.$user_url.'>
						<input type="hidden" name="user_picture" value='.$user_picture.'>
						<input type="hidden" name="decrypted_app_name" value='.$decrypted_app_name.'>
						<input type="hidden" name="decrypted_user_id" value='.$decrypted_user_id.'>		
						<input type="hidden" name="username_field" value='.$username.'>
						<input type="hidden" name="email_field" value='.$user_email.'>
						<input type="hidden" name="transaction_id" value='.$transaction_id.'>	
						<input type="hidden" name="option" value="mo_openid_otp_validation">
						</div>
						<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Submit"/>
						</p> 
					   </form>
					   </div>
					   </div>
					   </body>';	
		return $html;
	}
	
	function mo_openid_decrypt_sanitize($param) {
		if(strcmp($param,'null')!=0 && strcmp($param,'')!=0){
			$customer_token = get_option('mo_openid_customer_token');
			$decrypted_token = decrypt_data($param,$customer_token);
			// removes control characters and some blank characters 
			$decrypted_token_sanitise = preg_replace('/[\x00-\x1F\x7F\x81\x8D\x8F\x90\x9D\xA0\xAD]/', '', $decrypted_token); 
			//strips space,tab,newline,carriage return,NUL-byte,vertical tab.
			return trim($decrypted_token_sanitise); 			
		}else{
			return '';
		}
	}
	
	function mo_openid_disabled_register_message() {
		$message = get_option('mo_openid_register_disabled_message').' Go to <a href="' . site_url() .'">Home Page</a>'; 
		wp_die($message);
		}
		
	function mo_openid_get_redirect_url() {
		$option = get_option( 'mo_openid_login_redirect' );
		$redirect_url = site_url();
		if( $option == 'same' ) {
			if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
				$http = "https://";
			} else {
				$http =  "http://";
			}
			$redirect_url = urldecode(html_entity_decode(esc_url($http . $_SERVER["HTTP_HOST"] . str_replace('option=moopenid','',$_SERVER['REQUEST_URI']))));
			if(html_entity_decode(esc_url(remove_query_arg('ss_message', $redirect_url))) == wp_login_url() || strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE || strpos($_SERVER['REQUEST_URI'],'wp-admin') !== FALSE){
				$redirect_url = site_url().'/';
			}
		} else if( $option == 'homepage' ) {
			$redirect_url = site_url();
		} else if( $option == 'dashboard' ) {
			$redirect_url = admin_url();
		} else if( $option == 'custom' ) {
			$redirect_url = get_option('mo_openid_login_redirect_url');
		}
		if(strpos($redirect_url,'?') !== FALSE) {
			$redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '&autoregister=false';
		} else{
			$redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '?autoregister=false';
		}
		return $redirect_url;
	}
	
	function mo_openid_redirect_after_logout($logout_url) {
		if(get_option('mo_openid_logout_redirection_enable')){
			$option = get_option( 'mo_openid_logout_redirect' );
			$redirect_url = site_url();
			if( $option == 'homepage' ) {
				$redirect_url = $logout_url . '&redirect_to=' .home_url()  ;
			}
			else if($option == 'currentpage'){
				if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
					$http = "https://";
				} else {
					$http =  "http://";
				}
				$redirect_url = $logout_url . '&redirect_to=' . $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
			}
			else if($option == 'login') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . '/wp-admin' ;
			}
			else if($option == 'custom') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . (null !== get_option('mo_openid_logout_redirect_url')?get_option('mo_openid_logout_redirect_url'):'');
			}
			return $redirect_url;
		}else{
			return $logout_url;
		}
			
	} 

	function mo_openid_login_redirect($username = '', $user = NULL){
		mo_openid_start_session();
		if(is_string($username) && $username && is_object($user) && !empty($user->ID) && ($user_id = $user->ID) && isset($_SESSION['mo_login']) && $_SESSION['mo_login']){
			$_SESSION['mo_login'] = false;
			wp_set_auth_cookie( $user_id, true );
			$redirect_url = mo_openid_get_redirect_url();
			wp_redirect($redirect_url);
			exit;
		}
	} 
	
    function send_otp_token($email){
		$otp = wp_rand(1000,99999);
		$customerKey = get_option('mo_openid_admin_customer_key');
		$stringToHash = $customerKey . $otp;
		$transactionId = hash("sha512", $stringToHash);
        //wp_email function will come here
		$subject='Verification code validation link';
		$message='Dear User,

Your verification code for completing your profile is: '.$otp.'

Please use this code to complete your profile. Do not share this code with anyone.

Thank you.';
				
		$response = wp_mail($email, $subject, $message);
		if($response){
			mo_openid_start_session();
			$_SESSION['mo_otptoken'] = true;
			$_SESSION['sent_on'] = time();
			$content = array('status' => 'SUCCESS','tId' => $transactionId);
		}
		else
			$content = array('status' => 'FAILURE');
		return $content;
	}
	
	function validate_otp_token($transactionId,$otpToken){
		mo_openid_start_session();
		$customerKey = get_option('mo_openid_admin_customer_key');		
		if($_SESSION['mo_otptoken']){
			$pass =	checkTimeStamp($_SESSION['sent_on'],time());
			$pass = checkTransactionId($customerKey, $otpToken, $transactionId, $pass);
			if($pass)
				$content = array('status' => 'SUCCESS');
			else
				$content = array('status' => 'FAILURE');
			unset($_SESSION['$mo_otptoken']);
		}
		else
			$content = array('status' =>'FAILURE');
			
		return $content;
	}
	
     /*
	 * This function checks the time otp was sent to and the time
	 * user is validating the otp. The time difference shouldn't be 
	 * more that 60 seconds.
	 *
	 * @param $sentTime - the time otp was sent to
	 * @param $validatedTime - the time otp was validated
	 */
	function checkTimeStamp($sentTime,$validatedTime){
		$from_time 	= strtotime($sentTime);
		$to_time 	= strtotime($validatedTime);
		$diff 		= round(abs($to_time - $from_time) / 60,2);
		if($diff>5)
			return false;
		else
			return true;
	}
	
	 /**
	 * This function checks and compares the transaction set in session
	 * and one generated during validation. Both need to match for the 
	 * otp to be validated.
	 *
	 * @param $customerKey - the customer key of the user
	 * @param $otpToken - otp token entered by the user
	 * @param $transactionId - the transaction id in session
	 * @param $pass - the boolean value passed after the time check
	 */
	function checkTransactionId($customerKey,$otpToken,$transactionId,$pass){
		if(!$pass){ 
			return false;
		}
		$stringToHash 	= $customerKey . $otpToken;
		$txtID 		= hash("sha512", $stringToHash);
		if($txtID == $transactionId)
			return true;
	}	
	
	if(get_option('mo_openid_logout_redirection_enable') == 1){
		add_filter( 'logout_url', 'mo_openid_redirect_after_logout',0,1);
	}
	
add_action( 'widgets_init', create_function( '', 'register_widget( "mo_openid_login_wid" );' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget( "mo_openid_sharing_ver_wid" );' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget( "mo_openid_sharing_hor_wid" );' ) );

add_action( 'init', 'mo_openid_login_validate' );
//add_action( 'init', 'mo_openid_start_session' );
//add_action( 'wp_logout', 'mo_openid_end_session' );
add_action( 'wp_login', 'mo_openid_login_redirect', 9, 2);
add_filter('sanitize_user', 'mo_openid_sanitize_user', 10, 3);
remove_filter('sanitize_title','sanitize_title_with_dashes', 10);
add_filter( 'sanitize_title', 'mo_openid_sanitize_title_with_dashes', 10, 3 );

	function mo_openid_sanitize_user($username, $raw_username, $strict) { 
		
		$username = wp_strip_all_tags( $raw_username );
		$username = remove_accents( $username );
		// Kill octets
		$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
		$username = preg_replace( '/&.+?;/', '', $username ); // Kill entities
		// If strict, reduce to ASCII and Cyrillic characters for max portability.
		if ( $strict )
		$username = preg_replace( '|[^a-zあ-ん\p{Han}а-я0-9ㅂㅈㄷㄱ쇼ㅕㅑㅐㅔㅁㄴㅇㄹ호ㅓㅏㅣㅋㅌㅊ퓨ㅜㅡㅃㅉㄸㄲ썌ㅖ _.\-@]|iu', '', $username );
		$username = trim( $username );
		// Consolidate contiguous whitespace
		$username = preg_replace( '|\s+|', ' ', $username );
		return $username;
	}

	function mo_openid_sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display' ) {
		
		$title = strip_tags($raw_title);
		// Preserve escaped octets.
		$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		// Remove percent signs that are not part of an octet.
		$title = str_replace('%', '', $title);
		// Restore octets.
		$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
		if (seems_utf8($title)) {
			if (function_exists('mb_strtolower')) {
				$title = mb_strtolower($title, 'UTF-8');
			}
		}

		$title = strtolower($title);

		if ( 'save' == $context ) {
			// Convert nbsp, ndash and mdash to hyphens
			$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
			// Convert nbsp, ndash and mdash HTML entities to hyphens
			$title = str_replace( array( '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ), '-', $title );
			// Convert forward slash to hyphen
			$title = str_replace( '/', '-', $title );

			// Strip these characters entirely
			$title = str_replace( array(
				// iexcl and iquest
				'%c2%a1', '%c2%bf',
				// angle quotes
				'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
				// curly quotes
				'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
				'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
				// copy, reg, deg, hellip and trade
				'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
				// acute accents
				'%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
				// grave accent, macron, caron
				'%cc%80', '%cc%84', '%cc%8c',
			), '', $title );
			// Convert times to x
			$title = str_replace( '%c3%97', 'x', $title );
		}

		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = str_replace('.', '-', $title);

		//$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		$title = preg_replace( '|[^a-zあ-ん\p{Han}а-я0-9ㅂㅈㄷㄱ쇼ㅕㅑㅐㅔㅁㄴㅇㄹ호ㅓㅏㅣㅋㅌㅊ퓨ㅜㅡㅃㅉㄸㄲ썌ㅖ _.\-@]|iu', '', $title );
		$title = preg_replace('/\s+/', '-', $title);
		$title = preg_replace('|-+|', '-', $title);
		$title = trim($title, '-');

		return $title;
	}
}
?>