<?php
namespace CleanCode\Listing14;

use Exception;
use ArrayIterator;
use Iterator;

class StringArgumentMarshaler implements ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @throws ArgsException
     */
    public function set(ArrayIterator|Iterator $currentArgument): void
    {
        try {
            $this->stringValue = $currentArgument->current();
            $currentArgument->next();
        } catch (Exception) {
            throw new ArgsException(ErrorCode::MISSING_STRING, null);
        }
    }

    /**
    try {
    $this->stringValue = $currentArgument->next();
    } catch (NoSuchElementException $e) {
    $errorCode = ErrorCode::MISSING_STRING;
    throw new ArgsException($errorCode);
    }*/
    /**public function set(string $s): void
    {
        $this->stringValue = $s;
    }*/
    public function get(): string
    {
        return $this->stringValue;
    }
}