<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              aleksanderciesla.pl
 * @since             1.0.0
 * @package           Todolist
 *
 * @wordpress-plugin
 * Plugin Name:       ToDoList
 * Plugin URI:        https://github.com/olekk/todolist-wp-plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Aleksander
 * Author URI:        aleksanderciesla.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       todolist
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!defined("TODOLIST_PLUGIN_DIR")) define("TODOLIST_PLUGIN_DIR", plugin_dir_path(__FILE__));
if(!defined("TODOLIST_PLUGIN_URL")) define("TODOLIST_PLUGIN_URL", plugins_url()."/todolist");

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TODOLIST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-todolist-activator.php
 */
function activate_todolist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todolist-tables.php';
	$tables = new Todolist_Tables();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todolist-activator.php';
	$activator = new Todolist_Activator($tables);
	$activator->activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-todolist-deactivator.php
 */
function deactivate_todolist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todolist-tables.php';
	$tables = new Todolist_Tables();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todolist-deactivator.php';
	$deactivator = new Todolist_Deactivator($tables);
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_todolist' );
register_deactivation_hook( __FILE__, 'deactivate_todolist' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-todolist.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_todolist() {

	$plugin = new Todolist();
	$plugin->run();

}
run_todolist();
