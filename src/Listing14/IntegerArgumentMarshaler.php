<?php

namespace CleanCode\Listing14;

use ArgsException;

class IntegerArgumentMarshaler extends ArgumentMarshaler
{
    private int $intValue = 0;

    /**
     * @throws ArgsException
     */
    public function set(String $s): void
    {
        try {
            $this->intValue = (int) $s;
        } catch (NumberFormatException $e) {
            throw new ArgsException();
        }
    }
    public function get(): int
    {
        return $this->intValue;
    }
}