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
     * @param array $skipSchemaComponentClasses List of `Component` class to not initialize
     * @return void
     */
    public static function initializeComponents(
        array $componentClasses,
        array $skipSchemaComponentClasses = []
    ): void {
        /**
         * If any component class has already been initialized,
         * then do nothing
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            self::$initializedClasses
        ));
        foreach ($componentClasses as $componentClass) {
            self::$initializedClasses[] = $componentClass;

            // Initialize all depended-upon PoP components
            self::initializeComponents(
                $componentClass::getDependedComponentClasses(),
                $skipSchemaComponentClasses
            );

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::initializeComponents(
                array_filter(
                    $componentClass::getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $skipSchemaComponentClasses
            );

            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach ($componentClass::getDependedMigrationPlugins() as $migrationPlugin) {
                // All migration plugins go under /getpop, and have `initialize.php` as entry point
                require_once dirname(dirname(dirname(__DIR__))) . '/getpop/' . $migrationPlugin . '/initialize.php';
            }

            // Initialize the component, checking if its schema must be skipped
            $skipSchemaForComponent = in_array($componentClass, $skipSchemaComponentClasses);
            $componentClass::initialize($skipSchemaForComponent);
        }
    }
}
