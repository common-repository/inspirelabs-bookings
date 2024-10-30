<?php

namespace WPDesk\Bookings\Frontend;

use WPDesk\Bookings\Helpers\Booking;

/**
 * Order class
 * Includes methods used by WooCommerce order during new order submission.
 *
 * @package WPDesk\Bookings
 */
class Order {

	/**
	 * Plugin info
	 *
	 * @var WPDesk_Plugin_Info $plugin_info .
	 */
	public $plugin_info;



	/**
	 * __construct
	 *
	 * @param  object $plugin_info WPDesk_Plugin_Info.
	 * @return void
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}


	/**
	 * Run WordPress hooks
	 */
	public function hooks() {
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'update_cart_totals' ), 10, 1 );
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'apply_advance' ), 20, 1 );
		add_filter( 'woocommerce_cart_totals_fee_html', array( $this, 'advance_negative_to_positive' ), 10, 2 );

		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'update_cart_item_data' ), 10, 3 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_booking_details' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_booking_details' ), 10, 4 );
		add_action( 'woocommerce_new_order', array( $this, 'save_bookings_on_new_order' ), 1, 2 );
		add_filter( 'woocommerce_quantity_input_args', array( $this, 'hide_quantity_input_for_bookings' ), 20, 2 );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'allow_one_booking_product' ), 9999, 2 );
        // add booking details table to email and order details in 'My account'
        add_action( 'woocommerce_email_after_order_table', array( $this, 'display_booking_details_email'), 10, 6);
        add_action( 'woocommerce_order_details_after_order_table', array( $this, 'display_booking_details_user_account'), 10, 6);
        // change order of rows in new order email and and order details in 'My account' if advance option enabled
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'add_custom_order_totals_row' ), 30, 3 );

	}


	/**
	 * Add booking details to selected product
	 *
	 * @param array $cart_item_data .
	 * @param int   $product_id WC Product id.
	 * @param int   $variation_id .
	 *
	 * @return array $cart_item_data
	 */
	public function update_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

		if ( 'booking' === \WC_Product_Factory::get_product_type( $product_id ) ) {

			$cart_item_data['new_price'] = 0;

			if ( isset( $_POST['booking-details'] ) ) {

				$charter_details = json_decode( sanitize_text_field( stripslashes_deep( $_POST['booking-details'] ) ), true );
				foreach ( $charter_details as $date => $details ) {
					$cart_item_data['new_price']  += (float) $details[0]['price'];
					$cart_item_data['booking']     = $charter_details;
					$cart_item_data['booking_key'] = uniqid();
				}

			}

			return $cart_item_data;

		}
	}



	/**
	 * Update cart total
	 *
	 * @param object $cart .
	 */
	public function update_cart_totals( $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) :
			return;
		endif;

		if ( ! WC()->cart->is_empty() ) :

			// Iterate through each cart item.
			foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
				if ( isset( $cart_item['new_price'] ) ) :
					$price = $cart_item['new_price'];
					$cart_item['data']->set_price( $price );
				endif;
			endforeach;

		endif;
	}



	/**
	 * Apply advance to order that contains bookable products.
	 *
	 * @param object $cart WooCommerce cart object.
	 */
	public function apply_advance( $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) :
			return;
		endif;

		if ( ! WC()->cart->is_empty() ) :

			// Check if cart has booking product.
			$bookable_product = false;
            $additional_fees = array(); // linked to product
            $selected_dates = 0;
            $mode24h = false;

			foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
                if ( isset( $cart_item['booking'] ) ) :
					$bookable_product = true;
                    $additional_fees = get_post_meta( $cart_item['product_id'], 'ilabs_booking_fees', true);
                    $selected_dates = array_keys( $cart_item['booking'] );
                    $mode24h = get_post_meta( $cart_item['product_id'], 'ilabs_bookings_24h_mode', true );
				endif;
			endforeach;

			if ( $bookable_product ) :

				$booking_settings = new Booking();
			    $total_fees = 0;

                // if bookable product has additional fees
                if ( class_exists( '\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro' ) ) {
                    if ( !empty( $additional_fees ) ) {
                        foreach ( $additional_fees as $fee ) {
                            if ( !empty( $fee['value'] ) && !empty( $fee['name'] ) ) {
                                $cart->add_fee( esc_html( $fee['name'] ), $fee['value'], false );
                                $total_fees += $fee['value'];
                            }
                        }
                    }
                }

                // if bookable product has short booking fees
                if ( class_exists( '\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro' ) ) {
                    $short_booking_fees = get_option( 'inspirelabs_bookings_short_booking' );
                    if ( !empty( $short_booking_fees ) ) {
                        if ( $mode24h ) {
                            $nights = count( $selected_dates ) - 1;
                            if ( $nights > 0 && $nights < 4 ) {
                                $short_booking_fee_percent = $short_booking_fees[$nights];
                                if ( (int) $short_booking_fee_percent > 0 ) {
                                    $short_booking_fee_value = $cart->cart_contents_total * $short_booking_fee_percent / 100;
                                    $cart->add_fee( __('Short booking fee', 'inspirelabs-bookings' ), $short_booking_fee_value, false );
                                    $total_fees += $short_booking_fee_value;
                                }
                            }
                        } else {
                            if ( count( $selected_dates ) > 0 && count( $selected_dates ) < 4 ) {
                                $short_booking_fee_percent = $short_booking_fees[count( $selected_dates )];
                                if ( (int) $short_booking_fee_percent > 0 ) {
                                    $short_booking_fee_value = $cart->cart_contents_total * $short_booking_fee_percent / 100;
                                    $cart->add_fee( __('Short booking fee', 'inspirelabs-bookings' ), $short_booking_fee_value, false );
                                    $total_fees += $short_booking_fee_value;
                                }
                            }
                        }
                    }
                }

				// Check if advance are enabled.
				if ( $booking_settings->is_advance() ) :
                    $advance_percent = $booking_settings->get_advance_percent();
                    $cart_total = $cart->cart_contents_total;

                    if( $total_fees > 0 ) {
                        $cart_total = $total_fees + floatval( $cart_total );
                    }

                    $advance = $booking_settings->get_advance( $cart_total );

					if ( $advance > 0 ) :
						//$advance -= $cart->cart_contents_total; // count advance from only booking without fees
						$advance -= $cart_total;
						$cart->add_fee( __( 'Remain', 'inspirelabs-bookings' ), $advance, false );
                        // show Advance row on checkout and in cart via JS
                        if ( is_cart() || is_checkout()) {
                            wp_enqueue_script( 'inspirelabs-booking-checkout-data', $this->plugin_info->get_plugin_url() . '/assets/js/product/inspirelabs-booking-checkout.js', array( 'jquery' ), $this->plugin_info->get_version(), true );
                            wp_localize_script(
                                'inspirelabs-booking-checkout-data',
                                'booking_data',
                                array(
                                    'advance'         => __('Advance', 'inspirelabs-bookings'),
                                    'advance_percent' => $advance_percent . '%'
                                )
                            );
                        }
					endif;

				else :
					// hide field subtotal
					wp_enqueue_style( 'inspirelabs-booking-hide-subtotal', $this->plugin_info->get_plugin_url() . '/assets/css/hide-subtotal-on-checkout.css', array(), $this->plugin_info->get_version() . '&' . time() );
				endif;



			endif;

		endif;
	}



	/**
	 * Change negative to positive advance amount
	 *
	 * @param string $cart_totals_fee_html fee html.
	 * @param object $fee WooCommerce fee object.
	 *
	 * @return mixed|string
	 */
	public function advance_negative_to_positive( $cart_totals_fee_html, $fee ) {
		if ( 'remain' === $fee->id ) :
			$cart_totals_fee_html = wc_price( $fee->total * -1 );
		endif;

		return $cart_totals_fee_html;

	}



	/**
	 * Display booking details in the cart and checkout
	 * Date from, to and nights no.
	 *
	 * @param array $cart_item_data .
	 * @param array $cart_item .
	 *
	 * @return array $cart_item_data
	 */
	public function display_booking_details( $cart_item_data, $cart_item ) {

		if ( isset( $cart_item['booking'] ) ) :

			$mode24h = get_post_meta( $cart_item['product_id'], 'ilabs_bookings_24h_mode', true );

			$selected_dates = array_keys( $cart_item['booking'] );
			$date_format    = get_option( 'date_format' );

			$cart_item_data[] = array(
				'name'  => __( 'Date from', 'inspirelabs-bookings' ),
				'value' => date_i18n( $date_format, strtotime( $selected_dates[0] ) ),
			);
			$cart_item_data[] = array(
				'name'  => __( 'Date to', 'inspirelabs-bookings' ),
				'value' => date_i18n( $date_format, strtotime( $selected_dates[ count( $selected_dates ) - 1 ] ) ),
			);
			if ( $mode24h && class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro') ) {
				$cart_item_data[] = array(
					'name'  => __( 'Nights no', 'inspirelabs-bookings' ),
					'value' => count( $selected_dates ) - 1,
				);
			}

		endif;

		return $cart_item_data;
	}



	/**
	 * Save charter details as WooCommerce post meta _charter
	 *
	 * @param object $item WooCommerce cart item.
	 * @param string $cart_item_key WooCommerce cart item key.
	 * @param array  $values .
	 * @param object $order WooCommerce order.
	 */
	public function save_booking_details( $item, $cart_item_key, $values, $order ) {

		if ( isset( $values['booking_key'] ) && isset( $values['booking'] ) ) :

			// Cleanup booking array before submitting by removing second (empty) level of it.
			$booking_details = $values['booking'];
			if ( is_array( $booking_details ) ) :
				$booking_details = array_map(
					function( $single_date ) {
						return $single_date[0];
					},
					$booking_details
				);
			endif;

			$item->add_meta_data( '_booking_key', $values['booking_key'] );
			$order->update_meta_data( '_booking_' . $values['booking_key'], $booking_details );

		endif;
	}



	/**
	 * Save advance date to order post meta.
	 *
	 * @param object $order WooCommerce order object.
	 * @param array  $data array of booking details for db insert.
	 */
	public function set_advance_payment_date( $order, $data ) {

		$first_date = min( array_column( $data, 'date' ) );
		$booking    = new Booking();
		$days_no    = $booking->get_booking_advance_rest_days();
		$order_time = $order->get_date_created()->date( 'H:i:s' );

		if ( $days_no > 0 ) :
			$date = gmdate( 'Y-m-d H:i:s', strtotime( $first_date . $order_time . " -{$days_no} day" ) );
		else :
			$date = gmdate( 'Y-m-d H:i:s', strtotime( $first_date . $order_time ) );
		endif;

		return add_post_meta( $order->get_id(), '_booking_advance_date', $date );

	}



	/**
	 * Save rows with single day booking details within inspirelabs_bookings table.
	 *
	 * @param int    $order_id WooCommerce order id.
	 * @param object $order WooCommerce order object.
	 *
	 * @return bool|int
	 */
	public function save_bookings_on_new_order( $order_id, $order ) {

		$result = false;

		if ( count( $order->get_items() ) > 0 ) :

			$data = array();

			foreach ( $order->get_items() as $item_id => $item ) :
				if ( $item->meta_exists( '_booking_key' ) && $item->get_product()->is_type( 'booking' ) ) :
					$booking_key     = $item->get_meta( '_booking_key' );
					$booking_details = $order->get_meta( '_booking_' . $booking_key );
					if ( is_array( $booking_details ) ) :
						foreach ( $booking_details as $date => $details ) :
							$data[] = array(
								'date'         => $date,
								'product_id'   => (int) $item->get_product_id(),
								'order_id'     => (int) $order->get_id(),
								'order_status' => $order->get_status(),
								'price'        => (float) $details['price'],
								'duration'     => $details['duration'],
								'user_id'      => $order->get_user_id(),
								'booking_key'  => $booking_key,
							);
						endforeach;
					endif;
				endif;
			endforeach;

			if ( ! empty( $data ) ) :

				$this->set_advance_payment_date( $order, $data );

				global $wpdb;
				$table_name = $wpdb->prefix . 'inspirelabs_bookings';

				// Create column list.
				$columns = array_keys( $data[0] );
				$columns = implode( ', ', $columns );

				$placeholders = array();
				$rows         = array();

				foreach ( $data as $row ) :
					$row_placeholders = array();
					foreach ( $row as $key => $value ) :
						$rows[]             = $value;
						$row_placeholders[] = is_numeric( $value ) ? '%d' : '%s';
					endforeach;
					$placeholders[] = '(' . implode( ', ', $row_placeholders ) . ')';
				endforeach;

				// Create placeholder for query.
				$placeholders = implode( ', ', $placeholders );

				// Run the query. Returns number of affected rows.
				$result = $wpdb->query( $wpdb->prepare( "INSERT INTO {$table_name} ({$columns}) VALUES {$placeholders}", $rows ) );

			endif;

		endif;

		return $result;

	}



	/**
	 * Hide quantity input for booking product type in cart and checkout
	 *
	 * @param array  $args .
	 * @param object $product .
	 *
	 * @return array $args
	 */
	public function hide_quantity_input_for_bookings( $args, $product ) {
		if ( 'booking' === $product->get_type() ) :
			$input_value       = $args['input_value'];
			$args['min_value'] = $args['max_value'] = $input_value;
		endif;
		return $args;
	}



	/**
	 * Removes same ID booking product type per order.
	 * And removes all other type of products except booking.
	 *
	 * @param int $passed .
	 * @param int $added_product_id Last added product ID to cart.
	 *
	 * @return mixed
	 */
	public function allow_one_booking_product( $passed, $added_product_id ) {

		if ( 'booking' === \WC_Product_Factory::get_product_type( $added_product_id ) ) {

			$products = \WC()->cart->get_cart();
			if ( $products ) {
				foreach ( $products as $cart_item_key => $cart_item ) {
					if ( $cart_item['product_id'] === $added_product_id ) {
						WC()->cart->remove_cart_item( $cart_item_key );
					}
					if ( 'booking' !== \WC_Product_Factory::get_product_type( $cart_item['product_id'] ) ) {
						WC()->cart->remove_cart_item( $cart_item_key );
					}
				}
			}
		}

		return $passed;
	}


    /**
     * Add table with booking details to order emails
     *
     */
    public function display_booking_details_email( $order, $sent_to_admin, $plain_text, $email ) {

        $this->generate_booking_details_html( $order, 1 );

    }


    /**
     * Add table with booking details to order details in My account
     *
     */
    public function display_booking_details_user_account( $order, $sent_to_admin = '', $plain_text = '', $email = '' ) {
        // Only on "My Account" > "Order View"
        if ( is_wc_endpoint_url( 'view-order' ) ) {
            $this->generate_booking_details_html( $order );
        }
    }


    /**
     * Generate HTML table with booking details
     *
     */
    public function generate_booking_details_html( $order, $email_mode = null ) {

        foreach ( $order->get_items() as $item_id => $item ) {
            $product = $item->get_product();
            if ( $product->is_type( 'booking' ) && $item->meta_exists( '_booking_key' ) ) {
                $booking_key = $item->get_meta( '_booking_key' );
                $booking_details = get_post_meta( $item->get_order_id(), '_booking_' . $booking_key, true );

                if ( !empty( $booking_details ) ) {
                    $selected_dates = array_keys($booking_details);
                    $date_format = get_option('date_format'); ?>

                    <h2 style='color: #7f54b3; display: block; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;'>
                        <?php esc_html_e('Your booking details', 'inspirelabs-bookings'); ?>
                    </h2>
                    <table class="td" cellspacing="0" cellpadding="6" border="1"
                           style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"
                           width="100%">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <th class="td" scope="row" colspan="2"
                                style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"
                                align="left">
                                <?php esc_html_e('Date from', 'inspirelabs-bookings'); ?>
                            </th>
                            <td class="td"
                                style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"
                                align="left">
                        <span class="woocommerce-Price-amount amount">
                            <?php echo esc_html(date_i18n($date_format, strtotime(reset($selected_dates)))); ?>
                        </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="td" scope="row" colspan="2"
                                style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                align="left">
                                <?php esc_html_e('Date to', 'inspirelabs-bookings'); ?>
                            </th>
                            <td class="td"
                                style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                align="left">
                                <?php echo esc_html(date_i18n($date_format, strtotime(end($selected_dates)))); ?>
                            </td>
                        </tr>

                        <?php // to avoid too long table in emails
                            if (count($booking_details) > 15 && isset( $email_mode ) ) { ?>

                            <?php if (end($booking_details)['price'] === '0.00') { ?>
                                <tr>
                                    <th class="td" scope="row" colspan="2"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                        <?php esc_html_e('Nights no', 'inspirelabs-bookings'); ?>
                                    </th>
                                    <td class="td"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                    <span class="woocommerce-Price-amount amount">
                                        <?php echo esc_html(count($selected_dates) - 1); ?>
                                    </span>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th class="td" scope="row" colspan="2"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                        <?php esc_html_e('Total days', 'inspirelabs-bookings'); ?>
                                    </th>
                                    <td class="td"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                    <span class="woocommerce-Price-amount amount">
                                        <?php echo esc_html(count($selected_dates)); ?>
                                    </span>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } else { ?>

                            <?php foreach ($booking_details as $day => $details) { ?>
                                <tr>
                                    <th class="td" scope="row" colspan="2"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                        <?php echo esc_html(date_i18n($date_format, strtotime($day))); ?>
                                    </th>
                                    <td class="td"
                                        style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                                        align="left">
                                <span class="woocommerce-Price-amount amount">
                                    <?php echo wp_kses_data(wc_price((float)$details['price'])); ?>
                                </span>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                        <th class="td" scope="row" colspan="2"
                            style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                            align="left">
                            <?php esc_html_e('Subtotal:', 'woocommerce'); ?>
                        </th>
                        <td class="td"
                            style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;"
                            align="left">
                            <?php echo $order->get_subtotal_to_display(); ?>
                        </td>
                        </tbody>
                    </table>
                    <br></br>

                <?php }
            }
        }
    }


    /**
     * Change order of rows in new order email and and order details in 'My account'
     * if advance option enabled
     *
     */
    function add_custom_order_totals_row( $total_rows, $order, $tax_display ) {

        $booking_settings = new Booking();

        if ( class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro' ) && $booking_settings->is_advance() ) {

            foreach ( $order->get_items() as $item_id => $item ) {
                $product = $item->get_product();
                // check if deal with order which contains booking type product
                if ($product->is_type('booking') && $item->meta_exists('_booking_key')) {

                    $order_total_row = $total_rows['order_total'];
                    $advance_row = $order_total_row;
                    $advance_row['label'] = __('Advance:', 'inspirelabs-bookings');
                    $order_total_row['label'] = __('To pay:', 'inspirelabs-bookings');
                    unset( $total_rows['order_total'] );
                    $payment_method_row = $total_rows['payment_method'];
                    unset( $total_rows['payment_method'] );

                    $fee_rows = array();
                    $remain = '';
                    foreach ( $total_rows as $key_row => $row_values ) {

                        if ( strpos( $key_row, 'fee_' ) !== false ) {
                            // extract row 'Remain'
                            if ( strpos( $row_values['value'], '-' ) !== false ) {
                                $total_rows[$key_row]['value'] = str_replace( '-', '', $row_values['value'] );
                                $remain = $total_rows[$key_row];
                                unset( $total_rows[$key_row] );
                            } else {
                                $fee_rows[$key_row] = $total_rows[$key_row];
                                unset( $total_rows[$key_row] );
                            }
                        }
                    }

                    foreach( $fee_rows as $fee_row ) {
                        $total_rows[] = $fee_row;
                    }
                    $total_rows[] = $advance_row;
                    $total_rows[] = $remain;
                    $total_rows[] = $payment_method_row;
                    $total_rows[] = $order_total_row;
                }
            }
        }

        return $total_rows;

    }

}
