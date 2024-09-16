<?php
namespace CleanCode\Listing14\Marshaler\Argument;

use CleanCode\Listing14\ErrorCode;
use CleanCode\Listing14\Marshaler\NumericArgumentMarshaler;

class DoubleArgument extends NumericArgumentMarshaler
{
    private float $doubleValue = 0.0;

    protected function setTypedParameter(mixed $parameter): void
    {
        $this->doubleValue = (float)$parameter;
    }

    protected function getInvalidErrorCode(): ?ErrorCode
    {
        return ErrorCode::INVALID_DOUBLE;
    }

    protected function getMissingErrorCode(): ErrorCode
    {
        return ErrorCode::MISSING_DOUBLE;
    }

    protected function getNumberFormatMessage(string $parameter): string
    {
        return "Invalid double format: $parameter";
    }

    public function get(): float
    {
        return $this->doubleValue;
    }
}
