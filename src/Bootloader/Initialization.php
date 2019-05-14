<?php
namespace PoP\Root\Bootloader;

class Initialization
{
    public static function init()
    {
        InstantiateNamespaceClasses::init();
    }
}
