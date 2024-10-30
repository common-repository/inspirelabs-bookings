<?php

namespace WPDesk\Bookings\Helpers;

/**
 * Common class
 * Includes methods used both by frontend and backend.
 *
 * @package WPDesk\Bookings
 */
class Common {

	/**
	 * Duration options
	 * Note! You can not use "-" as space/delimeter in value
	 *
	 * @param string $value .
	 * @return array $options
	 */
	public static function duration_options( $value = null ) {

		$options = array(
			'day'    => array(
				'label'       => __( 'Any range', 'inspirelabs-bookings' ),
				'description' => __( 'Book any interval within period', 'inspirelabs-bookings' ),
			),
			'week'   => array(
				'label'       => __( 'Week', 'inspirelabs-bookings' ),
				'description' => __( 'Let book for week', 'inspirelabs-bookings' ),
			),
			'period' => array(
				'label'       => __( 'Whole range', 'inspirelabs-bookings' ),
				'description' => __( 'Only the entire period booking is allowed', 'inspirelabs-bookings' ),
			),
		);

		// Filter options for given value only.
		if ( $value ) :
			return $options[ $value ];
		endif;

		return $options;

	}



	/**
	 * Get array of translated month full name.
	 *
	 * @return array
	 */
	public static function month_names_translation() : array {
		return array(
			1  => __( 'January', 'inspirelabs-bookings' ),
			2  => __( 'Febraury', 'inspirelabs-bookings' ),
			3  => __( 'March', 'inspirelabs-bookings' ),
			4  => __( 'April', 'inspirelabs-bookings' ),
			5  => __( 'May', 'inspirelabs-bookings' ),
			6  => __( 'June', 'inspirelabs-bookings' ),
			7  => __( 'July', 'inspirelabs-bookings' ),
			8  => __( 'August', 'inspirelabs-bookings' ),
			9  => __( 'September', 'inspirelabs-bookings' ),
			10 => __( 'October', 'inspirelabs-bookings' ),
			11 => __( 'November', 'inspirelabs-bookings' ),
			12 => __( 'December', 'inspirelabs-bookings' ),
		);
	}



	/**
	 * Get array of translated day single letter representation.
	 *
	 * @return array
	 */
	public static function short_days_translation() : array {
		return array(
			1 => __( 'Sun', 'inspirelabs-bookings' ),
			2 => __( 'Mon', 'inspirelabs-bookings' ),
			3 => __( 'Tu', 'inspirelabs-bookings' ),
			4 => __( 'Wed', 'inspirelabs-bookings' ),
			5 => __( 'Th', 'inspirelabs-bookings' ),
			6 => __( 'Fri', 'inspirelabs-bookings' ),
			7 => __( 'Sat', 'inspirelabs-bookings' ),
		);
	}



	/**
	 * Get array days
	 *
	 * @return array
	 */
	public static function daysofweek() : array {
		return array(
			1 => __('Monday', 'inspirelabs-bookings' ),
			2 => __('Tuesday', 'inspirelabs-bookings' ),
			3 => __('Wednesday', 'inspirelabs-bookings' ),
			4 => __('Thursday', 'inspirelabs-bookings' ),
			5 => __('Friday', 'inspirelabs-bookings' ),
			6 => __('Saturday', 'inspirelabs-bookings' ),
			7 => __('Sunday', 'inspirelabs-bookings' )
		);
	}

}
