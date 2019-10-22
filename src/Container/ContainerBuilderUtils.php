<?php
namespace PoP\Root\Container;

use PoP\Root\Container\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\Reference;

class ContainerBuilderUtils {

    /**
     * Get all services located under the specified namespace
     * It requires the classes to be exposed as services in file services.yaml, using their own class as the service ID, like this:
     *
     * PoP\ComponentModel\FieldValueResolvers\:
     *     resource: '../src/FieldValueResolvers/*'
     *     public: true
     *
     * @param string $namespace
     * @return array list of services ids defined in the container
     */
    public static function getServiceClassesUnderNamespace(string $namespace): array
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();

        // Make sure the namespace ends with "\"
        if (substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }

        // Obtain all services whose definition id start with the given namespace
        return array_filter(
            $containerBuilder->getServiceIds(),
            function($class) use($namespace) {
                return strpos($class, $namespace) === 0;
            }
        );
    }

    /**
     * Initialize all services located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function instantiateNamespaceServices(string $namespace): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        foreach (self::getServiceClassesUnderNamespace($namespace) as $serviceClass) {
            $containerBuilder->get($serviceClass);
        }
    }

    /**
     * Inject all services located under a given namespace into another service
     *
     * @param string $injectableServiceId
     * @param string $injectingServicesNamespace
     * @param string $methodCall
     * @return void
     */
    public static function injectServicesIntoService(
        string $injectableServiceId,
        string $injectingServicesNamespace,
        string $methodCall
    ): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $definition = $containerBuilder->getDefinition($injectableServiceId);
        $injectingServiceClasses = self::getServiceClassesUnderNamespace($injectingServicesNamespace);
        foreach ($injectingServiceClasses as $injectingServiceClassId) {
            $definition->addMethodCall($methodCall, [new Reference($injectingServiceClassId)]);
        }
    }
}
