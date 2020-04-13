<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

/**
 * Keep a reference to all Components
 */
class ComponentManager
{
    private static $components = [];

    /**
     * Register a component
     */
    public static function register(string $component)
    {
        self::$components[] = $component;
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function beforeBoot()
    {
        foreach (self::$components as $component) {
            $component::beforeBoot();
        }
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function boot()
    {
        foreach (self::$components as $component) {
            $component::boot();
        }
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function afterBoot()
    {
        foreach (self::$components as $component) {
            $component::afterBoot();
        }
    }
}
