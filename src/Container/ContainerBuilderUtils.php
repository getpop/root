<?php
namespace PoP\Root\Container;

use PoP\Root\Container\ContainerBuilderFactory;

class ContainerBuilderUtils {
    
    /**
     * Get all services located under the specified namespace
     *
     * @param string $namespace
     * @return array list of services ids defined in the container
     */
    public static function getNamespaceServiceIds(string $namespace): array
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

        foreach (self::getNamespaceServiceIds($namespace) as $serviceId) {
            $containerBuilder->get($serviceId);
        }
    }
}