<?php
declare(strict_types=1);

namespace CleanCode\Cleansing;

use ArrayIterator;
use Exception;
use Iterator;
use Throwable;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0.0;

    public static function getValue(mixed $param)
    {
    }

    /**
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
        } catch (Throwable) {
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

    public function getDouble(string $arg): float
    {
        $am = $this->marshalers[$arg] ?? null;

        try {
            return $am === null ? 0.0 : (float)$am->get();
        } catch (Exception) {
            return 0.0;
        }
    }
}