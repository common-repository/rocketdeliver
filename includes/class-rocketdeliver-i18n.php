<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://rocketdeliver.in
 * @since      1.0.0
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/includes
 */

/**
 * Define the internationalization functionality.
 * Forked from Cloudimage WP Plugin
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    RocketDeliver
 * @subpackage RocketDeliver/includes
 * @author     RocketDeliver <hello@rocketdeliver.in>
 */
class Rocketdeliver_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rocketdeliver',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
