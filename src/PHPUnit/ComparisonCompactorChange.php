<?php
declare(strict_types=1);

namespace CleanCode\PHPUnit;

class ComparisonCompactorChange
{
    private const ELLIPSIS = "...";
    private const DELTA_END = "]";
    private const DELTA_START = "[";
    private int $prefixLength = 0;
    private int $suffixLength = 0;

    public function __construct(
        private readonly int     $contextLength,
        private readonly ?string $expected,
        private readonly ?string $actual)
    {
    }

    public function formatCompactedComparison(?string $message = null): string
    {
        $compactExpected = $this->expected;
        $compactActual = $this->actual;

        if ($this->shouldBeCompacted()) {
            $this->findCommonPrefixAndSuffix();
            $compactExpected = $this->compact($this->expected);
            $compactActual = $this->compact($this->actual);
        }
        return $this->format($message, $compactExpected, $compactActual);
    }

    private function shouldBeCompacted(): bool
    {
        return !$this->shouldNotBeCompacted();
    }

    private function shouldNotBeCompacted(): bool
    {
        return $this->expected == null ||
            $this->actual == null ||
            $this->expected === $this->actual;
    }

    private function findCommonPrefixAndSuffix(): void
    {
        $this->findCommonPrefix();
        $this->suffixLength = 0;
        for (; !$this->suffixOverlapsPrefix(); $this->suffixLength++) {
            if ($this->charFromEnd($this->expected, $this->suffixLength) !==
                $this->charFromEnd($this->actual, $this->suffixLength)) {
                break;
            }
        }
    }

    private function charFromEnd(?string $s, int $i): string
    {
        return $s[strlen($s) - $i - 1];
    }

    private function suffixOverlapsPrefix(): bool
    {
        return strlen($this->actual) - $this->suffixLength <= $this->prefixLength ||
            strlen($this->expected) - $this->suffixLength <= $this->prefixLength;
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

    private function compact(string $s): string
    {
        return implode('', [
            $this->startingEllipsis(),
            $this->startingContext(),
            self::DELTA_START,
            $this->delta($s),
            self::DELTA_END,
            $this->endingContext(),
            $this->endingEllipsis()
        ]);
    }

    private function startingEllipsis(): string
    {
        return $this->prefixLength > $this->contextLength ? self::ELLIPSIS : "";
    }

    private function startingContext(): string
    {
        $contextStart = max(0, $this->prefixLength - $this->contextLength);
        $contextEnd = $this->prefixLength;
        return $this->substring($this->expected, $contextStart, $contextEnd);
    }

    private function delta(string $s): string
    {
        $deltaStart = $this->prefixLength;
        $deltaEnd = strlen($s) - $this->suffixLength;
        return $this->substring($s, $deltaStart, $deltaEnd);
    }

    private function endingContext(): string
    {
        $contextStart = strlen($this->expected) - $this->suffixLength;
        $contextEnd = min($contextStart + $this->contextLength,
            strlen($this->expected));
        return $this->substring($this->expected, $contextStart, $contextEnd);
    }

    private function endingEllipsis(): string
    {
        return ($this->suffixLength > $this->contextLength ? self::ELLIPSIS : "");
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