<?php
declare(strict_types=1);

namespace CleanCode\Listing14\Marshaler;

use CleanCode\Listing14\NumberFormatException;

abstract class NumericArgumentMarshaler extends ArgumentMarshaler
{

    protected function getNumberFormatMessage(string $parameter): ?string
    {
        return null;
    }

    /**
     * @throws NumberFormatException
     */
    protected function validateParameter(mixed $parameter): void
    {
        if (!is_numeric($parameter)) {
            throw new NumberFormatException($this->getNumberFormatMessage($parameter));
        }
    }
}
