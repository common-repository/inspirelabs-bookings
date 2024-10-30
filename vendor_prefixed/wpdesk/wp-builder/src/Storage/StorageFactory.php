<?php

namespace BookingsVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \BookingsVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
