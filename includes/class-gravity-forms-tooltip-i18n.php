<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.facebook.com/disismehbub
 * @since      1.0.0
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/includes
 * @author     Mehbub Rashid <rashidiam1998@gmail.com>
 */
class Gravity_Forms_Tooltip_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'gravity-forms-tooltip',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
