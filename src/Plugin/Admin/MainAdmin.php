<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\PluginTemplate
 */
namespace WPDesk\Bookings\Admin;

use WPDesk\Bookings\Admin\WC_Product\PriceListDataTab;
use WPDesk\Bookings\Admin\WC_Order\Order;

/**
 * WooCommerce Custom Product Type class helper
 */
class MainAdmin {

	/**
	 * WP Desk Plugin Info Object includes var such as plugin ver, name, id, dir, url, etc.
	 *
	 * @var object PDesk_Plugin_Info.
	 */
	public $plugin_info;



	/**
	 * __construct
	 *
	 * @param  object $plugin_info WPDesk_Plugin_Info.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}



	/**
	 * Run WordPress hooks
	 */
	public function hooks() {
		// check if we have Pro Add-on
		if( !class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro') ) {
			// Run booking (product) price list tab in product settings
			add_action( 'admin_init', array( new PriceListDataTab( $this->plugin_info ), 'hooks' ) );
		}

		add_action( 'admin_init', array( new Order(), 'hooks' ) ); // Run WooCommerce order extensions.
		add_action( 'woocommerce_loaded', array( $this, 'register_custom_product_type' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ), 50 );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_assets' ) );
	}



	/**
	 * Register WooCommerce Custom Product Type
	 * This function is required by WooCommerce in order to manage
	 * custom product types
	 *
	 * @return void
	 */
	public function register_custom_product_type() {
		require_once $this->plugin_info->get_plugin_dir() . '/src/Plugin/Admin/WC_Product/WCProductBooking.php';
	}



	/**
	 * Manage product data tabs
	 *
	 * @param  array $tabs WooCommerce Product Data tabs.
	 * @return array $tabs .
	 */
	public function product_data_tabs( $tabs ) {

		// Hide shipping.
		if ( isset( $tabs['shipping']['class'] ) ) :
			array_push( $tabs['shipping']['class'], 'hide_if_booking' );
		endif;

		// Add price list.
		$tabs['booking_price_list'] = array(
			'label'    => __( 'Price List', 'inspirelabs-bookings' ),
			'priority' => 10,
			'target'   => 'booking_price_list',
			'class'    => 'show_if_booking',
		);

        if( class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro' ) ) {
            // Add additional payments tab.
            $tabs['booking_additional_payments'] = array(
                'label' => __( 'Additional payments', 'inspirelabs-bookings' ),
                'priority' => 20,
                'target' => 'booking_additional_payments',
                'class' => 'show_if_booking',
            );
        }

		return $tabs;
	}



	/**
	 * Enqueue globally required styles and scripts
	 *
	 * @return void
	 */
	public function plugin_assets() {
		wp_enqueue_style( 'inspirelabs-booking-admin', $this->plugin_info->get_plugin_url() . '/assets/css/inspirelabs-booking-admin.css', array(), $this->plugin_info->get_version() );
	}

}
