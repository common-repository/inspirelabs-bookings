<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\PluginTemplate
 */
class WC_Product_Booking extends \WC_Product_Simple {

	public function __construct( $product ) {
		$this->product_type = 'booking';
		parent::__construct( $product );
	}

	public function get_type() {
		return 'booking';
	}

    public function is_purchasable() {
		return true;
	}
}
