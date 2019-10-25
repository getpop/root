<?php
namespace PoP\Root\Component;

use PoP\Root\Managers\ComponentManager;
/**
 * Initialize component
 */
abstract class AbstractComponent
{
    /**
     * Indicate if the component is enabled or not. It allows to disable it through environment variables
     *
     * @var boolean
     */
    protected static $enabled;

    /**
     * Initialize services
     */
    public static function init()
    {
        // Define if the component is enabled
        self::initEnabled();
        if (self::isEnabled()) {
            // Register itself in the Manager
            ComponentManager::register(get_called_class());
        }
    }

    protected static function initEnabled()
    {
        self::$enabled = true;
    }

    public static function isEnabled()
    {
        return self::$enabled;
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
