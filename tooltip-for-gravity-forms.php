<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.facebook.com/disismehbub
 * @since             1.0.0
 * @package           Gravity_Forms_Tooltip
 *
 * @wordpress-plugin
 * Plugin Name:       Tooltip for Gravity Forms
 * Description:       Add Tooltips next to field labels of Gravity Forms.
 * Version:           2.8
 * Author:            Mehbub Rashid
 * Author URI:        https://www.facebook.com/disismehbub
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tooltip-for-gravity-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GRAVITY_FORMS_TOOLTIP_VERSION', '2.8' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gravity-forms-tooltip-activator.php
 */
function activate_gravity_forms_tooltip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip-activator.php';

	/* Set transient if gravity forms plugin is not active*/
	add_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
	Gravity_Forms_Tooltip_Activator::activate();

	if(!isset(get_option('gravity_tooltip_options')['allow_update'])) {
		$toset = array(
			'allow_update' => '1'
		);
		update_option('gravity_tooltip_options', $toset);
	}
}


/* Deactivate this plugin when admin deactivates gravity forms plugin */
function detect_plugin_deactivation( $plugin, $network_activation ) {
    if ($plugin=="gravityforms/gravityforms.php")
    {
        set_transient( 'gravitychecker', true, 5 );
    }
}
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gravity-forms-tooltip-deactivator.php
 */
function deactivate_gravity_forms_tooltip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip-deactivator.php';
	Gravity_Forms_Tooltip_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gravity_forms_tooltip' );
register_deactivation_hook( __FILE__, 'deactivate_gravity_forms_tooltip' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gravity-forms-tooltip.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gravity_forms_tooltip() {

	$plugin = new Gravity_Forms_Tooltip();
	$plugin->run();

}
run_gravity_forms_tooltip();
