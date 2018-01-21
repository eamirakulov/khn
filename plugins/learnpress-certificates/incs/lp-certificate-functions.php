<?php
function learn_press_certificate_upload_dir( $subfolder = false ) {
	static $upload_dir = array();
	$base_folder = 'certificates';

	if ( $subfolder === false ) {
		$current_user = wp_get_current_user();
		$subfolder    = $current_user->user_login;
	}

	if ( empty( $upload_dir[$subfolder] ) ) {
		$_upload_dir = wp_upload_dir();

		$dir                    = $_upload_dir['basedir'] . "/{$base_folder}";
		$url                    = $_upload_dir['baseurl'] . "/{$base_folder}";
		$userdir                = $dir . ( $subfolder ? '/' . $subfolder : '' );
		$userurl                = $url . ( $subfolder ? '/' . $subfolder : '' );
		$upload_dir[$subfolder] = array(
			'path'     => $dir,
			'url'      => $url,
			'userpath' => $userdir,
			'userurl'  => $userurl
		);
		@mkdir( $dir );
		@chmod( $dir, 0777 );
		@mkdir( $userdir );
		@chmod( $userdir, 0777 );

	}
	return $upload_dir[$subfolder];
}

function learn_press_get_certificate_tab_slug() {
	return sanitize_title( 'certificates' );
}

