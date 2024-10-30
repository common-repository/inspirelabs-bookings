<?php

namespace WPDesk\Bookings\Helpers;

use WPDesk\Bookings\Helpers\Common;

/**
 * Price List class
 * This class create monthly price list including existing bookings, prices and all available details.
 *
 * @package WPDesk\Bookings
 */
class PriceList {

	/**
	 * WooCommerce product type booking id.
	 *
	 * @var int WC Product id.
	 */
	private $product_id;

	/**
	 * Before this date picking date will be unavailable
	 *
	 * @var string Y-M-d format date.
	 */
	private $limit_date;



	/**
	 * __construct
	 *
	 * @param int $product_id Booking product id.
	 */
	public function __construct( $product_id ) {
		$this->product_id = $product_id;
	}



	/**
	 * Set date limit, before this date picking date will be unavailable
	 *
	 * @param \DateTime $date DateTime object, before this date booking details and price list will be unavailable.
	 */
	public function set_limit_date( \DateTime $date ) {
		$date->setTime( 23, 59 );
		$this->limit_date = $date;
	}



	/**
	 * PriceList
	 * Prepare array of cells and dates for render
	 *
	 * @param int $year  Given year.
	 * @param int $month Given month number, range 1 - 12.
	 *
	 * @return array $result .
	 */
	public function price_list( int $year, int $month ) : array {

		$date = new \DateTime();
		$date->setDate( $year, $month, 1 );
		$date->setTime( 0, 0 );

		$today = new \DateTime();
		$today->setTime( 0, 0 );

		$days_in_month     = (int) $date->format( 't' );
		$first_day_cell_no = (int) $date->format( 'N' );
		$total_cells       = $this->weeks_in_month( $year, $month ) * 7;

		$price_list = $this->get_price_list( $year );
		if ( $price_list ) {
			$price_list = $this->limit_price_list_to_date( $price_list );
		}

		// Get all existing bookings stored within wp_inspirelabs_bookings db table.
		$bookings = $this->get_bookings( $year, $month );
		if ( $bookings ) {

			$mode24h = get_post_meta( $this->product_id, 'ilabs_bookings_24h_mode', true);
			if( $mode24h && class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro') ) {
				$bookings     = $this->set_first_last_booking_date( $bookings ); // !!!! for 24h mode let book half day first and last
			}

			$bookings     = $this->limit_bookings_to_single_month( $year, $month, $bookings );
			$bookings     = $this->limit_bookings_to_date( $bookings );
			$booked_dates = $this->get_booked_days( $bookings );
		}

		// Create array of days in month starting from first day of month number.
		$d = 0;
		foreach ( range( $first_day_cell_no, $total_cells, 1 ) as $i ) {
			$d ++;
			if ( $d <= $days_in_month ) {

				$this_date   = mktime( 0, 0, 0, $month, $d, $year );
				$this_day_no = intval( gmdate( 'N', $this_date ) );

				$this_day          = array();
				$allow_reservation = false;

				// Single day.
				$this_day['day']     = $d;
				$this_day['class'][] = 'day';

				// Today.
				if ( $this_date === $today->getTimestamp() ) {
					$this_day['today']   = true;
					$this_day['class'][] = 'today';
				}

				// Weekend.
				if ( 6 === $this_day_no || 7 === $this_day_no ) {
					$this_day['weekend'] = true;
					$this_day['class'][] = 'weekend';
				}

				if ( 1 === $this_day_no) {
					$this_day['class'][] = 'monday';
				}
				if ( 2 === $this_day_no) {
					$this_day['class'][] = 'tuesday';
				}
				if ( 3 === $this_day_no) {
					$this_day['class'][] = 'wednesday';
				}
				if ( 4 === $this_day_no) {
					$this_day['class'][] = 'thursday';
				}
				if ( 5 === $this_day_no) {
					$this_day['class'][] = 'friday';
				}
				if ( 6 === $this_day_no) {
					$this_day['class'][] = 'saturday';
				}
				if ( 7 === $this_day_no) {
					$this_day['class'][] = 'sunday';
				}

				// Price list rules.
				if ( ! empty( $price_list ) ) {
					$rules = array_filter(
						$price_list,
						function ( $row ) use ( $this_date ) {
							return ( $this_date >= strtotime( $row['start'] ) && $this_date <= strtotime( $row['end'] ) );
						}
					);
					if ( $rules ) {
						$this_day['rules'] = $rules;
						$allow_reservation = true;
					}
				}

				// Booking.
				if ( $bookings ) {
					if ( in_array( gmdate( 'Y-m-d', $this_date ), $booked_dates, true ) ) {
						$booking_details = wp_list_filter( $bookings, array( 'date' => gmdate( 'Y-m-d', $this_date ) ) );
						$index           = 0;
						foreach ( $booking_details as $single_booking ) {
							unset( $single_booking['date'] );
							$this_day['booking'][ $index ] = $single_booking;
							if ( ! isset( $single_booking['first'] ) && ! isset( $single_booking['last'] ) ) {
								$this_day['class'][] = $single_booking['booking_status'];
							}
							if ( isset( $single_booking['first'] ) ) {
								$this_day['class'][] = 'first-day-book';
								$this_day['class'][] = $single_booking['booking_status'] . '__first-day-book';
							}
							if ( isset( $single_booking['last'] ) ) {
								$this_day['class'][] = 'last-day-book';
								$this_day['class'][] = $single_booking['booking_status'] . '__last-day-book';
							}
							$index++;
						}
						if ( ( array_column( $booking_details, 'first' ) && array_column( $booking_details, 'last' ) ) || ( ! array_column( $booking_details, 'first' ) && ! array_column( $booking_details, 'last' ) ) ) {
							$allow_reservation = false;
						}
					}
				}

				// Availability.
				if ( $allow_reservation ) {
					$this_day['available'] = true;
					$this_day['class'][]   = 'allow-reservation';
				} else {
					$this_day['available'] = false;
					$this_day['class'][]   = 'deny-reservation';
				}

				// Past: set deny
				if ( $this_date < $today->getTimestamp() ) {
					$this_day['class'] = array_diff($this_day['class'], ['allow-reservation']);
					$this_day['class'][] = 'deny-reservation';
				}

				$month_days[ $i ] = $this_day;
			}

		}

		// Create array of cells in month.
		$month_cells = array();
		foreach ( range( 1, $total_cells ) as $c ) {
			$month_cells[ intval( $c ) ] = intval( $c );
		}

		// Combine cells with days.
		$days = array_replace( array_fill_keys( array_flip( $month_cells ), null ), $month_days );

		return array(
			'Y' => $year,
			'n' => $month,
			'M' => Common::month_names_translation()[ $month ],
			'd' => $days,
		);
	}



