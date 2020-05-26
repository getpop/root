<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\Managers\ComponentManager;
use PoP\Root\Component\ComponentInterface;

/**
 * Initialize component
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * Initialize the component
     */
    public static function initialize(): void
    {
        // Initialize the self component
        static::doInitialize();
    }

    /**
     * All component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    abstract public static function getDependedComponentClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [];
    }

    /**
     * All migration plugins that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedMigrationPlugins(): array
    {
        return [];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize(): void
    {
        // Register itself in the Manager
        ComponentManager::register(get_called_class());
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function boot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function afterBoot(): void
    {
    }
}
