<?php
namespace PoP\Root\Component;

/**
 * Component configuration
 */
class Configuration
{
    private static $configCacheDebug;
    /**
     * Initialize services
     */
    public static function isConfigCacheDebug()
    {
        if (is_null(self::$configCacheDebug)) {
            self::$configCacheDebug = $_ENV['IS_CONFIG_CACHE_DEBUG'] == "true";
        }
        return self::$configCacheDebug;
    }
}
