<?php

declare(strict_types=1);

namespace PoP\Root\Component;

trait CanDisableComponentTrait
{
    protected static ?bool $enabled = null;

    protected static function resolveEnabled(): bool
    {
        return true;
    }

    public static function isEnabled()
    {
        // This is needed for if asking if this component is enabled before it has been initialized
        if (is_null(self::$enabled)) {
            self::$enabled = self::resolveEnabled();
        }
        return self::$enabled;
    }
}
