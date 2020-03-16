<?php
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
    public static function earlyBoot()
    {
        foreach (self::$components as $component) {
            $component::earlyBoot();
        }
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function reallyBoot()
    {
        foreach (self::$components as $component) {
            $component::reallyBoot();
        }
    }
}
