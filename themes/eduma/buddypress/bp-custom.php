<?php
$avt_thumb = (empty(get_theme_mod('thim_size_thumb_avatar')) || get_theme_mod('thim_size_thumb_avatar')=='') ? 70 : get_theme_mod('thim_size_thumb_avatar');
$avt_full = (empty(get_theme_mod('thim_size_full_avatar')) || get_theme_mod('thim_size_full_avatar')=='') ? 150 : get_theme_mod('thim_size_full_avatar');
define ( 'BP_AVATAR_THUMB_WIDTH', $avt_thumb );
define ( 'BP_AVATAR_THUMB_HEIGHT', $avt_thumb );
define ( 'BP_AVATAR_FULL_WIDTH', $avt_full );
define ( 'BP_AVATAR_FULL_HEIGHT', $avt_full );

function thim_buddypress_search_form() {
	$query_arg = bp_core_get_component_search_query_arg( 'members' );

	if ( ! empty( $_REQUEST[ $query_arg ] ) ) {
		$search_value = stripslashes( $_REQUEST[ $query_arg ] );
	} else {
		$search_value = bp_get_search_default_text( 'members' );
	}

	$search_form_html = '<form action="" method="get" id="search-members-form">
		<input type="text" name="' . esc_attr( $query_arg ) . '" id="members_search" placeholder="'. esc_attr( $search_value ) .'" />
		<input type="submit" id="members_search_submit" name="members_search_submit" value="' . esc_attr__( 'Search', 'eduma' ) . '" />
	</form>';

	return $search_form_html;
}
add_filter( 'bp_directory_members_search_form', 'thim_buddypress_search_form' );
