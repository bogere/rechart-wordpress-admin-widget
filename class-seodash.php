<?php
/**
 * Plugin Name:       Seo Dash Widget
 * Plugin URI:        https://www.kazilab.com/
 * Description:       PLugin to show the chart graph as dashboard widget
 * Version:           1.0.0
 * Author:            Bogere Goldsoft
 * Author URI:        https://github.com/bogere
 * Requires PHP:      5.4
 * Text Domain:       seo-dash
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 *
 * @package seo-dash
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // deny direct access to the plugin file by hackers.
}

if ( ! class_exists( 'SeoDash' ) ) {

	/**
	 * Main School Pay class
	 */
	final class SeoDash {



		/**
		 * Reference to plugin version
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * Plugin options key to store and retrieve settings in WordPress database
		 *
		 * @var string
		 */
		private $options_key = 'seo-dash';

		/**
		 * Variable that holds the one and only instance of M-Alkhair
		 *
		 * @var SeoDash
		 */
		private static $seo_instance = null;

		/**
		 * A dependency injection container
		 *
		 * @var Object
		 */
		public $container = null;

		/**
		 * A MonoLog log object
		 *
		 * @var Object
		 */
		public $logger = null;

		/**
		 * Load the global M-Alkhair instance
		 *
		 * @return SeoDash
		 */
		public static function get_instance() {
			if ( is_null( self::$seo_instance ) ) {
				self::$seo_instance = new self();
			}
			return self::$seo_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.8.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'seo-dash' ), esc_html( $this->version ) );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.8.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'seo-dash' ), esc_html( $this->version ) );
		}

		/**
		 * Class constructor
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
		}

		/**
		 * Papa Site constants
		 */
		private function define_constants() {
			$this->define( 'SEO_DASH_VERSION', $this->version );
			$this->define( 'SEO_DASH_SLUG', 'seo-dash' );
			$this->define( 'SEO_DASH_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'SEO_DASH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'SEO_DASH_PLUGIN_FILE', __FILE__ );
			$this->define( 'SEO_DASH_OPTIONS_KEY', $this->options_key );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name variable.
		 * @param string|bool $value variable.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Include required files
		 */
		private function includes() {
			require_once SEO_DASH_DIR . '/includes/classes/class-classtable.php';
			require_once SEO_DASH_DIR . '/includes/class-hook-registry.php';
		}
	}

	/**
	 * Make Seo Dash class instance available globally
	 */
	function seo_dash() {
		return SeoDash::get_instance();
	}

	seo_dash();
}
