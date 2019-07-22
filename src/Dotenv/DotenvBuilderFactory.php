<?php
namespace PoP\Root\Dotenv;

use Symfony\Component\Dotenv\Dotenv;

class DotenvBuilderFactory {
    private static $fileLocation;
    public static function setFileLocation($fileLocation)
    {
        self::$fileLocation = $fileLocation;
    }
    public static function maybeLoadEnvironmentVariables(): void
    {
        // If the file location has been set, then load the environment variables from .env files stored there
        if (self::$fileLocation) {
            $dotenv = new Dotenv();
            $dotenv->loadEnv(self::$fileLocation.'/.env');
        }
    }
}