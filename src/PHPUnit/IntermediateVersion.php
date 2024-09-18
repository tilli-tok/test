<?php
declare(strict_types=1);

namespace CleanCode\PHPUnit;

class IntermediateVersion
{
    private const ELLIPSIS = "...";
    private const DELTA_END = "]";
    private const DELTA_START = "[";
    private int $prefixIndex = 0;
    private int $suffixIndex = 0;
    private string $compactExpected;
    private string $compactActual;

    public function __construct(
        private readonly int     $contextLength,
        private readonly ?string $expected,
        private readonly ?string $actual)
    {
    }

    public function compact(string $message): string
    {
        if ($this->canBeCompacted()) {
            $this->compactExpectedAndActual();

            return $this->format($message, $this->compactExpected, $this->compactActual);

        }else{
            return $this->format($message, $this->expected, $this->actual);

        }
}
    private function compactExpectedAndActual(): void
    {
        //$prefixIndex = $this->findCommonPrefix();
        //$this->suffixIndex = $this->findCommonSuffix($prefixIndex);
        $this->findCommonPrefixAndSuffix();
        $this->compactExpected = $this->compactString($this->expected);
        $this->compactActual = $this->compactString($this->actual);
    }

    private function findCommonPrefixAndSuffix(): void
    {
        $this->findCommonPrefix();
        $expectedSuffix = strlen($this->expected) - 1;
        $actualSuffix = strlen($this->actual) - 1;

        for (; $actualSuffix >= $this->prefixIndex && $expectedSuffix >= $this->prefixIndex;
               $actualSuffix--, $expectedSuffix--) {
            if ($this->expected[$expectedSuffix] !== $this->actual[$actualSuffix]) {
                break;
            }
        }
        $this->suffixIndex =  strlen($this->expected) - $expectedSuffix;
    }
    private function canBeCompacted(): bool
    {
        return $this->expected !== null && $this->actual !== null && !$this->areStringsEqual();
    }
    private function compactString(string $source): string
    {
        $start = $this->prefixIndex;
        $length = strlen($source) - $this->suffixIndex + 1;

        $subst = $this->substring($source, $start, $length);

        $result = self::DELTA_START .
            $subst .
            self::DELTA_END;

        if ($this->prefixIndex > 0) {
            $result = $this->computeCommonPrefix() . $result;
        }

        if ($this->suffixIndex > 0) {
            $result .= $this->computeCommonSuffix();
        }

        return $result;

    }

    private function findCommonPrefix(): void
    {

        $this->prefixIndex = 0;
        $end = min(strlen($this->expected), strlen($this->actual));

        for (; $this->prefixIndex < $end; $this->prefixIndex++) {
            if ($this->expected[$this->prefixIndex] !== $this->actual[$this->prefixIndex]) {
                break;
            }
        }
    }


    private function computeCommonPrefix(): string
    {
        return ($this->prefixIndex > $this->contextLength ? self::ELLIPSIS : "") .
            $this->substring($this->expected,
                max(0, $this->prefixIndex - $this->contextLength),
                $this->prefixIndex);

    }

    private function computeCommonSuffix(): string
    {
        $end = min(strlen($this->expected) - $this->suffixIndex + 1 + $this->contextLength,
            strlen($this->expected));

        return $this->substring($this->expected, strlen($this->expected) - $this->suffixIndex + 1, $end) .
            (strlen($this->expected) - $this->suffixIndex + 1 < strlen($this->expected) -
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