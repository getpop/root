<?php
namespace PoP\Root\Component;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PoP\Root\Container\ContainerBuilderFactory;

trait YAMLServicesTrait
{
    public static function initYAMLServices(string $componentDir, string $configPath = '', string $fileName = 'services.yaml')
    {
        // First check if the container has been cached. If so, do nothing
        if (!ContainerBuilderFactory::isCached()) {
            // Initialize the ContainerBuilder with this component's service implementations
            $containerBuilder = ContainerBuilderFactory::getInstance();
            $componentPath = $componentDir.'/config'.($configPath ? '/'. trim($configPath, '/') : '');
            $loader = new YamlFileLoader($containerBuilder, new FileLocator($componentPath));
            $loader->load($fileName);
        }
    }
}
