<?php
declare(strict_types=1);

namespace CleanCode\PHPUnit;

class IntermediateVersion
{

    private const ELLIPSIS = "...";
    private const DELTA_END = "]";
    private const DELTA_START = "[";

    private string $compactExpected;
    private string $compactActual;
    private int $prefixLength = 0;
    private int $suffixLength;

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
        } else {
            return $this->format($message, $this->expected, $this->actual);
        }
    }

    private function compactExpectedAndActual(): void
    {
        $this->findCommonPrefixAndSuffix();
        $this->compactExpected = $this->compactString($this->expected);
        $this->compactActual = $this->compactString($this->actual);
    }

    private function canBeCompacted(): bool
    {
        return $this->expected !== null && $this->actual !== null && !$this->areStringsEqual();
    }

    private function compactString(string $source): string
    {
        $length = strlen($source) - $this->suffixLength;

        $result = self::DELTA_START .
            $this->substring($source, $this->prefixLength, $length) .
            self::DELTA_END;

        if ($this->prefixLength > 0) {
            $result = $this->computeCommonPrefix() . $result;
        }

        if ($this->suffixLength > 0) {
            $result .= $this->computeCommonSuffix();
        }

        return $result;

    }

    private function findCommonPrefix(): void
    {

        $this->prefixLength = 0;
        $end = min(strlen($this->expected), strlen($this->actual));

        for (; $this->prefixLength < $end; $this->prefixLength++) {
            if ($this->expected[$this->prefixLength] !== $this->actual[$this->prefixLength]) {
                break;
            }
        }
    }

    private function findCommonPrefixAndSuffix(): void
    {
        $this->findCommonPrefix();
        $this->suffixLength = 0;

        for (; !$this->suffixOverlapsPrefix($this->suffixLength); $this->suffixLength++) {
            if ($this->charFromEnd($this->expected, $this->suffixLength)
                !== $this->charFromEnd($this->actual, $this->suffixLength)) {
                break;
            }
        }
    }

    private function charFromEnd(string $expected, int $i): string
    {
        $e = strlen($expected) - $i - 1;
        return $expected[$e];
    }

    private function suffixOverlapsPrefix(int $suffixLength): bool
    {
        return strlen($this->actual) - $suffixLength <= $this->prefixLength ||
            strlen($this->expected) - $suffixLength <= $this->prefixLength;
    }

    private function computeCommonPrefix(): string
    {
        return ($this->prefixLength > $this->contextLength ? self::ELLIPSIS : "") .
            $this->substring($this->expected,
                max(0, $this->prefixLength - $this->contextLength),
                $this->prefixLength);

    }

    private function computeCommonSuffix(): string
    {
        $expectedLength = strlen($this->expected);
        $remainingLength = $expectedLength - $this->suffixLength;

        $end = min($expectedLength - $this->suffixLength + $this->contextLength, $expectedLength);

        return $this->substring($this->expected, $remainingLength, $end) .
            ($remainingLength < $expectedLength - $this->contextLength ? self::ELLIPSIS : "");
    }

    private function areStringsEqual(): bool
    {
        return $this->expected === $this->actual;
    }

    private function format(?string $message, ?string $fExpected, ?string $fActual): string
    {
        return trim(sprintf('%s expected:<%s> but was:<%s>',
            $message, $fExpected ?? 'null', $fActual ?? 'null'));
    }

    private function substring(string $str, int $start, int $end = null): string
    {
        if ($end !== null) {
            return substr($str, $start, $end - $start);
        }

        return substr($str, $start);
    }
}