	/**
	 * Gets number of weeks in a month
	 *
	 * @param int $year  Given year.
	 * @param int $month Given month, range 1 - 12.
	 *
	 * @return int
	 */
	private function weeks_in_month( int $year, int $month ) : int {

		$start = new \DateTime();
		$start->setDate( $year, $month, 1 );
		$start->setTime( 0, 0 );

		$end = new \DateTime();
		$end->setDate( $year, $month, $start->format( 't' ) );
		$end->setTime( 0, 0 );

		$start_week = (int) $start->format( 'W' );
		$end_week   = (int) $end->format( 'W' );

		if ( $end_week < $start_week ) {
			return ( ( 52 + $end_week ) - $start_week ) + 1;
		}

		return ( $end_week - $start_week ) + 1;
	}



	/**
	 * Get user defined price list for product by month and year
	 *
	 * @param int $year  Given year.
	 *
	 * @return array $result.
	 */
	private function get_price_list( int $year ) : array {

		$price_list = get_post_meta( $this->product_id, 'booking_price_list_' . $year, true );

		if ( $price_list ) {
			return $price_list;
		} else {
			return array();
		}
	}



	/**
	 * Remove all the price lists that ends before limit_date
	 *
	 * @param array $price_list Monthly price list from booking_monthly_price_list() method.
	 *
	 * @return array
	 */
	private function limit_price_list_to_date( array $price_list ) : array {

		if ( isset( $this->limit_date ) ) {
			$limit_date = $this->limit_date->format( 'Y-m-d' );
			$price_list = array_filter(
				$price_list,
				function ( $single_list ) use ( $limit_date ) {
					return ( ( $single_list['end'] >= $limit_date && 'day' === $single_list['duration'] ) || ( $single_list['start'] >= $limit_date && 'day' !== $single_list['duration'] ) );
				}
			);
			$price_list = array_map(
				function ( $single_list ) use ( $limit_date ) {
					if ( $limit_date > $single_list['start'] && 'day' === $single_list['duration'] ) {
						$single_list['start'] = $limit_date;
					}
					return $single_list;
				},
				$price_list
			);
			$price_list = array_filter( $price_list );
		}

		return $price_list;
	}



