<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THIM_CHILD_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );

/**
 * Generate param type "number"
 *
 * @param $settings
 * @param $value
 *
 * @return string
 */

function thim_sc_number_settings_field( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$min        = isset( $settings['min'] ) ? $settings['min'] : '';
	$max        = isset( $settings['max'] ) ? $settings['max'] : '';
	$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<input type="number" min="' . $min . '" max="' . $max . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" style="max-width:100px; margin-right: 10px;" />' . $suffix;

	return $output;
}

vc_add_shortcode_param( 'number', 'thim_sc_number_settings_field' );

function thim_vc_dropdown_multiple_form_field( $settings, $value ) {
	$output     = '';
	$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );
	$output     .= '<select name="'
	               . $settings['param_name']
	               . '" class="wpb_vc_param_value wpb-input wpb-select '
	               . $settings['param_name']
	               . ' ' . $settings['type']
	               . ' ' . $css_option
	               . '" multiple data-option="' . $css_option . '">';
	if ( is_array( $value ) ) {
		$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
	}
	if ( ! empty( $settings['value'] ) ) {
		foreach ( $settings['value'] as $index => $data ) {
			if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
				$option_label = $data;
				$option_value = $data;
			} elseif ( is_numeric( $index ) && is_array( $data ) ) {
				$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
				$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
			} else {
				$option_value = $data;
				$option_label = $index;
			}
			$selected            = '';
			$option_value_string = (string) $option_value;
			$value_string        = (string) $value;
			if ( '' !== $value && $option_value_string === $value_string ) {
				$selected = ' selected="selected"';
			}
			$option_class = str_replace( '#', 'hash-', $option_value );
			$output       .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '"' . $selected . '>'
			                 . htmlspecialchars( $option_label ) . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}

vc_add_shortcode_param( 'dropdown_multiple', 'thim_vc_dropdown_multiple_form_field' );

/**
 * Get post categories array
 *
 * @return array
 */
function thim_sc_get_categories() {
	$args                                  = array(
		'orderby' => 'id',
		'parent'  => 0
	);
	$items                                 = array();
	$items[ esc_html__( 'All', 'eduma' ) ] = 'all';
	$categories                            = get_categories( $args );
	if ( isset( $categories ) ) {
		foreach ( $categories as $key => $cat ) {
			$items[ $cat->cat_name ] = $cat->cat_ID;
			$childrens               = get_term_children( $cat->term_id, $cat->taxonomy );
			if ( $childrens ) {
				foreach ( $childrens as $key => $children ) {
					$child                        = get_term_by( 'id', $children, $cat->taxonomy );
					$items[ '--' . $child->name ] = $child->term_id;

				}
			}
		}
	}

	return $items;
}

function thim_sc_get_list_image_size() {
	global $_wp_additional_image_sizes;

	$sizes                        = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach ( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
			);

		}

	}

	$image_size                                          = array();
	$image_size[ esc_html__( "No Image", 'eduma' ) ]     = 'none';
	$image_size[ esc_html__( "Custom Image", 'eduma' ) ] = 'custom_image';
	if ( ! empty( $sizes ) ) {
		foreach ( $sizes as $key => $value ) {
			if ( $value['width'] && $value['height'] ) {
				$image_size[ $value['width'] . 'x' . $value['height'] ] = $key;
			} else {
				$image_size[ $key ] = $key;
			}
		}
	}

	return $image_size;
}

/**
 * Custom excerpt
 *
 * @param $length
 *
 * @return string
 */
function thim_sc_get_the_excerpt( $length ) {
	$excerpt = get_the_excerpt();

	if ( ! $excerpt ) {
		$excerpt = __( 'Sometimes, a picture is worth a thousand words.', 'eduma' );
	} else {
		$words   = explode( ' ', $excerpt );
		$excerpt = '';

		foreach ( $words as $word ) {
			if ( strlen( $excerpt ) < $length ) {
				$excerpt .= $word . ' ';
			} else {
				break;
			}
		}
	}

	return $excerpt . '...';
}


function thim_sc_get_course_categories( $cats = false ) {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
		'course_category', 0
	) );

	if ( ! $cats ) {
		$cats = array();
	}
	if ( ! empty( $query ) ) {
		foreach ( $query as $key => $value ) {
			$cats[ $value->name ] = $value->term_id;
		}
	}

	return $cats;
}

// Get list category our team
function thim_sc_get_team_categories() {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
		'our_team_category', 0
	) );

	$cats        = array();
	$cats['All'] = esc_html__( 'all', 'eduma' );
	if ( ! empty( $query ) ) {
		foreach ( $query as $key => $value ) {
			$cats[ $value->name ] = $value->term_id;
		}
	}

	return $cats;
}

function thim_sc_get_portfolio_categories() {
	$portfolio_category = get_terms( 'portfolio_category', array(
		'hide_empty' => 0,
		'orderby'    => 'ASC',
		'parent'     => 0
	) );

	$cate                                 = array();
	$cate[ esc_html__( 'All', 'eduma' ) ] = '';
	if ( is_array( $portfolio_category ) ) {
		foreach ( $portfolio_category as $cat ) {
			$cate[ $cat->name ] = $cat->term_id;
		}
	}

	return $cate;
}

function thim_sc_get_categories_autocomplete() {
	$args       = array(
		'orderby' => 'id',
		'parent'  => 0
	);
	$items      = array();
	$categories = get_categories( $args );
	if ( isset( $categories ) ) {
		foreach ( $categories as $key => $cat ) {
			$items[]   = array(
				'value' => $cat->cat_ID,
				'label' => $cat->cat_name
			);
			$childrens = get_term_children( $cat->term_id, $cat->taxonomy );
			if ( $childrens ) {
				foreach ( $childrens as $key => $children ) {
					$child   = get_term_by( 'id', $children, $cat->taxonomy );
					$items[] = array(
						'value' => $child->term_id,
						'label' => '--' . $child->name
					);
				}
			}
		}
	}

	return $items;
}