<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\Container\ContainerBuilderFactory;

trait PHPServiceConfigurationTrait
{
    public static function initialize()
    {
        self::initPHPServiceConfiguration();
    }

    public static function initPHPServiceConfiguration()
    {
        // First check if the container has been cached. If so, do nothing
        if (!ContainerBuilderFactory::isCached()) {
            self::configure();
        }
    }

    /**
     * Function called to configure Symfony's services
     *
     * @return void
     */
    protected static function configure()
    {
    }
}
