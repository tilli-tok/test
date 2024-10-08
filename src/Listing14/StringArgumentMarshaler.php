<?php

namespace CleanCode\Listing14;

use ArrayIterator;
use Exception;
use Iterator;

class StringArgumentMarshaler implements ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @throws ArgsException
     * {@inheritDoc}
     */
    public function set(ArrayIterator|Iterator $currentArgument): void
    {
        try {
            if (!$currentArgument->valid()) {
                throw new ArgsException(ErrorCode::MISSING_STRING);
            }
            $parameter = $currentArgument->current();
            $this->stringValue = (string)$parameter;
            $currentArgument->next();
        } catch (Exception) {
            throw new ArgsException(ErrorCode::MISSING_STRING);
        }

    }

    public function get(): string
    {
        return $this->stringValue;
    }
}