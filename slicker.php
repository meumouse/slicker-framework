<?php
/**
 * Plugin Name:     Slicker Framework
 * Plugin URI:      https://meumouse.com/
 * Description:     Framework for developing WordPress plugins.
 * Author:          MeuMouse.com
 * Author URI:      https://meumouse.com/
 * Version:         1.0.0
 * Text Domain:     slicker-framework
 * Domain Path:     /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define SLICKER_PLUGIN_FILE
if ( ! defined( 'SLICKER_PLUGIN_FILE' ) ) {
	define( 'SLICKER_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Slicker_Framework' ) ) {

	/**
	 * Main Slicker_Framework Class
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 * @package MeuMouse.com
	 */
	final class Slicker_Framework {

		/**
		 * Slicker_Framework The single instance of Slicker_Framework
		 *
		 * @var object
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * The token
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $token;

		/**
		 * The version number
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version;

		/**
		 * Constructor function
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			$this->token = 'slicker-framework';
			$this->version = '1.0.0';

			add_action( 'plugins_loaded', array( $this, 'setup_constants' ), 10 );
			add_action( 'plugins_loaded', array( $this, 'includes' ), 20 );
		}

		/**
		 * Main Slicker_Framework Instance
		 *
		 * Ensures only one instance of Slicker_Framework is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see Slicker_Framework()
		 * @return Main Slicker instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function setup_constants() {

			// Plugin Folder Path.
			if ( ! defined( 'SLICKER_FRAMEWORK_DIR' ) ) {
				define( 'SLICKER_FRAMEWORK_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'SLICKER_FRAMEWORK_URL' ) ) {
				define( 'SLICKER_FRAMEWORK_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'SLICKER_FRAMEWORK_FILE' ) ) {
				define( 'SLICKER_FRAMEWORK_FILE', __FILE__ );
			}

			// Modules File.
			if ( ! defined( 'SLICKER_FRAMEWORK_MODULES_DIR' ) ) {
				define( 'SLICKER_FRAMEWORK_MODULES_DIR', SLICKER_FRAMEWORK_DIR . '/modules' );
			}

			$this->define( 'SLICKER_ABSPATH', dirname( SLICKER_FRAMEWORK_FILE ) . '/' );
			$this->define( 'SLICKER_VERSION', $this->version );
		}

		/**
		 * Define constant if not already set
		 *
		 * @param string $name  Constant name
		 * @param string|bool $value Constant value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 *
		 * @param string $type admin, ajax or cron.
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required files
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function includes() {
			/**
			 * Class autoloader
			 * 
			 * @since 1.0.0
			 */
			include_once SLICKER_FRAMEWORK_DIR . '/includes/class-slicker-autoloader.php';

			/**
			 * General functions
			 * 
			 * @since 1.0.0
			 */
			include_once SLICKER_FRAMEWORK_DIR . '/includes/slicker-functions.php';

			/**
			 * Class admin functions
			 * 
			 * @since 1.0.0
			 */
			if ( $this->is_request( 'admin' ) ) {
				include_once SLICKER_FRAMEWORK_DIR . '/includes/admin/class-slicker-framework-admin.php';
			}
			
			// Include files of project here
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', SLICKER_PLUGIN_FILE ) );
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Trapaceando?', 'slicker-framework' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Trapaceando?', 'slicker-framework' ), '1.0.0' );
		}
	}
}

/**
 * Returns the main instance of Slicker_Framework to prevent the need to use globals.
 *
 * @since 1.0.0
 * @return object Slicker_Framework
 */
function Slicker_Framework() { //phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return Slicker_Framework::instance();
}

/**
 * Initialise the plugin
 */
Slicker_Framework();