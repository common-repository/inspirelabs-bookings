<?php
namespace WPDesk\Bookings\Frontend;

use BookingsVendor\WPDesk_Plugin_Info;

/**
 * Single Product class
 * This class creates single booking product type page.
 *
 * @package WPDesk\Bookings
 */
class SingleProduct {

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
	 * Run WordPress Hooks
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'single_product_assets' ) );

		$date_picker = new DatePicker();
		add_action( 'inspirelabs_booking_form_before_summary', array( $date_picker, 'date_picker' ) );
		add_action( 'wp_ajax_load_month', array( $date_picker, 'load_month' ) );
		add_action( 'wp_ajax_nopriv_load_month', array( $date_picker, 'load_month' ) );

		add_shortcode( 'ilabs_booking_form', array( $this, 'ilabs_booking_form' ) );
		add_filter( 'the_content', array( $this,'add_booking_form_before_product_description'), 10 );

		add_filter ('woocommerce_add_to_cart_redirect', array( $this, 'redirect_bookable_to_checkout'), 10, 2 );
		add_filter( 'wc_add_to_cart_message_html', array( $this, 'hide_cart_message_for_bookable' ), 10, 2 );
		add_filter('woocommerce_product_needs_shipping',  array( $this, 'remove_shipping_for_bookable'), 10, 2);

		add_filter('woocommerce_get_price_html',  array( $this, 'remove_price_for_bookable'), 10, 2);
		add_filter('woocommerce_loop_add_to_cart_link',  array( $this, 'change_addtocart_btn_for_bookable'), 10, 2);

	}


	/**
	 * Remove shipping for bookable products
	 */
	public function remove_shipping_for_bookable( $condition, $product ) {

		if ('booking' === \WC_Product_Factory::get_product_type($product->get_id())) {
			return $condition = false;
		}
		return $condition;
	}


	/**
	 * Hide message about adding to cart for bookable products
	 */
	public function hide_cart_message_for_bookable( $message, $products) {

		foreach( $products as $product_id => $quantity ) {
			if ('booking' === \WC_Product_Factory::get_product_type($product_id)) {
				return $message = '';
			}
		}
		return $message;
	}


	/**
	 * Redirect booking type products to checkout without cart step
	 */
	public function redirect_bookable_to_checkout( $url, $product) {

		if ( isset( $_POST['add-to-cart'] ) && !is_null( $product ) ) {
			if ('booking' === \WC_Product_Factory::get_product_type( $product->get_id() )) {
				return wc_get_checkout_url();
			}
		}
		return $url;
	}



	/**
	 * Add form calendar before long product description
	 */
	public function add_booking_form_before_product_description( $content ){

		if( is_product() ) {
			if ( 'booking' === \WC_Product_Factory::get_product_type( get_the_ID() ) ) {
				do_shortcode('[ilabs_booking_form]');
			}
		}
		return  $content;
	}


	/**
	 * Output booking form via shortcode
	 */
	public function ilabs_booking_form() {
		global $product;?>
		<form id="inspirelabs-booking-form" class="cart inspirelabs_wide_tabs_woo" action="<?php the_permalink($product->get_id()); ?>"
		      method="post" enctype="multipart/form-data">

			<?php do_action('inspirelabs_booking_form_before_summary'); ?>

			<section class="booking__summary">
				<header class="booking__summary__header">
					<?php esc_html_e('Your booking details', 'inspirelabs-bookings'); ?>
				</header>
				<span class="entire-period-booking-alert" style="color:red; font-size:0.8em"></span>
				<ul class="booking__summary__items" style="display: none"></ul>
				<div class="booking__summary__total">
					<?php esc_html_e('Total', 'inspirelabs-bookings'); ?>:
					<span class="price">0,00 <?php echo esc_html(get_woocommerce_currency_symbol()); ?></span>
				</div>
				<button class="add_to_cart_button button alt" name="add-to-cart" type="submit"
				        value="<?php echo esc_attr($product->get_id()); ?>" disabled>
					<?php esc_html_e('Book Now', 'inspirelabs-bookings'); ?>
				</button>
			</section>

			<?php do_action('inspirelabs_booking_form_after_summary'); ?>

		</form>
	<?php }



	/**
	 * Single product (booking type) assets
	 */
	public function single_product_assets() {
		if ( is_product() ) {
			if ( 'booking' === \WC_Product_Factory::get_product_type( get_the_ID() ) ) {

				wp_enqueue_script( 'swiper', $this->plugin_info->get_plugin_url() . '/assets/js/product/swiper-bundle.min.js', false, '6.7.0', true );
				wp_enqueue_script( 'popper', $this->plugin_info->get_plugin_url() . '/assets/js/product/popper.min.js', false, '2.9.3', true );
				wp_enqueue_script( 'tippy', $this->plugin_info->get_plugin_url() . '/assets/js/product/tippy-bundle.umd.js', false, '6.3.1', true );

				$mode24h = get_post_meta( get_the_ID(), 'ilabs_bookings_24h_mode', true);

				if( $mode24h && class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro') ) {
					wp_enqueue_style( 'inspirelabs-booking-product-24h', $this->plugin_info->get_plugin_url() . '/assets/css/inspirelabs-booking-product-24h.css', array(), $this->plugin_info->get_version() . '&' . time() );
					wp_enqueue_script( 'inspirelabs-booking-form', $this->plugin_info->get_plugin_url() . '/assets/js/product/inspirelabs-booking-form-24h.js', array( 'swiper', 'popper', 'tippy' ), $this->plugin_info->get_version(), true );
				} else {
					wp_enqueue_style( 'inspirelabs-booking-product', $this->plugin_info->get_plugin_url() . '/assets/css/inspirelabs-booking-product.css', array(), $this->plugin_info->get_version() . '&' . time() );
					wp_enqueue_script( 'inspirelabs-booking-form', $this->plugin_info->get_plugin_url() . '/assets/js/product/inspirelabs-booking-form.js', array( 'swiper', 'popper', 'tippy' ), $this->plugin_info->get_version(), true );
				}
                // we need this data to show via JS additional fees on product page
				$short_booking_fees = get_option('inspirelabs_bookings_short_booking');

				$translation_array = array(
					'alerttext_1'        => __('Only entire week booking allowed starting from monday', 'inspirelabs-bookings'),
					'alerttext_2'        => __('Only entire week booking allowed starting from tuesday', 'inspirelabs-bookings'),
					'alerttext_3'        => __('Only entire week booking allowed starting from wednesday', 'inspirelabs-bookings'),
					'alerttext_4'        => __('Only entire week booking allowed starting from thursday', 'inspirelabs-bookings'),
					'alerttext_5'        => __('Only entire week booking allowed starting from friday', 'inspirelabs-bookings'),
					'alerttext_6'        => __('Only entire week booking allowed starting from saturday', 'inspirelabs-bookings'),
					'alerttext_7'        => __('Only entire week booking allowed starting from sunday', 'inspirelabs-bookings'),
					'from'               => __('Booking start', 'inspirelabs-bookings'),
					'to'                 => __('Booking end', 'inspirelabs-bookings'),
					'checkin'            => __('Check-in', 'inspirelabs-bookings'),
					'checkout'           => __('Check-out', 'inspirelabs-bookings'),
					'short_booking_text' => __('Short booking fee:', 'inspirelabs-bookings')
				);

				wp_localize_script(
					'inspirelabs-booking-form',
					'booking',
					array(
						'currency'           => get_woocommerce_currency_symbol(),
						'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
						'security'           => wp_create_nonce( 'load_month' ),
						'product'            => get_the_ID(),
						'alerttext'          => $translation_array,
						'short_booking_fees' => $short_booking_fees && is_array( $short_booking_fees ) ? $short_booking_fees : '',
						'pro_mode'           => class_exists('\WPDesk\Bookings\Admin\WC_Product\PriceListDataTabPro')
					)
				);
			}
		}
	}


    /**
     * Remove price for bookable products
     */
    public function remove_price_for_bookable( $price, $product ) {

        if ('booking' === \WC_Product_Factory::get_product_type($product->get_id())) {
            return $price = '';
        }
        return $price;
    }


    /**
     * Replace add-to-cart button behavior for bookable products
     */
    public function change_addtocart_btn_for_bookable(  $args, $product ) {

        if ('booking' === \WC_Product_Factory::get_product_type($product->get_id())) {
            $product_id = $product->get_id();
            $product_sku = $product->get_sku();
            $product_name = $product->get_name();
            $product_url = $product->get_slug();

            $args = '<a 
                        href="' . $product_url . '" 
                        data-quantity="1" 
                        class="button product_type_variable add_to_cart_button" 
                        data-product_id="' . $product_id . '" 
                        data-product_sku="' . $product_sku . '" 
                        aria-label="Select options for ' . $product_name . '" 
                        rel="nofollow">
                        ' . __( 'Select options', 'inspirelabs-bookings' ) . '
                    </a>';
        }
        return $args;
    }

}
