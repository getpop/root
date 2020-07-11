<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use InvalidArgumentException;
use PoP\Root\Component\Configuration;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerBuilderFactory
{
    private static $instance;
    private static $cached;
    private static $cacheFile;

    /**
     * Initialize the Container Builder.
     * If the directory is not provided, store the cache in a system temp dir
     *
     * @param string|null $directory directory where to store the cache
     * @param string|null $namespace subdirectory under which to store the cache
     * @return void
     */
    public static function init(?string $namespace = null, ?string $directory = null)
    {
        /**
         * Code copied from Symfony FilesystemAdapter
         * @see https://github.com/symfony/cache/blob/master/Traits/FilesystemCommonTrait.php
         */
        if (!$directory) {
            $directory = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'pop' . \DIRECTORY_SEPARATOR . 'container-cache';
        }
        if ($namespace) {
            if (preg_match('#[^-+_.A-Za-z0-9]#', $namespace, $match)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Namespace contains "%s" but only characters in [-+_.A-Za-z0-9] are allowed.',
                        $match[0]
                    )
                );
            }
            $directory .= \DIRECTORY_SEPARATOR . $namespace;
        }
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }
        $directory .= \DIRECTORY_SEPARATOR;
        // On Windows the whole path is limited to 258 chars
        if ('\\' === \DIRECTORY_SEPARATOR && \strlen($directory) > 234) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cache directory too long (%s).',
                    $directory
                )
            );
        }

        // Store the cache under this file
        self::$cacheFile = $directory . 'container.php';

        // Initialize the services from the cached file
        $isDebug = !Configuration::cacheContainerConfiguration();
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
        // After compiling, cache it in disk for performance.
        // This happens only the first time the site is accessed on the current server
        if (!self::$cached) {
            // Create the folder if it doesn't exist, and check it was successful
            $dir = dirname(self::$cacheFile);
            $folderExists = file_exists($dir);
            if (!$folderExists) {
                $folderExists = @mkdir($dir, 0777, true);
            }
            if ($folderExists) {
                // Compile the container and save it to disk
                $containerBuilder = self::getInstance();
                $containerBuilder->compile();
                $dumper = new PhpDumper($containerBuilder);
                file_put_contents(self::$cacheFile, $dumper->dump());

                // Change the permissions so it can be modified by external processes (eg: deployment)
                chmod(self::$cacheFile, 0777);
            }
        }
    }
}
