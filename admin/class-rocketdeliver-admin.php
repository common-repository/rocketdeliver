<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rocketdeliver.in
 * @since      1.0.0
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Forked from Cloudimage WP Plugin
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/admin
 * @author     RocketDeliver <hello@rocketdeliver.in>
 */
class Rocketdeliver_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Is Dev env.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $is_dev
     */
    private $is_dev;

    /**
     * Initialize the class and set its properties.
     * Forked from Cloudimage WP Plugin
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param bool $is_dev Check if environnement is local or not
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version, $is_dev = false)
    {

        $this->is_dev = $is_dev;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->rocketdeliver_options = get_option($this->plugin_name);

    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rocketdeliver-admin.css', array(), $this->version, 'all');

    }


    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        return null;
    }


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_menu_page(
            'Welcome to the RocketDeliver WordPress Plugin',
            'RocketDeliver',
            'manage_options',
            $this->plugin_name, array($this, 'display_plugin_setup_page'),
            plugin_dir_url(__FILE__) . '../admin/images/logo_19.png'
        );
    }


    /**
     * Add settings action link to the plugins page.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', 'rocketdeliver') . '</a>',
        );

        return array_merge($settings_link, $links);
    }


    /**
     * Render the settings page for this plugin.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page()
    {
        include_once('partials/rocketdeliver-admin-display.php');
    }


    /**
     * Validate data from admin
     * Modified to remove options for blurhash, lazy loading and reponsive js
     *
     * @version  2.0.5
     * @since    1.0.0
     */
    public function validate($input)
    {

        // All checkboxes inputs
        $valid = array();

        //Cleanup
        if (!empty($input['domain']) && strpos($input['domain'], '.') === false) {
            $valid['domain'] = $valid['rocketdeliver_domain'] = 'rocketdeliver.in/images/source='; //$input['domain'] . '.rocketdeliver.in';
        } else {
            $valid['domain'] = $valid['rocketdeliver_domain'] = 'rocketdeliver.in/images/source='; // $input['domain'];
        }

        return $valid;
    }


    /**
     * Register option once they are updated
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }


    /**
     * Add notice if domain is not set
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function rocketdeliver_admin_notice_no_domain()
    {
        $class = 'notice notice-warning';
        $message = __('To get started, please create and account at https://rocketdeliver.in', 'rocketdeliver');

    }


    /**
     * Add notice if we are on Localhost
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     */
    public function rocketdeliver_admin_notice_localhost()
    {
        $class = 'notice notice-warning';
        $message = __('RocketDeliver has been disabled because your are running on LocalHost. RocketDeliver needs accessible URL to work', 'rocketdeliver');

        if ($this->is_dev) {
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }
    }


}