function _learn_press_get_user_certificates( $user_id = 0, $course_id = 0 ) {
	global $wpdb;
	if ( !$user_id ) {
		$user_id = get_current_user_id();
	}

	$user = learn_press_get_user( $user_id );

	$where_format = array( null, $user_id, 'finished' );
	$where        = "
		WHERE
		 	uc.user_id = %d
		 	AND uc.status = %s
	";
	if ( $course_id ) {
		$where .= "\nAND c.ID = %d";
		$where_format[] = $course_id;
	}
	$where_format[0] = $where;
	$where           = call_user_func_array( array( $wpdb, 'prepare' ), $where_format );
	if ( version_compare( LEARNPRESS_VERSION, '2.0', '<' ) ) {
		$query = $wpdb->prepare( "
			SELECT c.*, cert.ID as cert_id, uc.*
			FROM {$wpdb->posts} c
			INNER JOIN {$wpdb->postmeta} cm ON cm.post_id = c.ID AND cm.meta_key = %s
			INNER JOIN {$wpdb->posts} cert ON cert.ID = cm.meta_value
			INNER JOIN {$wpdb->prefix}learnpress_user_courses uc ON c.ID = uc.course_id
			$where
		", '_lp_cert' );
	} else {
		$query = $wpdb->prepare( "
			SELECT * FROM(
				SELECT  c.*, cert.ID as cert_id, uc.*
				FROM {$wpdb->posts} c
				INNER JOIN {$wpdb->postmeta} cm ON cm.post_id = c.ID AND cm.meta_key = %s
				INNER JOIN {$wpdb->posts} cert ON cert.ID = cm.meta_value
				INNER JOIN {$wpdb->prefix}learnpress_user_items uc ON c.ID = uc.item_id
				$where
				ORDER BY user_item_id DESC
			) as x GROUP BY item_id
		", '_lp_cert' );
	}
	$results = array();
	if ( $_results = $wpdb->get_results( $query ) ) {
		$user = learn_press_get_user( $user_id );
		foreach ( $_results as $res ) {
			if ( is_callable( 'learn_press_setup_user_course_data' ) ) {
				learn_press_setup_user_course_data( $user_id, $res->ID );
			}
			if ( !( $course_results = $user->has_passed_course( $res->ID ) ) ) {
				continue;
			}
			$results[] = (object) array(
				'course_id'      => $res->ID,
				'cert_id'        => $res->cert_id,
				'user_id'        => $user_id,
				'course_results' => $course_results
			);
		}
	}
	return $course_id ? ( !empty( $results[0] ) ? $results[0] : false ) : $results;
}

function learn_press_get_user_certificates( $user_id = 0, $course_id = 0 ) {
	// backward compatibility
	if ( version_compare( LEARNPRESS_VERSION, '2.0', '<' ) || !$course_id ) {
		return _learn_press_get_user_certificates( $user_id, $course_id );
	}
	if ( $course_id ) {
		$user = learn_press_get_user( $user_id );
		$course_results = $user->get_course_info( $course_id );

		if ( !$course_results || $course_results['status'] != 'finished' ) {
			return false;
		}

		if ( ! $user->has_passed_course( $course_id ) ) {
			return false;
		}

		$results = (object) array(
			'course_id'      => $course_id,
			'cert_id'        => learn_press_course_has_cert( $course_id ),
			'user_id'        => $user_id,
			'course_results' => $course_results
		);
	} else {
		$results = array();
	}

	return $results;
}

function learn_press_course_has_cert( $course_id ) {
	return get_post_meta( $course_id, '_lp_cert', true );
}

function learn_press_get_certificate_by_name( $name ) {
	global $wpdb;
	$query = $wpdb->prepare( "
		SELECT *
		FROM {$wpdb->posts} p
		WHERE p.post_name = %s
			AND p.post_type = %s
			AND p.post_status = %s
	", $name, 'lp_cert', 'publish' );

	return $wpdb->get_row( $query );
}

function learn_press_get_certificate_course( $cert_id ) {
	$args = array(
		'post_type'  => 'lp_course',
		'meta_query' => array(
			array(
				'key'   => '_lp_cert',
				'value' => $cert_id
			)
		)
	);
	return ( $posts = get_posts( $args ) ) ? $posts[0] : false;
}

function learn_press_certificate_permalink( $cert_id, $course_id, $user_id = 0 ) {
	$user = learn_press_get_user( $user_id ? $user_id : get_current_user_id() );

	$url = add_query_arg( '', '', learn_press_get_page_link( 'profile' ) . $user->user_login ) . '/' . learn_press_get_certificate_tab_slug() . '?view-cert=' . get_post_field( 'post_name', $cert_id ) . '&course_id=' . $course_id;
	return $url;//get_the_permalink( $cert_id );
}

function learn_press_certificates_template( $name, $args = null ) {
	learn_press_get_template( $name, $args, LP_ADDON_CERTIFICATES_THEME_PATH, LP_ADDON_CERTIFICATES_PATH . '/templates/' );
}

function learn_press_certificates_locate_template( $name ) {
	return learn_press_locate_template( $name, LP_PLUGIN_PATH . 'templates/addons/certificates', LP_ADDON_CERTIFICATES_PATH );
}

if ( !function_exists( 'learn_press_load_plugin_text_domain' ) ) {
	/**
	 * Load plugin text domain
	 *
	 * @param        string
	 * @param        mixed
	 */
	function learn_press_load_plugin_text_domain( $path, $text_domain = true ) {
		$plugin_folder = basename( $path );
		if ( true === $text_domain ) {
			$text_domain = $plugin_folder;
		}

		$locale = apply_filters( 'plugin_locale', get_locale(), $text_domain );

		if ( is_admin() ) {
			load_textdomain( $text_domain, WP_LANG_DIR . "/{$plugin_folder}/{$plugin_folder}-admin-{$locale}.mo" );
			load_textdomain( $text_domain, WP_LANG_DIR . "/plugins/{$plugin_folder}-admin-{$locale}.mo" );
		}

		load_textdomain( $text_domain, WP_LANG_DIR . "/{$plugin_folder}/{$plugin_folder}-{$locale}.mo" );

		$mo = WP_CONTENT_DIR . "/plugins/{$plugin_folder}/languages/{$plugin_folder}-{$locale}.mo";
		load_textdomain( $text_domain, $mo );
		load_plugin_textdomain( $text_domain, false, plugin_basename( $path ) . "/languages" );

	}
}

function learn_press_certificates_get_socials() {
	$all     = LP()->settings->get( 'cert_share' );
	$socials = array();
	if ( $all ) {
		foreach ( $all as $social => $enable ) {
			if ( $enable === 'yes' ) {
				$socials[] = $social;
			}
		}
	}
	return $socials;
}

function learn_press_share_certificate_button( $social, $args ) {
	switch ( $social ) {
		case 'twitter':
			?>
			<a href="http://twitter.com/share" data-count="none" class="twitter-share-button" lang="en" data-url="<?php echo esc_url( $args['url'] ); ?>" data-text="<?php echo esc_attr( $args['text'] ); ?>"></a>
			<script type="text/javascript">_load_js_url('https://platform.twitter.com/widgets.js')</script>
			<?php
			break;
		case
		'facebook':
			?>
			<div class="fb-like" href="<?php echo esc_url( $args['url'] ); ?>" width="180" send="false" showfaces="false" action="like" data-share="true" data-layout="button"></div>
			<script type="text/javascript">_load_js_url('//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5')</script>
			<?php
			break;
		case 'plusone':
			?>
			<div class="g-plusone" data-href="<?php echo esc_url( $args['url'] ); ?>" data-size="medium" data-annotation="none">
			</div>
			<script type="text/javascript">_load_js_url('https://apis.google.com/js/plusone.js')</script>
			<?php
			break;
		default:
			echo '<a href="' . esc_url( $args['url'] ) . '">' . $social . '</a>';
	}
}

add_action( 'learn_press_share_certificate_button', 'learn_press_share_certificate_button', 10, 2 );
