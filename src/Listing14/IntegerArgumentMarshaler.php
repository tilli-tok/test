<?php

namespace CleanCode\Listing14;


class IntegerArgumentMarshaler extends ArgumentMarshaler
{
    private int $intValue = 0;

    /**
     * @throws ArgsException
     */
    public function set(string $s): void
    {
        if (!is_numeric($s)) {
            throw new ArgsException(ErrorCode::INVALID_INTEGER, 'p', $s);
        }
        $this->intValue = (int) $s;
    }

    public function get(): int
    {
        return $this->intValue;
    }
}