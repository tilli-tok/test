<?php

namespace CleanCode\Listing14;

class StringArgumentMarshaler extends ArgumentMarshaler
{
    private string $stringValue = "";
    public function set(String $s): void
    {
        $this->stringValue = $s;
    }
    public function get(): string
    {
        return $this->stringValue;
    }
}