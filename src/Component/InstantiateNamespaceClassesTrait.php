<?php
namespace PoP\Root\Component;

use PoP\Root\Bootloader\InstantiateNamespaceClasses;

trait InstantiateNamespaceClassesTrait
{
    public static function instantiateNamespaceClasses(array $namespaces)
    {
        InstantiateNamespaceClasses::addNamespaces($namespaces);
    }
}
