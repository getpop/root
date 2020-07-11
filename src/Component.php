<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [];
    }
    /**
     * Initialize services
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);

        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        // Initialize the ContainerBuilder
        // Provide a namespace, but not a directory (then it will use a system temp folder)
        $namespace =
            $configuration[Environment::CACHE_CONTAINER_CONFIGURATION_NAMESPACE] ??
            Environment::getCacheContainerConfigurationNamespace();
        // $directory = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'build' . \DIRECTORY_SEPARATOR . 'cache';
        ContainerBuilderFactory::init($namespace/*, $directory*/);
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        // Compile and Cache Symfony's DependencyInjection Container Builder
        ContainerBuilderFactory::maybeCompileAndCacheContainer();
    }
}
