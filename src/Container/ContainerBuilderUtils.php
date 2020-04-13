<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use PoP\Root\Container\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\Reference;

class ContainerBuilderUtils
{
    /**
     * Get all services located under the specified namespace
     * It requires the classes to be exposed as services in file services.yaml, using their own class as the service ID, like this:
     *
     * PoP\ComponentModel\FieldResolvers\:
     *     resource: '../src/FieldResolvers/*'
     *     public: true
     *
     * @param string $namespace
     * @param bool $includeSubfolders indicate if not include the classes from the subnamespaces
     * @return array list of services ids defined in the container
     */
    public static function getServiceClassesUnderNamespace(string $namespace, bool $includeSubfolders = true): array
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();

        // Make sure the namespace ends with "\"
        if (substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }

        // Obtain all services whose definition id start with the given namespace
        return array_filter(
            $containerBuilder->getServiceIds(),
            function ($class) use ($namespace, $includeSubfolders) {
                return
                    // id starts with namespace, and...
                    strpos($class, $namespace) === 0 &&
                    (
                        // include subfolders, or...
                        $includeSubfolders ||
                        // exclude classes which still have another "\"
                        strpos($class, '\\', strlen($namespace)) === false
                    );
            }
        );
    }

    /**
     * Initialize a specific class
     *
     * @param string $namespace
     * @return void
     */
    public static function instantiateService(string $serviceClass): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $containerBuilder->get($serviceClass);
    }

    /**
     * Initialize all services located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function instantiateNamespaceServices(string $namespace, bool $includeSubfolders = true): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
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
        string $methodCall,
        bool $includeSubfolders = true
    ): void {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $definition = $containerBuilder->getDefinition($injectableServiceId);
        $injectingServiceClasses = self::getServiceClassesUnderNamespace($injectingServicesNamespace, $includeSubfolders);
        foreach ($injectingServiceClasses as $injectingServiceClassId) {
            $definition->addMethodCall($methodCall, [new Reference($injectingServiceClassId)]);
        }
    }

    /**
     * Inject some service into another service
     *
     * @param string $injectableServiceId
     * @param string $injectingServiceId
     * @param string $methodCall
     * @return void
     */
    public static function injectServiceIntoService(
        string $injectableServiceId,
        string $injectingServiceId,
        string $methodCall
    ): void {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $definition = $containerBuilder->getDefinition($injectableServiceId);
        $definition->addMethodCall($methodCall, [new Reference($injectingServiceId)]);
    }

    /**
     * Inject some value into a service
     *
     * @param string $injectableServiceId
     * @param string $value
     * @param string $methodCall
     * @return void
     */
    public static function injectValuesIntoService(
        string $injectableServiceId,
        string $methodCall,
        ...$values
    ): void {
        // If any value is a string starting with '@', then it's a reference to a service!
        $values = array_map(
            function ($value) {
                if ($value && is_string($value) && substr($value, 0, 1) == '@') {
                    // Return a reference to the service
                    $injectedServiceId = substr($value, 1);
                    return new Reference($injectedServiceId);
                }
                return $value;
            },
            $values
        );
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $definition = $containerBuilder->getDefinition($injectableServiceId);
        $definition->addMethodCall($methodCall, $values);
    }
}
