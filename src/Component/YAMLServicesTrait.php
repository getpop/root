<?php
namespace PoP\Root\Component;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PoP\Root\Container\ContainerBuilderFactory;

trait YAMLServicesTrait
{
    public static function initYAMLServices(string $componentDir)
    {
        // First check if the container has been cached. If so, do nothing
        if (!ContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            $containerBuilder = ContainerBuilderFactory::getInstance();
            $loader = new YamlFileLoader($containerBuilder, new FileLocator($componentDir));
            $loader->load('config/services.yaml');
        }
    }
}
