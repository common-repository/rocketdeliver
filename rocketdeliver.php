<?php

/**
 * RocketDeliver - Delivering your website faster
 * Forked from Cloudimage WP Plugin
 *
 *
 * @link              https://rocketdeliver.in
 * @since             1.0.0
 * @package           RocketDeliver
 *
 * @wordpress-plugin
 * Plugin Name:       RocketDeliver - Delivering your website faster
 * Description:       The easiest way to <strong>speed up</strong> your website.  RocketDeliver uses the best of compression and web technologies to ensure a fast website for every user no matter the device they use. This plugin reroutes your asset (images, css and js) urls through the <a href="https://rocketdeliver.in">RocketDeliver</a> service. No tracking or Js or intensive computations run on your server or the users' devices. 
 * Version:           1.0.0
 * Author:            RocketDeliver
 * Author URI:        https://rocketdeliver.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rocketdeliver
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Forked from Cloudimage WP Plugin
 */
define( 'ROCKETDELIVER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rocketdeliver-activator.php
 * Forked from Cloudimage WP Plugin
 */
function activate_rocketdeliver() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rocketdeliver-activator.php';
	Rocketdeliver_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rocketdeliver-deactivator.php
 * Forked from Cloudimage WP Plugin
 */
function deactivate_rocketdeliver() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rocketdeliver-deactivator.php';
	Rocketdeliver_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rocketdeliver' );
register_deactivation_hook( __FILE__, 'deactivate_rocketdeliver' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 * Forked from Cloudimage WP Plugin
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rocketdeliver.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 * Forked from Cloudimage WP Plugin
 *
 * @since    1.0.0
 */
function run_rocketdeliver() {

	$plugin = new Rocketdeliver();
	$plugin->run();

}
run_rocketdeliver();
