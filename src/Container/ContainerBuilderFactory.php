<?php
namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerBuilderFactory {
    private static $instance;
    public static function init()
    {
        self::$instance = new ContainerBuilder();
    }
    public static function getInstance()
    {
        return self::$instance;
    }
}