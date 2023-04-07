<?php
/**
 * Hook registry
 *
 * @category   Components
 * @package    seo-dash
 * @author     Bogere Goldsoft
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       tupimelab.com
 * @since      1.0.0
 */

namespace Seo\Dash;

use \WP_REST_RESPONSE;
use \WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * General hook registry
 */
class Hook_Registry {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add all hooks
	 */
	private function add_hooks() {

		// Enqueue Styles and Scripts.

		add_action( 'admin_enqueue_scripts', [ $this, 'seo_dash_load_custom_scripts' ] );

		add_action( 'wp_dashboard_setup', [ $this, 'seo_dash_add_dashboard_widget' ] );

		add_action( 'rest_api_init', [ $this, 'seo_dash_handle_custom_rest_api' ] );

		
		register_activation_hook( SEO_DASH_PLUGIN_FILE, [ $this, 'after_plugin_is_activated' ] );

		register_deactivation_hook( SEO_DASH_PLUGIN_FILE, [ $this, 'after_deactivate_plugin' ] );
	}

	public function seo_dash_load_custom_scripts() {

		wp_enqueue_style( 'seo-dash-style', SEO_DASH_PLUGIN_URL . 'build/index.css' );
		wp_enqueue_script( 'seo-dash-script', SEO_DASH_PLUGIN_URL . 'build/index.js', array( 'wp-element' ), '1.0.0', true );

		wp_localize_script(
			'seo-dash-script',
			'siteData',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'data_var_1' => 'value 1',
				'data_var_2' => 'value 2',
			)
		);
	}

	
	/**
	 * Perform the actions after activating the plugin
	 *
	 */
	public function after_plugin_is_activated() {

		// register the config options for codeable site.
		update_option( SEO_DASH_OPTIONS_KEY, 'no-expired-sites' );

		$this->seo_dash_create_graph_tables();

	}

	/**
	 * Perform the actions after deactivating the plugin
	 *
	 */
	public function after_deactivate_plugin() {
		echo "I have deactivated the plugin";
	}


    /**
	 * Add the admin dashboard widget 
	 */
	public function seo_dash_add_dashboard_widget() {
		wp_add_dashboard_widget(
			'dashboard_widget',
			'Rechart Dashboard Widget',
			[ $this, 'seo_dash_render_dashboard_widget' ]
		);
	}

	/**
	 * Render the page for the admin dashboard widget
	*/
	public function seo_dash_render_dashboard_widget() {
		require_once SEO_DASH_DIR . '/templates/app.php';
	}

	public function seo_dash_create_graph_tables() {

		$class_db = new ClassTable();
		$class_db->seo_dash_create_graph_table();
		$class_db->seo_dash_insert_sample_graph_data();
	}

	/**
	 * Custom REST API handler for the graph data.
	 */
	public function seo_dash_handle_custom_rest_api() {
		// Declare our namespace 
		$namespace = 'myrest/v1';

		register_rest_route( $namespace, '/performance', array(
			'methods' => 'GET', 
			'callback' => [$this, 'seo_dash_get_performance_data'],
			// 'permission_callback' => '__return_true',
		  ) 
	    );

	}


	/**
	 * Get a performance data for the graphs
	 *
	 * @since 0.0.1
	 *
	 * @param \WP_REST_Request $request Full details about the request
	 *
	 * @return \WP_HTTP_Response
	 */

	public function seo_dash_get_performance_data( \WP_REST_Request $request){
		$search_query = $request->get_param( 'filter_value' );

		if (!is_numeric($search_query)) {
			return new \WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed input value, search value must be a number'
				),
				200
			);
		}
        
		$results = ClassTable::seo_dash_fetch_data_from_db( $search_query );

        return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => $results
			),
			200
		);

	}
}

new Hook_Registry();
