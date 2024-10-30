<?php
/**
Plugin Name: iLabs Booking WooCommerce - apartments, boats, cars
Description: This plugin in integration with Woocommerce allows you to create different products for rent, with the output of the calendar for booking on the product page.
You can use it for any entity where you need to work with rentals and date ranges.
Product: iLabs Booking WooCommerce - apartments, boats, cars
Version: 1.0.2
Author: ilabs.dev
Author URI: https://ilabs.dev/
Text Domain: inspirelabs-bookings
Domain Path: /lang/

@package \WPDesk\Bookings

Copyright 2022 Inspire Labs sp. z o.o.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version     = '1.0.2';

$plugin_name        = 'iLabs Booking WooCommerce - apartments, boats, cars';
$plugin_class_name  = '\WPDesk\Bookings\Plugin';
$plugin_text_domain = 'inspirelabs-bookings';
$product_id         = 'inspirelabs-bookings';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

$requirements = [
	'php'     => '7.0',
	'wp'      => '5.0',
	'plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '4.7',
		],
	],
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/plugin-init-php52-free.php';
