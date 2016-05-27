<?php
/*
 * Plugin Name: UBB Book Setting Add-On
 * Plugin URI: https://www.nosegraze.com/create-custom-ubb-plugin-field/
 * Description: Adds a new field for the book setting.
 * Version: 1.0
 * Author: Nose Graze
 * Author URI: https://www.nosegraze.com
 * License: GPL2
 * 
 * @package ubb-book-setting
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license GPL2+
*/

/**
 * Book Info Configuration
 *
 * Adds a new entry to the book info configuration for book setting.
 *
 * @param array $fields
 *
 * @return array
 */
function ubb_setting_config_option( $fields ) {
	// Change 'book_setting' to whatever you want. No spaces!
	$fields['book-info']['fields']['ubb_sorter']['std']['disabled']['book_setting'] = array(
		'name'  => esc_html__( 'Book Setting' ), // Title of the field
		'desc'  => __( 'Use <code>[setting]</code> to display the book setting.' ), // Description and instructions
		'label' => '<strong>Setting:</strong> [setting] <br>' // Default label (this can be edited in settings later)
	);

	return $fields;
}

add_filter( 'ubb_fields', 'ubb_setting_config_option' );

/**
 * Add Meta Field
 *
 * @param string                      $key          Custom field key name
 * @param object                      $ubb_boxgroup Meta box group object
 * @param int|null                    $group_id     Group ID number
 * @param Ultimate_Book_Blogger_Admin $ubb_admin    UBB admin object
 *
 * @return void
 */
function ubb_setting_meta_field( $key, $ubb_boxgroup, $group_id, $ubb_admin ) {
	if ( $key == 'book_setting' ) {
		$args = array(
			'name' => esc_html__( 'Book Setting' ),
			'id'   => $ubb_admin->prefix . 'book_setting',
			'type' => 'text_medium',
			// Other choices include: text, select, textarea, checkbox - see https://github.com/WebDevStudios/CMB2/wiki/Field-Types
		);
		do_action( 'ubb_add_metabox', $ubb_boxgroup, $args, $group_id );
	}
}

add_action( 'ubb_display_meta_boxes', 'ubb_setting_meta_field', 10, 4 );

/**
 * Setting Shortcode
 *
 * Register the [setting] shortcode with UBB and map it to our book_setting key.
 *
 * @param array $shortcodes
 *
 * @return array
 */
function ubb_setting_shortcode( $shortcodes = array() ) {
	$shortcodes['book_setting'] = '[setting]';

	return $shortcodes;
}

add_filter( 'ubb_find_shortcodes', 'ubb_setting_shortcode' );

/**
 * Get Value
 *
 * Gets the value of our custom field.
 *
 * @param string   $value  Existing value
 * @param string   $key    Custom field key
 * @param bool     $linked Whether or not the parameter should be linked (used with taxonomies)
 * @param UBB_Book $book   Book object
 *
 * @return string
 */
function ubb_setting_get_value( $value, $key, $linked, $book ) {
	if ( $key != 'book_setting' ) {
		return $value;
	}

	return $book->get_meta_value( 'book_setting' );
}

add_filter( 'ubb/book/get_value', 'ubb_setting_get_value', 10, 4 );
