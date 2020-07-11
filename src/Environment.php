<?php

declare(strict_types=1);

namespace PoP\Root;

class Environment
{
    public const CACHE_CONTAINER_CONFIGURATION_NAMESPACE = 'CACHE_CONTAINER_CONFIGURATION_NAMESPACE';

    public static function getCacheContainerConfigurationNamespace(): ?string
    {
        return $_ENV[self::CACHE_CONTAINER_CONFIGURATION_NAMESPACE];
    }
}
