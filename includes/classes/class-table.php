<?php
/**
 * Class Table 
 *
 * @category   Components
 * @package    seo-dash
 * @author     Bogere Goldsoft
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       www.servicecops.com
 * @since      1.0.0
 */

namespace Seo\Dash;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *  Class Table for interacting with the database
 */
class ClassTable {
    
    /**
     * Class constructor
     */
    //public function __construct() {
    //    $this->add_hooks();
    //}

    public function init(){
        //$this->school_pay_handle_reset_password();
    }


    public function seo_dash_create_graph_table(){
        global $wpdb;
        $graph_table = $wpdb->prefix . "graph_table";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $graph_table (
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
    }


    public function seo_dash_insert_sample_graph_data(){
        global $wpdb;
        $sampleData = $this->seo_dash_get_sample_data();
        $graph_table = $wpdb->prefix . "graph_table";
        $charset_collate = $wpdb->get_charset_collate();

        foreach($sampleData as $data){

            $wpdb->insert( 
                $graph_date, 
                array( 
                    'name' => $data['name'], 
                    'uv' => $data['uv'],
                    'pv' => $data['pv'],  
                    'amount' => $data['amt'],
                    'created_at' => date('Y-m-d H:i:s')
                ),
                array(
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                ) 
            );
               
        }
       
    }


    private function seo_dash_get_sample_data(){
        $sampleData = [
           
           [
            'name' => 'Goldsoft',
            'uv' => 4000,
             'pv' => 2400,
            'amt' => 2400,
           ],
           [
            'name' => 'Kashyap',
            'uv' => 2000,
             'pv' => 9800,
            'amt' => 2290,
           ],
           [
            'name' => 'Mary',
            'uv' => 3000,
             'pv' => 9800,
            'amt' => 1900,
           ],
           [
            'name' => 'Piller',
            'uv' => 2500,
             'pv' => 1400,
            'amt' => 5400,
           ],
           [
            'name' => 'Dubey',
            'uv' => 1200,
             'pv' => 8500,
            'amt' => 4400,
           ],
           [
            'name' => 'John',
            'uv' => 2200,
             'pv' => 3400,
            'amt' => 4300,
           ],
           [
            'name' => 'Messi',
            'uv' => 3200,
             'pv' => 2400,
            'amt' => 2400,
           ],
           [
            'name' => 'Hazard',
            'uv' => 2000,
             'pv' => 3400,
            'amt' => 2400,
           ],
           [
            'name' => 'Vibhute',
            'uv' => 2780,
             'pv' => 3908,
            'amt' => 2000,
           ],
          
        ];
        return $sampleData;
    }

    /**
     * Fetch the graph data based on selected input
     */
    public static function seo_dash_fetch_data_from_db($searchQuery){
        global $wpdb;
        $graph_table = $wpdb->prefix . "graph_table";

        $searchQuery = (int) $searchQuery;
        $searchValue = 3;
        if ($searchQuery == 3) { // last 3 days
            $searchValue = 3;
        }else if ($searchQuery == 15) { //last 15 days
            $searchValue = 15; 
        }else if ($searchQuery == 30) { // last 30 days (month)
            $searchValue = 30;
        }else{
            $searchValue = 12;
        }



        $query = $wpdb->prepare("SELECT * FROM $graph_table WHERE created_at > NOW() - INTERVAL ' ". $searchValue . "' day");
        $results = $wpdb->get_results($query);
        return $results;
    }

    
    
} 