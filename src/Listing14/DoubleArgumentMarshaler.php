<?php
namespace CleanCode\Listing14;

use Iterator;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0.0;

    /**
     * @throws ArgsException
     */

    public function set(Iterator $currentArgument): void
    {
        $parameter = $currentArgument->current();
        try {
            if (!$currentArgument->valid()) {
                throw new ArgsException(ErrorCode::MISSING_DOUBLE, null);
            }

            if (!is_numeric($parameter)) {
                throw new NumberFormatException("Invalid double format: $parameter");
            }

            $this->doubleValue = (float)$parameter;
            $currentArgument->next();
        } catch (NumberFormatException) {
            throw new ArgsException(ErrorCode::INVALID_DOUBLE, null, $parameter);
        }
    }

    public function get(): float
    {
        return $this->doubleValue;
    }
}