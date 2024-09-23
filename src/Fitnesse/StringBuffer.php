<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class StringBuffer extends \Stringable
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