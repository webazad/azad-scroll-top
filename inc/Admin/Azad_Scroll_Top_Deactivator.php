<?php
namespace Inc\Admin;
// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;

class Azad_Scroll_Top_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function Deactivate() {

		delete_option( 'ultimate_blocks' );

	}

}