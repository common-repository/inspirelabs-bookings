<?php

namespace WPDesk\Bookings\Frontend;

/**
 * Main Frontend class
 *
 * @package WPDesk\PluginTemplate
 */
class Frontend {

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
		add_action( 'init', array( new SingleProduct( $this->plugin_info ), 'hooks' ) );
		add_action( 'init', array( new Order($this->plugin_info), 'hooks' ) );
	}

}
