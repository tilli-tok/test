<?php

namespace CleanCode\Listing14\Marshaler\Argument;

use CleanCode\Listing14\ErrorCode;
use CleanCode\Listing14\Marshaler\NumericArgumentMarshaler;

class IntegerArgument extends NumericArgumentMarshaler
{
    private int $intValue = 0;

    protected function setTypedParameter(mixed $parameter): void
    {
        $this->intValue = (int)$parameter;
    }

    protected function getInvalidErrorCode(): ?ErrorCode
    {
        return ErrorCode::INVALID_INTEGER;
    }


    protected function getMissingErrorCode(): ErrorCode
    {
        return ErrorCode::MISSING_INTEGER;
    }

    protected function getNumberFormatMessage(string $parameter): string
    {
        return "Invalid integer format: $parameter";
    }


    public function get(): int
    {
        return $this->intValue;
    }
}
