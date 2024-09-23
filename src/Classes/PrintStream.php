<?php
declare(strict_types=1);

namespace CleanCode\Classes;

readonly class PrintStream
{

    public function __construct(private false $printStream = STDOUT)
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