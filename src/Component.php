<?php
namespace PoP\Root;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Class required to check if this component exists and is active
 */
class Component extends AbstractComponent
{
    /**
     * Indicate if the component is active
     *
     * @var boolean
     */
    public static $active;
    
    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        self::$active = true;

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
    }
}
