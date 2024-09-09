<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use ArrayIterator;
use Iterator;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0.0;

    public static function getValue(mixed $param)
    {
    }

    /**
     * @param ArrayIterator|Iterator $currentArgument
     * @throws ArgsException
     */
    public function set(ArrayIterator|Iterator $currentArgument): void
    {
        $parameter = null;

        try {
            if ($currentArgument->valid()) {
                $parameter = $currentArgument->current();
                $this->doubleValue = (float)$parameter;
                $currentArgument->next();
            } else {
                throw new ArgsException(ErrorCode::MISSING_DOUBLE);
            }
        } catch (\Throwable $e) {
            if ($parameter === null) {
                throw new ArgsException(ErrorCode::MISSING_DOUBLE);
            }

            throw new ArgsException(ErrorCode::INVALID_DOUBLE, '', $parameter);
        }
    }

    public function get(): float
    {
        return $this->doubleValue;
    }
}