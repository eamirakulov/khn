<?php

/**
 * Admin functions
 *
 * @package   Thim_Core
 * @since     0.1.0
 */

/**
 * Clean all keys which is a number, e.g: Array( [0] => ..., ..., [69] => ...);
 *
 * @since 0.4.0
 *
 * @param $theme_mods
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_clean_theme_mods' ) ) {
	function thim_clean_theme_mods( $theme_mods ) {
		// Gets mods keys
		$mod_keys = array_keys( $theme_mods );
		foreach ( $mod_keys as $mod_key ) {
			// Removes from array if the key is a number
			if ( is_numeric( $mod_key ) ) {
				unset( $theme_mods[ $mod_key ] );
			}
		}

		return $theme_mods;
	}
}

if ( ! function_exists( '_thim_export_skip_object_meta' ) ) {
	function _thim_export_skip_object_meta( $return_me, $meta_key, $meta_value = false ) {
		if ( '_thim_demo_content' == $meta_key ) {
			$return_me = true;
		}

		return $return_me;
	}

	/**
	 * Skip export object's meta data if it's _thim_demo_content
	 */
	add_filter( 'wxr_export_skip_postmeta', '_thim_export_skip_object_meta', 1000, 2 );
	add_filter( 'wxr_export_skip_commentmeta', '_thim_export_skip_object_meta', 1000, 2 );
	add_filter( 'wxr_export_skip_termmeta', '_thim_export_skip_object_meta', 1000, 3 );
}

/**
 * Parse url youtube to id.
 *
 * @since 1.0.0
 *
 * @param $url
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_parse_id_youtube' ) ) {
	function thim_parse_id_youtube( $url ) {
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
			$video_id = $match[1];

			return $video_id;
		}

		return false;
	}
}

/**
 * Redirect to url.
 *
 * @since 0.8.9
 *
 * @param $url
 */
if ( ! function_exists( 'thim_core_redirect' ) ) {
	function thim_core_redirect( $url ) {
		if ( headers_sent() ) {
			echo "<meta http-equiv='refresh' content='0;URL=$url' />";
		} else {
			wp_redirect( $url );
		}

		exit();
	}
}

/**
 * Unserialize (avoid whitespace string).
 *
 * @since 1.0.0
 *
 * @param $string
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_maybe_unserialize' ) ) {
	function thim_maybe_unserialize( $string ) {
		$value = maybe_unserialize( $string );

		if ( ! $value && strlen( $string ) ) {
			$string = trim( $string );
			$value  = maybe_unserialize( $string );
		}

		return $value;
	}
}

/**
 * Wrapper for set_time_limit to see if it is enabled.
 *
 * @since 1.1.1
 *
 * @param $limit integer
 */
function thim_core_set_time_limit( $limit = 0 ) {
	if ( function_exists( 'set_time_limit' ) && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) ) {
		set_time_limit( $limit );
	}
}

/**
 * Get ownership.
 *
 * @since 1.2.0
 *
 * @param $path
 *
 * @return array|bool
 */
function thim_core_get_ownership( $path ) {
	if ( ! function_exists( 'posix_getpwuid' ) ) {
		return array(
			'owner' => '',
			'group' => '',
		);
	}

	$default = array(
		'name' => '',
	);

	$owner = wp_parse_args( posix_getpwuid( fileowner( $path ) ), $default );
	$group = wp_parse_args( posix_getpwuid( filegroup( $path ) ), $default );

	return array(
		'owner' => $owner['name'],
		'group' => $group['name'],
	);
}

/**
 * Get chmod.
 *
 * @since 1.2.0
 *
 * @param $path
 *
 * @return string
 */
function thim_core_get_chmod( $path ) {
	return substr( sprintf( '%o', fileperms( $path ) ), - 4 );
}

/**
 * Get is child theme.
 *
 * @since 1.0.3
 *
 * @return bool
 */
function thim_core_is_child_theme() {
	$stylesheet = get_stylesheet();
	$template   = get_template();

	return ( $stylesheet != $template );
}

/**
 * Generate token.
 *
 * @since 1.2.1
 *
 * @return string
 */
function thim_core_generate_token() {
	$text  = bin2hex( openssl_random_pseudo_bytes( 16 ) );
	$token = md5( $text );

	return $token;
}

/**
 * Generate code to request to private server.
 *
 * @since 1.4.2
 *
 * @param $key string
 *
 * @return string
 */
function thim_core_generate_code_by_site_key( $key ) {
	$code = time() . '.' . $key;

	return base64_encode( $code );
}

/**
 * Test request.
 *
 * @since 1.4.3
 *
 * @param $url
 *
 * @return array
 */
function thim_core_test_request( $url ) {
	$response         = wp_safe_remote_get( $url );
	$successful       = true;
	$message_response = 'success';
	if ( is_wp_error( $response ) ) {
		$successful       = false;
		$message_response = $response->get_error_message();
	}

	return array(
		'return'  => $successful,
		'message' => $message_response,
	);
}
