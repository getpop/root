<?php
namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerBuilderFactory {
    private static $containerBuilder;
    public static function getInstance()
    {
        if (is_null(self::$containerBuilder)) {
            self::$containerBuilder = new ContainerBuilder();
        }
        return self::$containerBuilder;
    }
}