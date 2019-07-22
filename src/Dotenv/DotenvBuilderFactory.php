<?php
namespace PoP\Root\Dotenv;

use Symfony\Component\Dotenv\Dotenv;

class DotenvBuilderFactory {
    private static $fileLocation;
    /**
     * Initialize the file location to the document root
     *
     * @return void
     */
    public static function init()
    {
        self::setFileLocation($_SERVER['DOCUMENT_ROOT'].'/config');
    }
    /**
     * Override the folder from where to get the .env files
     *
     * @param [type] $fileLocation
     * @return void
     */
    public static function setFileLocation($fileLocation)
    {
        self::$fileLocation = $fileLocation;
    }
    public static function maybeLoadEnvironmentVariables(): void
    {
        // If the file location has been set, then load the environment variables from .env files stored there
        if (self::$fileLocation && file_exists(self::$fileLocation.'/.env')) {
            $dotenv = new Dotenv();
            $dotenv->loadEnv(self::$fileLocation.'/.env');
        }
    }
}