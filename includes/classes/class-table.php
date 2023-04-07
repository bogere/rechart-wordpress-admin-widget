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
    public function __construct() {
       
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    }


    public function seo_dash_create_graph_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'graph_table';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `pv` mediumint(9) NOT NULL DEFAULT 1000,
          `uv` mediumint(9) NOT NULL DEFAULT 500,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `amount` mediumint(9) NOT NULL DEFAULT 1000,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8";
        
        dbDelta($sql);

    }


    public function seo_dash_insert_sample_graph_data(){
        global $wpdb;
        $sampleData = $this->seo_dash_get_sample_data();
        $table_name = $wpdb->prefix . "graph_table";
        $charset_collate = $wpdb->get_charset_collate();

        $personList = array('Messi', 'Ronald', 'Mary', 'Piller', 'Dubey');

        foreach($personList as $person){
            
            $startDate = "03/01/2023";
            $endDate = "03/07/2023";
            $randomDate = $this->generateRandomDate($startDate, $endDate);
            $wpdb->insert( 
                $table_name, 
                // array( 
                //     'name' => $data['name'], 
                //     'uv' => $data['uv'],
                //     'pv' => $data['pv'],  
                //     'amount' => $data['amt'],
                //     'created_at' => date('Y-m-d H:i:s')
                // ),
                array( 
                    'name' => $person, 
                    'uv' => $this->generateRandomNumbers(1000,3000),
                    'pv' => $this->generateRandomNumbers(5000,7000),  
                    'amount' => $this->generateRandomNumbers(5000,8000),
                    'created_at' => $randomDate
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

    /**
     * Fetch the graph data based on selected input
     */
    public static function seo_dash_fetch_data_from_db($searchQuery){
        global $wpdb;
        $graph_table = $wpdb->prefix . "graph_table";

        $searchQuery = (int) $searchQuery;
        error_log("search value");
        error_log(print_r($searchQuery,true));

        $searchValue = 3;
        if ($searchQuery == 3) { // last 3 days
            $searchValue = 3;
        }else if ($searchQuery == 15) { //last 15 days
            $searchValue = 15; 
        }else if ($searchQuery == 30) { // last 30 days (month)
            $searchValue = 30;
        }else{
            //$searchValue = 12;
            $searchValue = 3;
        }

        error_log("search value");
        error_log(print_r($searchValue,true));

        $query = $wpdb->prepare("SELECT * FROM $graph_table WHERE created_at > NOW() - INTERVAL ' ". $searchValue . "' day");
        $results = $wpdb->get_results($query);
        return $results;
    }

    /**
     * Generate random numbers
     */
    private function generateRandomNumbers($minNumber, $maxiNumber){
        return rand($minNumber,$maxiNumber);
    }
    
    /**
     *  Generate random date
     */
    private function generateRandomDate($firstDate, $secondDate, $format = 'Y-m-d'): string
    {
        $firstDateTimeStamp = strtotime($firstDate);
        $secondDateTimeStamp = strtotime($secondDate);

        if ($firstDateTimeStamp < $secondDateTimeStamp) {
            return date($format, mt_rand($firstDateTimeStamp, $secondDateTimeStamp));
        }

        return date($format, mt_rand($secondDateTimeStamp, $firstDateTimeStamp));
    }
    
    
} 