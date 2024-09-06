<?php
declare(strict_types=1);
namespace CleanCode\Сleansing;

use CleanCode\Cleansing\ArgumentMarshaler;
use Iterator;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0.0;

    public function set(Iterator $currentArgument): void
    {
        $parameter = null;
    }

    public static function getValue(?ArgumentMarshaler $am): float
    {
        if ($am !== null && $am instanceof DoubleArgumentMarshaler) {
            return $am->doubleValue;
        } else {
            return 0.0;
        }
    }
}
