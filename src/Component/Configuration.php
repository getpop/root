<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Component configuration
 */
class Configuration
{
    private static $cacheContainerConfig;
    /**
     * Initialize services
     */
    public static function cacheContainerConfiguration()
    {
        if (is_null(self::$cacheContainerConfig)) {
            /**
             * Using `getenv` instead of $_ENV because this latter one, somehow, doesn't work yet:
             * Because this code is executed to know from where to load the container configuration,
             * this env variable can't be configured using the .env file, must must be injected
             * straight into the webserver.
             * By default, do not cache, since that's the conservative approach that always works.
             * Otherwise, if caching, newly installed modules (eg: on WordPress plugin) may not work
             */
            $useCache = getenv('CACHE_CONTAINER_CONFIGURATION');
            self::$cacheContainerConfig = $useCache !== false ? strtolower($useCache) == "true" : false;
        }
        return self::$cacheContainerConfig;
    }
}
