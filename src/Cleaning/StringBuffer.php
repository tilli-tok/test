<?php

namespace CleanCode\Cleaning;

class StringBuffer implements \Stringable
{
    private string $buffer = '';

    public function append(string $text): StringBuffer
    {
        $this->buffer .= $text;
        return $this;
    }

    public function __toString(): string
    {
        return $this->buffer;
    }
}