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

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	}

	/**
	 * Create the database table for the graph
	 */
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

		dbDelta( $sql );

	}


	/**
	 * Insert the sample charts data into the graph table
	 */

	public function seo_dash_insert_sample_graph_data() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'graph_table';
		$charset_collate = $wpdb->get_charset_collate();

		$person_list = array( 'Messi', 'Ronald', 'Mary', 'Piller', 'Dubey' );

		foreach ( $person_list as $person ) {

			$start_date  = '03/01/2023';
			$end_date    = '04/07/2023';
			$random_date = $this->generate_random_date( $start_date, $end_date );
			$wpdb->insert(
				$table_name,
				array(
					'name'       => $person,
					'uv'         => $this->generate_random_numbers( 1000, 3000 ),
					'pv'         => $this->generate_random_numbers( 5000, 7000 ),
					'amount'     => $this->generate_random_numbers( 5000, 8000 ),
					'created_at' => $random_date,
				),
				array(
					'%s',
					'%d',
					'%d',
					'%d',
					'%s',
				)
			);

		}

	}

	/**
	 * Fetch the graph data based on selected input
	 *
	 * @param string $search_query
	 *
	 * @return array
	 */
	public static function seo_dash_fetch_data_from_db( $search_query ) {
		global $wpdb;
		$graph_table = $wpdb->prefix . 'graph_table';

		$search_query = (int) $search_query;

		$search_value = 3;
		if ( $search_query === 3 ) { // last 3 days.
			$search_value = 3;
		} elseif ( $search_query === 15 ) { // last 15 days.
			$search_value = 15;
		} elseif ( $search_query === 30 ) { // last 30 days (month).
			$search_value = 30;
		} else {
			$search_value = $search_query;
		}

		$query   = $wpdb->prepare( "SELECT * FROM $graph_table WHERE created_at > NOW() - INTERVAL ' " . $search_value . "' day" );
		//$query   = $wpdb->prepare( "SELECT * FROM $graph_table g WHERE g.created_at > NOW() - INTERVAL =%d  day", $search_value );
		$results = $wpdb->get_results( $query );
		return $results;
	}

	/**
	 * Generate random numbers
	 *
	 * @param int $min_number
	 * @param int $maxi_number
	 * @return int
	 */
	private function generate_random_numbers( $min_number, $maxi_number ) {
		return wp_rand( $min_number, $maxi_number );
	}

	/**
	 *  Generate random date
	 *
	 *  @param string $first_date
	 *  @param string $second_date
	 *  @param string $format
	 *  @return date
	 */
	private function generate_random_date( $first_date, $second_date, $format = 'Y-m-d' ): string {
		$first_date_timestamp  = strtotime( $first_date );
		$second_date_timestamp = strtotime( $second_date );

		if ( $first_date_timestamp < $second_date_timestamp ) {
			return gmdate( $format, mt_rand( $first_date_timestamp, $second_date_timestamp ) );
		}

		return date( $format, mt_rand( $second_date_timestamp, $first_date_timestamp ) );
	}
}
