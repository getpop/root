<?php
namespace PoP\Root\Container;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerBuilderFactory {
    private static $instance;
    private static $cached;
    private static $cacheFile;
    public static function init($componentDir)
    {
        self::$cacheFile = $componentDir.'/build/cache/container.php';

        // Initialize the services from the cached file
        $isDebug = isset($_ENV['IS_CONFIG_CACHE_DEBUG']) && ($_ENV['IS_CONFIG_CACHE_DEBUG'] == "true");
        $containerConfigCache = new ConfigCache(self::$cacheFile, $isDebug);
        self::$cached = $containerConfigCache->isFresh();

        // If not cached, then create the new instance
        if (!self::$cached) {
            self::$instance = new ContainerBuilder();
        } else {
            require_once self::$cacheFile;
            self::$instance = new \ProjectServiceContainer();
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
            $dir = dirname(self::$cacheFile);
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            file_put_contents(self::$cacheFile, $dumper->dump());

            // Change the permissions so it can be modified by external processes (eg: deployment)
            chmod(self::$cacheFile, 0777);
        }
    }
}
