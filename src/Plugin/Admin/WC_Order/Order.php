<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\PluginTemplate
 */
namespace WPDesk\Bookings\Admin\WC_Order;


/**
 * WooCommerce Custom Product Type class helper
 */
class Order {

	/**
	 * Run WordPress hooks
	 */
	public function hooks() {
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'display_booking_details' ), 10, 3 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_custom_itemmeta' ), 10, 1 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'update_bookings_table_status' ), 10, 3 );
	}



	/**
	 * Display display table details under the order item.
	 *
	 * @param int    $item_id .
	 * @param object $item .
	 * @param object $product .
	 */
	public function display_booking_details( $item_id, $item, $product ) {

		if ( ! ( is_admin() && $item->is_type( 'line_item' ) ) ) :
			return;
		endif;

		if ( $product->is_type( 'booking' ) && $item->meta_exists( '_booking_key' ) ) :

			$booking_key     = $item->get_meta( '_booking_key' );
			$booking_details = get_post_meta( $item->get_order_id(), '_booking_' . $booking_key, true );
			$selected_dates  = array_keys( $booking_details );
			$date_format     = get_option( 'date_format' );

			include __DIR__ . '/order_details_template.php';

		endif;
	}



	/**
	 * Prevent charter itemmeta from displaying within the order items table
	 *
	 * @param array $itemmeta .
	 *
	 * @return array $itemmeta
	 */
	public function hide_custom_itemmeta( $itemmeta ) {
		$itemmeta[] = '_booking_key';
		return $itemmeta;
	}



	/**
	 * Update order status within inspirelabs_bookings db table.
	 *
	 * @param int    $order_id        WC order id.
	 * @param string $previous_status WC order previous status.
	 * @param string $new_status      WC order new status.
	 */
	public function update_bookings_table_status( $order_id, $previous_status, $new_status ) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'inspirelabs_bookings';

		$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET order_status=%s WHERE order_id=%d", $new_status, $order_id ) );
	}

}

