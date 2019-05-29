<?php
namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerBuilderFactory {
    private static $instance;
    private static $cached;
    private static $cacheFile;
    public static function init($componentDir)
    {
        self::$cacheFile = $componentDir.'/build/cache/container.php';

        // $file = \PoP\Root\Component::DIR.'/build/cache/container.php';
        // Initialize the services from the cached file
        self::$cached = file_exists(self::$cacheFile);
        if (self::$cached) {
            require_once self::$cacheFile;
            self::$instance = new \ProjectServiceContainer();
        } else {
            self::$instance = new ContainerBuilder();
        }
    }
    public static function getInstance()
    {
        return self::$instance;
    }
    public static function isCached()
    {
        return self::$cached;
    }

    public static function maybeCompileAndCacheContainer(): void
    {
        // Compile Symfony's DependencyInjection Container Builder
        // After compiling, cache it in disk for performance. This happens only the first time the site is accessed on the current server
        if (!self::$cached) {
            $containerBuilder = self::getInstance();            // ...
            $containerBuilder->compile();
            $dumper = new PhpDumper($containerBuilder);
            @mkdir(dirname(self::$cacheFile), 0777, true);
            file_put_contents(self::$cacheFile, $dumper->dump());
        }
    }
}