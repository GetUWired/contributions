<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.getuwired.com/
 * @since             1.0.0
 * @package           Page_Visibility
 *
 * @wordpress-plugin
 * Plugin Name:       Page Visibility
 * Plugin URI:        https://www.getuwired.com/
 * Description:       Extends WordPress' default page visibility options to include an option to only show pages if the current user is logged in
 * Version:           1.0.0
 * Author:            Cody Brant (GetUWired)
 * Author URI:        https://www.getuwired.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-visibility
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
define( 'PAGE_VISIBILITY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-page-visibility-activator.php
 */
function activate_page_visibility() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-visibility-activator.php';
	Page_Visibility_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-page-visibility-deactivator.php
 */
function deactivate_page_visibility() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-visibility-deactivator.php';
	Page_Visibility_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_page_visibility' );
register_deactivation_hook( __FILE__, 'deactivate_page_visibility' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-page-visibility.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_page_visibility() {

	$plugin = new Page_Visibility();
	$plugin->run();

}
run_page_visibility();
