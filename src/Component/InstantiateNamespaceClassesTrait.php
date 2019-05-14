<?php
namespace PoP\Root\Component;

use HaydenPierce\ClassFinder\ClassFinder;

trait InstantiateNamespaceClassesTrait
{
    public static function instantiateNamespaceClasses(array $namespaces)
    {
        foreach ($namespaces as $namespace) {
            $classes = ClassFinder::getClassesInNamespace($namespace, ClassFinder::RECURSIVE_MODE);
            foreach ($classes as $class) {
                new $class();
            }
        }
    }
}
