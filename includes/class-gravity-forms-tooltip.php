<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.facebook.com/disismehbub
 * @since      1.0.0
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/includes
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
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/includes
 * @author     Mehbub Rashid <rashidiam1998@gmail.com>
 */
class Gravity_Forms_Tooltip {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Gravity_Forms_Tooltip_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	protected $plugin_public;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GRAVITY_FORMS_TOOLTIP_VERSION' ) ) {
			$this->version = GRAVITY_FORMS_TOOLTIP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'gravity-forms-tooltip';

		$this->load_dependencies();
		$this->set_locale();


		$this->plugin_public = new Gravity_Forms_Tooltip_Public($this->get_plugin_name(), $this->get_version());

		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gravity_Forms_Tooltip_Loader. Orchestrates the hooks of the plugin.
	 * - Gravity_Forms_Tooltip_i18n. Defines internationalization functionality.
	 * - Gravity_Forms_Tooltip_Admin. Defines all hooks for the admin area.
	 * - Gravity_Forms_Tooltip_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gravity-forms-tooltip-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gravity-forms-tooltip-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gravity-forms-tooltip-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-gravity-forms-tooltip-public.php';

		$this->loader = new Gravity_Forms_Tooltip_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gravity_Forms_Tooltip_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Gravity_Forms_Tooltip_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Gravity_Forms_Tooltip_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'gform_field_standard_settings', $plugin_admin, 'tooltip_input', 10, 2 );
		$this->loader->add_action( 'gform_editor_js', $plugin_admin, 'tooltip_editor_script' );

		$this->loader->add_filter( 'gform_field_content', $plugin_admin, 'render_tooltips', 10, 5);

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'tooltip_update_checker' );

		$this->loader->add_action( 'in_plugin_update_message-tooltip-for-gravity-forms/tooltip-for-gravity-forms.php', $plugin_admin, 'set_updater_transient', 10, 2 );

		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'detect_plugin_update');
		
		
		$this->loader->add_filter( 'auto_update_plugin', $plugin_admin, 'auto_update_this_plugin', 10, 2 );


		// if current page is gravity forms edit form page, enqueue scripts needed for tooltip
		// so that we can render tooltip in edit and preview mode
		// if ( isset( $_GET['page'] ) && $_GET['page'] == 'gf_edit_forms' ) {
		// 	$plugin_public = $this->plugin_public;
		// 	$this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_styles');
		// 	$this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		// }

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = $this->plugin_public;

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Gravity_Forms_Tooltip_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
