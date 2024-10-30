<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\PluginTemplate
 */

namespace WPDesk\Bookings;

use BookingsVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\Activateable;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\Deactivateable;
use BookingsVendor\WPDesk_Plugin_Info;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use WPDesk\Bookings\Core\Database;
use WPDesk\Bookings\Frontend\Frontend;
use WPDesk\Bookings\Admin\MainAdmin;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @package WPDesk\PluginTemplate
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection, Deactivateable, Activateable {
	use LoggerAwareTrait;
	use HookableParent;

	const APP_PREFIX = 'inspirelabs-bookings';


	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );
		$this->setLogger( new NullLogger() );
	}



	/**
	 * Create required database tables during plugin activation.
	 */
	public function activate() {
		$database = new Database();
		$database->install();
	}



	/**
	 * Initializes plugin external state.
	 *
	 * The plugin internal state is initialized in the constructor and the plugin should be internally consistent after creation.
	 * The external state includes hooks execution, communication with other plugins, integration with WC etc.
	 *
	 * @return void
	 */
	public function init() {

		parent::init();
		$this->customize_wpdesk_boilerplate();

		$admin = new MainAdmin( $this->plugin_info );
		$admin->hooks();

		$frontend = new Frontend( $this->plugin_info );
		$frontend->hooks();
	}



	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
	}

	/**
	 * Plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate() {
		update_option( Plugin::APP_PREFIX . '_is_registered', false );
	}

	private function customize_wpdesk_boilerplate() {
		add_action( 'admin_menu', function () {
			remove_menu_page( 'wpdesk-helper' );
		}, 100 );

		add_filter( 'wpdesk_show_plugin_activation_notice', function ( $bool ) {
			return false;
		} );

	}

}
