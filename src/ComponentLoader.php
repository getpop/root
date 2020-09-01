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
     *
     * @var string[]
     */
    protected static $initializedClasses = [];

    /**
     * Initialize the PoP components
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     * @param array<string, mixed> $componentClassConfiguration [key]: Component class, [value]: Configuration
     * @param string[] $skipSchemaComponentClasses List of `Component` class to not initialize
     */
    public static function initializeComponents(
        array $componentClasses,
        array $componentClassConfiguration = [],
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
                $componentClassConfiguration,
                $skipSchemaComponentClasses
            );

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::initializeComponents(
                array_filter(
                    $componentClass::getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $componentClassConfiguration,
                $skipSchemaComponentClasses
            );

            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach ($componentClass::getDependedMigrationPlugins() as $migrationPlugin) {
                // All migration plugins must be composed of "owner/package",
                // and have `initialize.php` as entry point
                require_once dirname(__DIR__, 3) . '/' . $migrationPlugin . '/initialize.php';
            }

            // Initialize the component, passing its configuration, and checking if its schema must be skipped
            $componentConfiguration = $componentClassConfiguration[$componentClass] ?? [];
            $skipSchemaForComponent = in_array($componentClass, $skipSchemaComponentClasses);
            $componentClass::initialize(
                $componentConfiguration,
                $skipSchemaForComponent,
                $skipSchemaComponentClasses
            );
        }
    }
}
