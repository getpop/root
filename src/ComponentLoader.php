<?php

declare(strict_types=1);

namespace PoP\Root;

/**
 * Component Loader
 */
class ComponentLoader
{
    /**
     * Has the component been initialized?
     */
    protected static $initializedClasses = [];

    /**
     * Initialize the PoP components
     *
     * @param array $componentClasses List of `Component` class to initialize
     * @param array $ignoreComponentClasses List of `Component` class to not initialize
     * @return void
     */
    public static function initializeComponents(
        array $componentClasses,
        array $ignoreComponentClasses = []
    ): void {
        /**
         * If any component class is on the ignore list, or has already been initialized,
         * then do not initialize it
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            $ignoreComponentClasses,
            self::$initializedClasses
        ));
        foreach ($componentClasses as $componentClass) {
            self::$initializedClasses[] = $componentClass;

            // Initialize all depended-upon PoP components
            self::initializeComponents(
                $componentClass::getDependedComponentClasses(),
                $ignoreComponentClasses
            );

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::initializeComponents(
                array_filter(
                    $componentClass::getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $ignoreComponentClasses
            );

            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach ($componentClass::getDependedMigrationPlugins() as $migrationPlugin) {
                // All migration plugins go under /getpop, and have `initialize.php` as entry point
                require_once dirname(dirname(dirname(__DIR__))) . '/getpop/' . $migrationPlugin . '/initialize.php';
            }

            // Initialize the component
            $componentClass::initialize();
        }
    }
}
