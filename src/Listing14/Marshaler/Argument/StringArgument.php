<?php

namespace CleanCode\Listing14\Marshaler\Argument;

use CleanCode\Listing14\ErrorCode;
use CleanCode\Listing14\Marshaler\ArgumentMarshaler;

class StringArgument extends ArgumentMarshaler
{
    private string $stringValue = '';

    protected function setTypedParameter(mixed $parameter): void
    {
        $this->stringValue = (string)$parameter;
    }

    protected function getMissingErrorCode(): ErrorCode
    {
        return ErrorCode::MISSING_STRING;
    }

    public function get(): string
    {
        return $this->stringValue;
    }
}
