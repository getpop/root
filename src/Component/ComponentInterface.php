<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Initialize component
 */
interface ComponentInterface
{
    /**
     * Initialize the component
     */
    public static function initialize(): void;

    /**
     * All component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedComponentClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses(): array;

    // /**
    //  * Initialize services
    //  */
    // public static function init();

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot();

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function boot();

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function afterBoot();
}
