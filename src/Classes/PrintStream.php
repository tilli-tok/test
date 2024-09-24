<?php
declare(strict_types=1);

namespace CleanCode\Classes;

class PrintStream
{

    public function __construct(private $printStream = STDOUT)
    {
    }

    public function println(string $data): void
    {
        fwrite($this->printStream, $data . "\n");
    }

    public function format(string $format, mixed $value): void
    {
        $this->println(sprintf($format, $value));
    }
}