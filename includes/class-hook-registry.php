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

        //Enqueue Styles and Scripts

        add_action( 'admin_enqueue_scripts', [ $this, 'seo_dash_load_custom_scripts']);

        add_action( 'wp_dashboard_setup',  [ $this, 'seo_dash_add_dashboard_widget'] );

        add_action('rest_api_init', [$this, 'seo_dash_handle_custom_rest_api']);
        
        //what about the filters
        //add_filter('cron_schedules'. [$this, 'sch_add_custom_cron_interval']);
         //dealing with plugin activation
        register_activation_hook(SEO_DASH_PLUGIN_FILE, [$this, 'after_plugin_is_activated']);
         //Actions.

        register_deactivation_hook(SEO_DASH_PLUGIN_FILE, [$this, 'after_deactivate_plugin']); 
    }


    public function seo_dash_load_custom_scripts (){

        wp_enqueue_style( 'seo-dash-style', SEO_DASH_PLUGIN_URL . 'build/index.css' );
        wp_enqueue_script( 'seo-dash-script', SEO_DASH_PLUGIN_URL . 'build/index.js', array( 'wp-element' ), '1.0.0', true );

        wp_localize_script( 'seo-dash-script', 'siteData',
             array( 
             'ajaxurl' => admin_url( 'admin-ajax.php' ),
             'data_var_1' => 'value 1',
             'data_var_2' => 'value 2',
        )
       );
    }



     /**
      *  schedule the job after the activating the plugin
      */
    public function after_plugin_is_activated(){
            
        //register the config options for codeable site
        update_option(SEO_DASH_OPTIONS_KEY, 'no-expired-sites' );  
             
    
        $this->seo_dash_create_graph_table();

    }

    /*
    * Deactivate the plugin 
    */
    public function after_deactivate_plugin(){
        error_log("yeah i have been deactivated...");
        //wp_clear_scheduled_hook('hourly_synchronise_hook');
    }


    public function seo_dash_add_dashboard_widget() {
        wp_add_dashboard_widget(
            'dashboard_widget',
            'Rechart Hello Dashboard Widget',
            [$this, 'seo_dash_render_dashboard_widget']
        );
    }
    
    public function seo_dash_render_dashboard_widget() {
        require_once SEO_DASH_DIR . '/templates/app.php';
    }
    

    public function seo_dash_generate_random_data(){
        error_log("Please randomly generate chart data for dashboard widget");
    }


    public function seo_dash_create_graph_table(){
        global $wpdb;
        $graph_table = $wpdb->prefix . "graph_table";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $graph_table (
               id mediumint(9) NOT NULL AUTO_INCREMENT,
               name varchar(50) NOT NULL,
               uv varchar(50) NOT NULL,
               pv varchar(50) NOT NULL,
               amount smallint(5) NOT NULL,
               created_at timestamp NULL DEFAULT NULL
               PRIMARY KEY  (id)
             ) $charset_collate";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        // $classDB = new ClassTable();
        // $classDB->seo_dash_create_graph_table();
        // $classDB->seo_dash_insert_sample_graph_data();
    }


    /**
     * Custom REST API handler for the graph data.
     */
    public  function seo_dash_handle_custom_rest_api(){
        //DEclare our namespace
        $namespace = 'myrest/v1';

        // //register teh route for the graph data.
        // register_rest_route($namespace, '/performance', array(
        //     'methods' => WP_REST_Server::READABLE,
        //     'callback' => [ $this, 'seo_dash_fetch_graph_data']
        // ));
        
        // register_rest_route($namespace, '/products/?P<id>\d+)', array(
        //     'methods' => 'GET',
        //     //'callback' => 'seo_dash_fetch_graph_data'
        //     'callback' => function($data){
        //          $product = $data['id'];
        //          return $product;
        //     }
        // ));

        //register_rest_route( $namespace, '/project/(?P<id>\d+)', [
        //register_rest_route( $namespace, '/project/(?P<id>\d+)', [
        register_rest_route( $namespace, '/project' . '/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_project'],
            //'permission_callback' => '__return_true',
          ]);
    

    }


    /**
	 * Get a single graph performance
	 *
	 * @since 0.0.1
	 *
	 * @param \WP_REST_Request $request Full details about the request
	 *
	 * @return \WP_HTTP_Response
	 */
    public function get_project(  $request ) {
        $params = $request->get_params();

        $searchQuery = $params[ 'id' ];
        
        $results = ClassTable::seo_dash_fetch_data_from_db($searchQuery);

        $response = array(
            'message' => $results
        );
        wp_send_json_success($response);

    }


    /**
     * fetch the sample graph data
     */
    public function seo_dash_fetch_graph_data($request){
        return new WP_REST_RESPONSE(array(
            'success' => true,
            'value' => array(
                'message' => 'Performance graph is much available'
            )
        ), 200);
    }
        


}

new Hook_Registry();
