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
     * Initialize the component
     */
    public static function initialize(array $componentClasses): void
    {
        foreach ($componentClasses as $componentClass) {
            if (!in_array($componentClass, self::$initializedClasses)) {
                self::$initializedClasses[] = $componentClass;

                // Initialize all depended-upon PoP components
                self::initialize($componentClass::getDependedComponentClasses());

                // Initialize all depended-upon PoP conditional components, if they are installed
                self::initialize(array_map(
                    'class_exists',
                    $componentClass::getDependedConditionalComponentClasses()
                ));

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
}
