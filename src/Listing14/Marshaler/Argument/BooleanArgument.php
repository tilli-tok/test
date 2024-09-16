<?php
declare(strict_types=1);
namespace CleanCode\Listing14\Marshaler\Argument;

use CleanCode\Listing14\Marshaler\ArgumentMarshaler;

class BooleanArgument extends ArgumentMarshaler
{
    private bool $booleanValue = false;
    public function get(): bool
    {
        return $this->booleanValue;
    }

    protected function setTypedParameter(mixed $parameter): void
    {
        $this->booleanValue = true;
    }
}
