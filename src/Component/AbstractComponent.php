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
     * Has the component been initialized?
     */
    public static $initializedClasses = [];

    /**
     * Initialize the component
     */
    public static function initialize(): void
    {
        if (!in_array(get_called_class(), static::$initializedClasses)) {
            static::$initializedClasses[] = get_called_class();
            
            // Initialize all depended-upon PoP components
            foreach (static::getDependedComponentClasses() as $componentClass) {
                $componentClass::initialize();
            }

            // Initialize all depended-upon PoP conditional components, if they are installed
            foreach (static::getDependedConditionalComponentClasses() as $componentClass) {
                if (\class_exists($componentClass)) {
                    $componentClass::initialize();
                }
            }

            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach (static::getDependedMigrationPlugins() as $migrationPlugin) {
                // All migration plugins go under /getpop, and have `initialize.php` as entry point
                // var_dump(basename(basename(basename(__DIR__))) . '/getpop/' . $migrationPlugin . '/initialize.php');die;
                require_once basename(basename(basename(__DIR__))) . '/getpop/' . $migrationPlugin . '/initialize.php';
            }
            
            // Initialize the self component
            static::init();
        }
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
