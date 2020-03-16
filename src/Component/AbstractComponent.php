<?php
namespace PoP\Root\Component;

use PoP\Root\Managers\ComponentManager;
/**
 * Initialize component
 */
abstract class AbstractComponent
{
    /**
     * Initialize services
     */
    public static function init()
    {
        // Register itself in the Manager
        ComponentManager::register(get_called_class());
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot()
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function boot()
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function afterBoot()
    {
    }
}
