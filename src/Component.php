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
    protected static function doInitialize(bool $skipSchema = false): void
    {
        parent::doInitialize($skipSchema);

        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        // Initialize the ContainerBuilder
        ContainerBuilderFactory::init(dirname(__DIR__));
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
