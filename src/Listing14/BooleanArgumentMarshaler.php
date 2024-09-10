<?php

namespace CleanCode\Listing14;

class BooleanArgumentMarshaler extends ArgumentMarshaler
{
    private bool $booleanValue = false;
    public function set(String $s): void
    {
        $this->booleanValue = true;
    }
    public function get(): bool
    {
        return $this->booleanValue;
    }
}
