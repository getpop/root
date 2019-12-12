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
            $debugEnvValue = getenv('IS_CONFIG_CACHE_DEBUG');
            self::$configCacheDebug = $debugEnvValue && strtolower($debugEnvValue) == "true";
        }
        return self::$configCacheDebug;
    }
}
