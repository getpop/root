<?php
namespace PoP\Root\Component;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PoP\Root\Container\ContainerBuilderFactory;

trait ConfigurableServicesTrait
{
    public static function initServiceConfiguration(string $componentDir)
    {
        // Initialize the ContainerBuilder with this component's service implementations
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator($componentDir));
        $loader->load('config/services.yaml');
    }
}