	/**
	 * Get booked dates by product_id in given year and month
	 *
	 * @param int $year  Given year.
	 * @param int $month Given month, range 1 - 12.
	 *
	 * @return array $dates Array of strong with booked dates
	 */
	private function get_bookings( int $year, int $month ) : array {

		global $wpdb;
		$table_name = $wpdb->prefix . 'inspirelabs_bookings';

		$date_from = new \DateTime();
		$date_from->setDate( $year, $month, 1 );
		$date_from->modify( 'first day of -1 month' );

		$date_to = new \DateTime();
		$date_to->setDate( $year, $month, 1 );
		$date_to->modify( 'last day of +1 month' );

		$to_book_statuses    = array( 'processing', 'completed' );  // Array of WooCommerce statuses that will change to book.
		$to_prebook_statuses = array( 'pending', 'on-hold' );       // Array of WooCommerce statuses that will change to pre-book.

		$string_to_book_statuses = array_map(
			function( $string ) {
				return "'" . esc_sql( $string ) . "'";
			},
			$to_book_statuses
		);
		$string_to_book_statuses = implode( ',', $string_to_book_statuses );

		$string_all_statuses = array_map(
			function( $string ) {
				return "'" . esc_sql( $string ) . "'";
			},
			array_merge( $to_prebook_statuses, $to_book_statuses )
		);
		$string_all_statuses = implode( ',', $string_all_statuses );

		$bookings = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT 
						date,
  						product_id,
  						order_id,
  						order_status,
  						price,
  						duration,
  						user_id,
  						booking_key,
  						IF (order_status IN ({$string_to_book_statuses}), 'book', 'pre-book' ) AS booking_status
					FROM {$table_name}
					WHERE product_id = %d 
						AND (date BETWEEN %s AND %s)
						AND order_status IN ({$string_all_statuses})",
				$this->product_id,
				$date_from->format( 'Y-m-d' ),
				$date_to->format( 'Y-m-d' )
			),
			ARRAY_A
		);

		return $bookings;
	}



	/**
	 * Return array of already booked dates from get_bookings method.
	 *
	 * @param array $bookings Array of bookings with details get_bookings() result.
	 *
	 * @return array
	 */
	private function get_booked_days( array $bookings ) : array {
		$booked_dates = array_column( $bookings, 'date' );
		$booked_dates = array_unique( $booked_dates );
		return $booked_dates;
	}



	/**
	 * Adds key to first and last date within grouped bookings
	 * This is required, so user can choose single date as one beginning and ending
	 *
	 * @param array $bookings Booking array returned by get_bookings method.
	 *
	 * @return array $bookings
	 */
	private function set_first_last_booking_date( $bookings ) {

		$orders_ids = array_column( $bookings, 'order_id' );
		$orders_ids = array_unique( $orders_ids );

		foreach ( $orders_ids as $order_id ) {
			$orders             = wp_list_filter( $bookings, array( 'order_id' => $order_id ) );

			if (function_exists('array_key_first') && function_exists('array_key_last')) {
				$first_booking_date = array_key_first( $orders );
				$last_booking_date  = array_key_last( $orders );
			} else {
				$first_booking_date = $this->array_key_f( $orders );
				$last_booking_date  = $this->array_key_l( $orders );
			}

			if ( $first_booking_date !== $last_booking_date ) {
				$bookings[ $first_booking_date ]['first'] = true;
			}
			$bookings[ $last_booking_date ]['last'] = true;
		}

		return $bookings;

	}

	public function array_key_f(array $array){
		if (count($array)) {
			reset($array);
			return key($array);
		}
		return null;
	}


	public function array_key_l(array $array){
		if (count($array)) {
			reset($array);
			end($array);
			return key($array);
		}
		return null;
	}



	/**
	 * Limit given booking array to single month and year selected by the user.
	 *
	 * @param int   $year     Year of month to limit bookings.
	 * @param int   $month    Month to limit bookings, range 1 - 12.
	 * @param array $bookings Booking array returned by get_bookings method.
	 *
	 * @return array $bookings Booking array returned by get_bookings method.
	 */
	private function limit_bookings_to_single_month( int $year, int $month, array $bookings ) : array {

		$month_to_limit = new \DateTime();
		$month_to_limit->setDate( $year, $month, 1 );

		$bookings = array_filter(
			$bookings,
			function( $booking ) use ( $month_to_limit ) {
				return $booking['date'] >= $month_to_limit->format( 'Y-m-d' ) && $booking['date'] <= $month_to_limit->format( 'Y-m-t' );
			}
		);

		return $bookings;
	}



	/**
	 * Remove all bookings to limit_date
	 *
	 * @param array $bookings Booking array returned by get_bookings method.
	 *
	 * @return array
	 */
	private function limit_bookings_to_date( array $bookings ) : array {

		if ( isset( $this->limit_date ) ) {
			$limit_date = $this->limit_date->format( 'Y-m-d' );
			$bookings   = array_filter(
				$bookings,
				function ( $booking ) use ( $limit_date ) {
					return $booking['date'] >= $limit_date;
				}
			);
		}

		return $bookings;
	}

}
