<?php
namespace CleanCode\Listing14;

use ArrayIterator;
use Exception;
use Iterator;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0.0;

    /**
     * @throws ArgsException
     */

    public function set(ArrayIterator|Iterator $currentArgument): void
    {
        $parameter = null;
        try {
            $parameter = $currentArgument->current();
            $this->doubleValue = (float)$parameter;
            $currentArgument->next();
        } catch (Exception) {
            if (empty($parameter)) {
                throw new ArgsException(ErrorCode::MISSING_DOUBLE, null);
            } else {
                throw new ArgsException(ErrorCode::INVALID_DOUBLE, $parameter);
            }
        }
    }

    public function get(): float
    {
        return $this->doubleValue;
    }
}