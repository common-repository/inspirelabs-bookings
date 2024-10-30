<?php

namespace BookingsVendor\WPDesk\Helper\Integration;

use BookingsVendor\WPDesk\Helper\Page\SettingsPage;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use BookingsVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
/**
 * Integrates WP Desk main settings page with WordPress
 *
 * @package WPDesk\Helper
 */
class SettingsIntegration implements \BookingsVendor\WPDesk\PluginBuilder\Plugin\Hookable, \BookingsVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /** @var SettingsPage */
    private $settings_page;
    public function __construct(\BookingsVendor\WPDesk\Helper\Page\SettingsPage $settingsPage)
    {
        $this->add_hookable($settingsPage);
    }
    /**
     * @return void
     */
    public function hooks()
    {
        $this->hooks_on_hookable_objects();
    }
}
