<?php
declare(strict_types=1);

namespace CleanCode\Classes;

class PrintStream
{
    private $printStream;

    public function __construct($printStream = STDOUT)
    {
        $this->printStream = $printStream;
    }

    public function write(string $data): void
    {
        fwrite($this->printStream, $data);
    }
}