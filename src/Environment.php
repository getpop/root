<?php

declare(strict_types=1);

namespace PoP\Root;

class Environment
{
    public const CACHE_CONTAINER_CONFIGURATION = 'CACHE_CONTAINER_CONFIGURATION';
    public const CONTAINER_CONFIGURATION_CACHE_NAMESPACE = 'CONTAINER_CONFIGURATION_CACHE_NAMESPACE';

    /**
     * Indicate if to cache the container configuration.
     * Using `getenv` instead of $_ENV because this latter one, somehow, doesn't work yet:
     * Because this code is executed to know from where to load the container configuration,
     * this env variable can't be configured using the .env file, must must be injected
     * straight into the webserver.
     * By default, do not cache, since that's the conservative approach that always works.
     * Otherwise, if caching, newly installed modules (eg: on WordPress plugin) may not work
     *
     * @return boolean
     */
    public static function cacheContainerConfiguration(): bool
    {
        // If the environment variable is not set, `getenv` returns the boolean `false`
        // Otherwise, it returns the string value
        $useCache = getenv(self::CACHE_CONTAINER_CONFIGURATION);
        return $useCache !== false ? strtolower($useCache) == "true" : false;
    }

    public static function getCacheContainerConfigurationNamespace(): ?string
    {
        return $_ENV[self::CONTAINER_CONFIGURATION_CACHE_NAMESPACE];
    }
}
