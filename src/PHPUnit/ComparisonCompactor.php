<?php
declare(strict_types=1);

namespace CleanCode\PHPUnit;

class ComparisonCompactor
{
    private const ELLIPSIS = "...";
    private const DELTA_END = "]";
    private const DELTA_START = "[";
    private int $fPrefix = 0;
    private int $fSuffix = 0;

    public function __construct(
        private readonly int     $fContextLength,
        private readonly ?string $fExpected,
        private readonly ?string $fActual)
    {
    }

    public function compact(string $message): string
    {
        if ($this->fExpected === null || $this->fActual === null || $this->areStringsEqual()) {
            return $this->format($message, $this->fExpected, $this->fActual);
        }

        $this->findCommonPrefix();
        $this->findCommonSuffix();

        $expected = $this->compactString($this->fExpected);
        $actual = $this->compactString($this->fActual);
        return $this->format($message, $expected, $actual);
    }

    private function compactString(string $source): string
    {
        $start = $this->fPrefix;
        $length = strlen($source) - $this->fSuffix + 1;

        $subst = $this->substring($source, $start, $length);

        $result = self::DELTA_START .
            $subst .
            self::DELTA_END;

        if ($this->fPrefix > 0) {
            $result = $this->computeCommonPrefix() . $result;
        }

        if ($this->fSuffix > 0) {
            $result .= $this->computeCommonSuffix();
        }

        return $result;
    }

    private function findCommonPrefix(): void
    {
        $this->fPrefix = 0;
        $end = min(strlen($this->fExpected), strlen($this->fActual));

        for (; $this->fPrefix < $end; $this->fPrefix++) {
            if ($this->fExpected[$this->fPrefix] !== $this->fActual[$this->fPrefix]) {
                break;
            }
        }
    }

    private function findCommonSuffix(): void
    {
        $expectedSuffix = strlen($this->fExpected) - 1;
        $actualSuffix = strlen($this->fActual) - 1;

        for (; $actualSuffix >= $this->fPrefix && $expectedSuffix >= $this->fPrefix;
               $actualSuffix--, $expectedSuffix--) {
            if ($this->fExpected[$expectedSuffix] !== $this->fActual[$actualSuffix]) {
                break;
            }
        }
        $this->fSuffix = strlen($this->fExpected) - $expectedSuffix;
    }

    private function computeCommonPrefix(): string
    {
          return ($this->fPrefix > $this->fContextLength ? self::ELLIPSIS : "") .
              $this->substring($this->fExpected,
                  max(0, $this->fPrefix - $this->fContextLength),
                  $this->fPrefix);

    }

    private function computeCommonSuffix(): string
    {
          $end = min(strlen($this->fExpected) - $this->fSuffix + 1 + $this->fContextLength,
          strlen($this->fExpected));

          return $this->substring($this->fExpected,
                  strlen($this->fExpected) - $this->fSuffix + 1,
                  $end) .
                  (strlen($this->fExpected) - $this->fSuffix + 1 <
                  strlen($this->fExpected) - $this->fContextLength ? self::ELLIPSIS : "");
    }

    private function areStringsEqual(): bool
    {
        return $this->fExpected === $this->fActual;
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