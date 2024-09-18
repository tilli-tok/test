<?php
declare(strict_types=1);

namespace CleanCode\PHPUnit;

class IntermediateVersion
{
    private const ELLIPSIS = "...";
    private const DELTA_END = "]";
    private const DELTA_START = "[";
    private int $prefix = 0;
    private int $suffix = 0;

    public function __construct(
        private readonly int     $contextLength,
        private readonly ?string $expected,
        private readonly ?string $actual)
    {
    }

    public function compact(string $message): string
    {
        if ($this->shouldNotCompact()) {
            return $this->format($message, $this->expected, $this->actual);
        }
        $this->findCommonPrefix();
        $this->findCommonSuffix();

        $expected = $this->compactString($this->expected);
        $actual = $this->compactString($this->actual);
        return $this->format($message, $expected, $actual);
    }
    private function shouldNotCompact(): bool
    {
        return $this->expected === null || $this->actual === null || $this->areStringsEqual();
    }

    private function compactString(string $source): string
    {
        $start = $this->prefix;
        $length = strlen($source) - $this->suffix + 1;

        $subst = $this->substring($source, $start, $length);

        $result = self::DELTA_START .
            $subst .
            self::DELTA_END;

        if ($this->prefix > 0) {
            $result = $this->computeCommonPrefix() . $result;
        }

        if ($this->suffix > 0) {
            $result .= $this->computeCommonSuffix();
        }

        return $result;

    }

    private function findCommonPrefix(): void
    {
        $this->prefix = 0;
        $end = min(strlen($this->expected), strlen($this->actual));

        for (; $this->prefix < $end; $this->prefix++) {
            if ($this->expected[$this->prefix] !== $this->actual[$this->prefix]) {
                break;
            }
        }
    }

    private function findCommonSuffix(): void
    {
        $expectedSuffix = strlen($this->expected) - 1;
        $actualSuffix = strlen($this->actual) - 1;

        for (; $actualSuffix >= $this->prefix && $expectedSuffix >= $this->prefix;
               $actualSuffix--, $expectedSuffix--) {
            if ($this->expected[$expectedSuffix] !== $this->actual[$actualSuffix]) {
                break;
            }
        }
        $this->suffix = strlen($this->expected) - $expectedSuffix;
    }

    private function computeCommonPrefix(): string
    {
        return ($this->prefix > $this->contextLength ? self::ELLIPSIS : "") .
            $this->substring($this->expected,
                max(0, $this->prefix - $this->contextLength),
                $this->prefix);

    }

    private function computeCommonSuffix(): string
    {
        $end = min(strlen($this->expected) - $this->suffix + 1 + $this->contextLength,
            strlen($this->expected));

        return $this->substring($this->expected, strlen($this->expected) - $this->suffix + 1, $end) .
            (strlen($this->expected) - $this->suffix + 1 < strlen($this->expected) -
            $this->contextLength ? self::ELLIPSIS : "");
    }

    private function areStringsEqual(): bool
    {
        return $this->expected === $this->actual;
    }

    private function format(?string $message, ?string $fExpected, ?string $fActual): string
    {
        return trim(sprintf('%s expected:<%s> but was:<%s>', $message, $fExpected ?? 'null', $fActual ?? 'null'));
    }

    function substring(string $str, int $start, int $end = null): string
    {
        if ($end !== null) {
            return substr($str, $start, $end - $start);
        }

        return substr($str, $start);
    }
}