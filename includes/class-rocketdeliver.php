<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rocketdeliver.in
 * @since      1.0.0
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    RocketDeliver
 * @subpackage RocketDeliver/includes
 * @author     RocketDeliver <hello@rocketdeliver.in>
 */
class Rocketdeliver
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Rocketloader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * The current option of the plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array $rocketdeliver_options
     */
    private $rocketdeliver_options;

    /**
     * Define the core functionality of the plugin.
     * Forked from Cloudimage WP Plugin
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        if (defined('ROCKETDELIVER_VERSION')) {
            $this->version = ROCKETDELIVER_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'rocketdeliver';
        $this->rocketdeliver_options = get_option($this->plugin_name);

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Rocket_Loader. Orchestrates the hooks of the plugin.
     * - Rocketdeliver_i18n. Defines internationalization functionality.
     * - Rocketdeliver_Admin. Defines all hooks for the admin area.
     * - RocketDeliver_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rocketdeliver-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rocketdeliver-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-rocketdeliver-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-rocketdeliver-public.php';


        $this->loader = new Rocket_Loader();

    }


    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Rocketdeliver_i18n class in order to set the domain and to register the hook
     * with WordPress.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Rocketdeliver_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     * Forked from Cloudimage WP Plugin
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Rocketdeliver_Admin($this->get_plugin_name(), $this->get_version(), $this->is_development_mode());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

        // Save/Update our plugin options
        $this->loader->add_action('admin_init', $plugin_admin, 'options_update');

        // Add a notice if domain is null
        $this->loader->add_action('admin_notices', $plugin_admin, 'rocketdeliver_admin_notice_no_domain');

        // Add a notice if LocalHost
        $this->loader->add_action('admin_notices', $plugin_admin, 'rocketdeliver_admin_notice_localhost');

    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     * Modified to remove the blurhash, lazy loading and responsive js 
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new RocketDeliver_Public($this->get_plugin_name(), $this->get_version(), $this->is_development_mode());

        //$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        //$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

	$this->loader->add_filter('style_loader_tag', $plugin_public, 'filter_rocket_style', 1, 4);
	$this->loader->add_filter('script_loader_tag', $plugin_public, 'filter_rocket_script', 1, 3);
	//$this->loader->add_filter('wp_get_attachment_url', $plugin_public, 'filter_rocket_image', 1, 2);
	/*
        $this->loader->add_filter(
            'wp_get_attachment_url', $plugin_public, 'filter_rocketdeliver_wp_get_attachment_url',
            0, 2
        );
	 */
        $this->loader->add_filter(
            'wp_calculate_image_srcset', $plugin_public, 'filter_rocketdeliver_wp_calculate_image_srcset',
            0, 5
        );
	/*	
        $this->loader->add_filter(
            'image_downsize', $plugin_public, 'filter_rocketdeliver_image_downsize', 
            0, 3
        );
        $this->loader->add_filter(
            'the_content', $plugin_public, 'filter_rocketdeliver_the_content',
            0, 1
        );
	 */
    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
        do_action('rocketdeliver_setup');

    }


     /**
     * Is rocketdeliver in development (offline) mode?
     * Forked from Cloudimage WP Plugin
     *
     * @return bool $is_dev - return a value to decide if we are on dev
     * 
     * @since    1.0.0
     */
    private function is_development_mode()
    {
        $development_mode = false;


        if (isset($this->rocketdeliver_options['dev']) && $this->rocketdeliver_options['dev'] === 1) {
            $development_mode = true;
        } elseif (isset($this->rocketdeliver_options['prod']) && $this->rocketdeliver_options['prod'] === 1) {
            $development_mode = false;
        } elseif ($site_url = site_url()) {
            $development_mode = (false === strpos($site_url, '.')) || (stripos($site_url, 'local') !== false);
        }

        return $development_mode;
    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }


    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    RocketDeliver_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }


    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }


}
