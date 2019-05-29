<?php
namespace PoP\Root\Component;

// use Symfony\Component\Config\FileLocator;
// use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
// use PoP\Root\Container\ContainerBuilderFactory;

trait PHPConfigurableServicesTrait
{
    public static function initPHPServiceConfiguration(string $componentDir)
    {
        // // Initialize the ContainerBuilder with this component's service implementations
        // $containerBuilder = ContainerBuilderFactory::getInstance();
        // $loader = new PhpFileLoader($containerBuilder, new FileLocator($componentDir));
        // $loader->load('config/service-configuration.php');
        require_once $componentDir.'/config/service-configuration.php';
    }
}
