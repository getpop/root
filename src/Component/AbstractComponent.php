<?php
namespace PoP\Root\Component;

use PoP\Root\Managers\ComponentManager;
/**
 * Class required to check if this component exists and is active
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
    public static function boot()
    {
    }
}
