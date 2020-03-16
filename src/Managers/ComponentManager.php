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
    public static function prematureBoot()
    {
        foreach (self::$components as $component) {
            $component::prematureBoot();
        }
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function timelyBoot()
    {
        foreach (self::$components as $component) {
            $component::timelyBoot();
        }
    }

    /**
     * Boot all components
     *
     * @return void
     */
    public static function lateBoot()
    {
        foreach (self::$components as $component) {
            $component::lateBoot();
        }
    }
}
