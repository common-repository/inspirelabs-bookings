<?php

namespace WPDesk\Bookings\Helpers;

/**
 * Bookings class
 * This class includes methods for booking details.
 *
 * @package WPDesk\Bookings
 */
class Booking {

	/**
	 * Get advance details from plugin options.
	 *
	 * @var bool|array
	 */
	private $advance;

	/**
	 * WooCommerce order object.
	 *
	 * @var bool|object
	 */
	private $order = false;

	/**
	 * WordPress date format.
	 *
	 * @var bool|string
	 */
	private $date_format;



	/**
	 * __construct
	 */
	public function __construct() {
		$this->advance     = get_option( 'inspirelabs_bookings_advance' );
		$this->date_format = get_option( 'date_format' );
	}



	/**
	 * Set WooCommerce order
	 *
	 * @param int|object $order WooCommerce order object or order id.
	 */
	public function set_order( $order ) {
		if ( is_object( $order ) ) :
			$this->order = $order;
		else :
			if ( 'shop_order' === get_post_type( $order ) ) :
				$this->order = new \WC_Order( $order );
			endif;
		endif;
	}



	/**
	 * Get booking product names
	 *
	 * @return string
	 */
	public function get_products() {
		if ( $this->order ) :
			if ( count( $this->order->get_items() ) > 0 ) :

				$products_names = array();

				foreach ( $this->order->get_items() as $item ) :
					if ( $item->meta_exists( '_booking_key' ) && $item->get_product()->is_type( 'booking' ) ) :
						$products_names[] = $item->get_name();
					endif;
				endforeach;

				return implode( ', ', $products_names );
			endif;
		endif;
	}



	/**
	 * Get booking dates
	 *
	 * @return string
	 */
	public function get_dates() {
		if ( $this->order ) :
			if ( count( $this->order->get_items() ) > 0 ) :

				$booking_days = array();

				foreach ( $this->order->get_items() as $item ) :
					if ( $item->meta_exists( '_booking_key' ) && $item->get_product()->is_type( 'booking' ) ) :
						$products_names[] = $item->get_name();
						$booking_key      = $item->get_meta( '_booking_key' );
						$booking_details  = array_keys( $this->order->get_meta( '_booking_' . $booking_key ) );
						$booking_days[]   = gmdate( $this->date_format, strtotime( reset( $booking_details ) ) ) . ' - ' . gmdate( $this->date_format, strtotime( end( $booking_details ) ) );
					endif;
				endforeach;

				return implode( ', ', $booking_days );
			endif;
		endif;
	}



	/**
	 * Get booking total amount
	 *
	 * @return float
	 */
	public function get_total() {
		if ( $this->order ) :
			return (float) $this->order->get_subtotal();
		endif;
	}



	/**
	 * Check if advance are enabled.
	 *
	 * @return bool
	 */
	public function is_advance() {
		if( class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro') ) {
			if (is_array($this->advance) && array_key_exists('advanceenable', $this->advance) && !empty($this->advance['advanceenable'])) :
				return true;
			else :
				return false;
			endif;
		}
	}



	/**
	 * Get booking advance percent
	 * Can be set within plugin settings page
	 *
	 * @return float
	 */
	public function get_advance_percent() {
		if ( array_key_exists( 'advancepercent', $this->advance ) && ! empty( $this->advance['advancepercent'] ) ) :
			return (float) $this->advance['advancepercent'];
		endif;
	}



	/**
	 * Get advance amount
	 * Round to value from plugin settings
	 * Check if amount is enough from plugin settings
	 *
	 * @param float|bool $order_total Order total amount.
	 * @return float $advance
	 */
	public function get_advance( $order_total = false ) {

		if ( array_key_exists( 'advancemin', $this->advance ) && ! empty( $this->advance['advancemin'] ) ) :

			$advance     = 0;
			$round_to    = $this->advance['advanceround'] ? $this->advance['advanceround'] : 1;
			$advance_min = $this->advance['advancemin'];

			if ( empty( $order_total ) ) :
				$order_total = $this->get_total();
			endif;

			// Check if apply advance.
			if ( $order_total < $advance_min ) :
				return 0;
			endif;

			// Calculate advance.
			if ( $this->get_advance_percent() ) :
				$advance = (float) ( ( $order_total * $this->get_advance_percent() ) / 100 );
			endif;

			// Round advice.
			if ( $round_to > 0 && $advance > $round_to ) :
				$advance = ( ceil( $advance / $round_to ) * $round_to );
			else :
				return 0;
			endif;

			return (float) $advance;

		else :

			return 0;

		endif;

	}



	/**
	 * Get advance rest amount
	 *
	 * @return float
	 */
	public function get_advance_rest() {
		if ( $this->get_advance() > 0 ) :
			return (float) $this->get_total() - $this->get_advance();
		else :
			return (float) $this->get_total();
		endif;
	}



	/**
	 * Get no of days to pay first advance installment
	 *
	 * @return int
	 */
	public function get_booking_advance_days() {
		if ( array_key_exists( 'advancedaysfirst', $this->advance ) && ! empty( $this->advance['advancedaysfirst'] ) ) {
			return (int) $this->advance['advancedaysfirst'];
		}
	}


	/**
	 * Get date of first advance installment payment
	 *
	 * @return string
	 */
	public function get_booking_advance_date() {

		$days_no = $this->get_booking_advance_days();

		if ( $days_no > 0 ) :
			return gmdate( $this->date_format, strtotime( $this->order->get_date_created() . " +{$days_no} day" ) );
		else :
			return gmdate( $this->date_format, $this->order->get_date_created() );
		endif;
	}



	/**
	 * Get no of days to pay rest of advance
	 *
	 * @return int
	 */
	public function get_booking_advance_rest_days() {
		if ( is_array($this->advance) && array_key_exists( 'advancedaysrest', $this->advance ) && ! empty( $this->advance['advancedaysrest'] ) ) {
			return (int) $this->advance['advancedaysrest'];
		}
	}



	/**
	 * Get date of rest advance installment payment
	 *
	 * @return string
	 */
	public function get_booking_advance_rest_date() {
		if ( $this->order ) :
			$date = get_post_meta( $this->order->get_id(), '_booking_advance_date', true );
			if ( $date ) :
				$time_format = get_option( 'time_format' );
				return gmdate( $this->date_format . ' ' . $time_format, strtotime( $date ) );
			endif;
		endif;
	}
}
