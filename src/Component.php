<?php
namespace PoP\Root;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();

        // Initialize the ContainerBuilder
        ContainerBuilderFactory::init(dirname(__DIR__));
    }
    
    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function boot()
    {
        // Compile and Cache Symfony's DependencyInjection Container Builder
        ContainerBuilderFactory::maybeCompileAndCacheContainer();

        // Load variables from the environment
        DotenvBuilderFactory::maybeLoadEnvironmentVariables();
    }
}
