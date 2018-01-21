<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_twitter( $atts ) {

	$instance = shortcode_atts( array(
		'title'    => '',
		'username' => 'press_thim',
		'number'   => '2',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'vc-shortcodes/shortcodes/twitter/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'vc-shortcodes/shortcodes/twitter/tpl/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	$twitter_id = ! empty( $instance['username'] ) ? $instance['username'] : 'press_thim';
	$number     = ! empty( $instance['number'] ) ? $instance['number'] : 2;

	$default      = array(
		'consumer_key'        => 'fCuXeJBzIhikOjNFmh7FC7Cpz',
		'consumer_secret'     => 'tLefeE8nyARq1aIAJF7GSIhAoAxQiAMU9aX0RE79F6IVAcfA7J',
		'access_token'        => '3546925700-hzs7KwBYCqsZxP6sYRtjIS4V1TIMgh0zY0Hlhb5',
		'access_token_secret' => 'TmI0MW7QH7KTfdePVX1Swsie7i2K1RziunVc46y0wOALn'
	);
	$thim_twitter = get_option( 'thim_twitter', $default );

	$consumer_key        = $thim_twitter['consumer_key'];
	$consumer_secret     = $thim_twitter['consumer_secret'];
	$access_token        = $thim_twitter['access_token'];
	$access_token_secret = $thim_twitter['access_token_secret'];

	if ( $twitter_id && $number && $consumer_key && $consumer_secret && $access_token && $access_token_secret ) {
		$transName = 'list_tweets_' . $twitter_id;
		$cacheTime = 10;

		$twitterData = get_transient( $transName );
		@$twitter = json_decode( get_transient( $transName ), true );

		if ( false === $twitterData || isset( $twitter['errors'] ) ) {
			$twitter_token = 'twitter_token_' . $twitter_id;
			$token         = ! empty( $thim_twitter[ $twitter_token ] ) ? $thim_twitter[ $twitter_token ] : false;
			if ( ! $token ) {
				// preparing credentials
				$credentials = $consumer_key . ':' . $consumer_secret;
				$toSend      = base64_encode( $credentials );
				// http post arguments
				$args_twitter = array(
					'method'      => 'POST',
					'httpversion' => '1.1',
					'blocking'    => true,
					'headers'     => array(
						'Authorization' => 'Basic ' . $toSend,
						'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8'
					),
					'body'        => array( 'grant_type' => 'client_credentials' )
				);

				//add_filter( 'https_ssl_verify', '__return_false' );
				$response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args_twitter );

				$keys = json_decode( wp_remote_retrieve_body( $response ) );

				if ( $keys ) {
					// saving token to wp_options table
					$token                          = $keys->access_token;
					$thim_twitter[ $twitter_token ] = $token;
					update_option( 'thim_twitter', $thim_twitter );
				}
			}
			// we have bearer token wether we obtained it from API or from options
			$args_twitter = array(
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => array(
					'Authorization' => "Bearer $token"
				)
			);

			//add_filter( 'https_ssl_verify', '__return_false' );
			$api_url  = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $twitter_id . '&count=' . $number;
			$response = wp_remote_get( $api_url, $args_twitter );
			set_transient( $transName, wp_remote_retrieve_body( $response ), 60 * $cacheTime );
		}
	}
	@$twitter = json_decode( get_transient( $transName ), true );

	ob_start();
	//echo '<div class="thim-widget-twitter">';
	include $widget_template;
	//echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-twitter', 'thim_shortcode_twitter' );


