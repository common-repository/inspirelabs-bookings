<?php
namespace WPDesk\Bookings\Core;

/**
 * Database class
 * Includes all methods database related.
 *
 * @package WPDesk\Bookings
 */
class Database {

	/**
	 * @var int db version to be installed.
	 */
	private $target_version;

	/**
	 * @var string db table name.
	 */
	private $table_name;

	/**
	 * @var string db charset collate.
	 */
	private $charset_collate;



	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->target_version  = 1;
		$this->table_name      = $wpdb->prefix . 'inspirelabs_bookings';
		$this->charset_collate = $wpdb->get_charset_collate();

	}



	/**
	 * Gets installed plugin db version.
	 *
	 * @return int $version;
	 */
	private function get_version() : int {
		$version = get_option( 'inspirelabs_bookings_db', 0 );
		return (int) $version;
	}


	/**
	 * Set installed plugin db version.
	 *
	 * @param int $version .
	 */
	private function set_version( $version ) {
		update_option( 'inspirelabs_bookings_db', $version, false );
	}



	/**
	 * Main method to install required db tables by the plugin.
	 */
	public function install() {
		for ( $version = $this->get_version(); $version <= $this->target_version; $version++ ) :
			if ( $this->create_table( $version ) ) :
				$this->set_version( $version + 1 );
			endif;
		endfor;
	}



	/**
	 * Create database tables.
	 *
	 * @param int $version db version to be installed.
	 *
	 * @return bool $result db creation result.
	 */
	private function create_table( $version ) : bool {

		global $wpdb;
		$result = false;

		switch ( $version ) :
			case 0:
				$sql = "CREATE TABLE {$this->table_name} (
				    date               DATE                   not null,
				    product_id         BIGINT                 not null,
				    order_id           BIGINT                 not null,
				    order_status       VARCHAR(256) default ''    null,
				    price              DOUBLE       default 0 not null,
				    duration           VARCHAR(64)  default ''    null,
				    user_id            bigint       		      null,
				    booking_key        VARCHAR(64)  default ''    null,

				    KEY(product_id),
				    KEY(order_id)
				) {$this->charset_collate}";
				$wpdb->show_errors( false );
				$result = (bool) $wpdb->query( $sql );
		endswitch;

		if ( $wpdb->last_error ) :
			$wpdb->print_error();
		endif;

		return $result;
	}

}
