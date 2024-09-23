<?php
declare(strict_types=1);
namespace CleanCode\PHPUnit;

class ComparisonCompactorSecond
{
    private int $ctxt;
    private ?string $s1;
    private ?string $s2;
    private int $pfx;
    private int $sfx;

    public function __construct(int $ctxt, ?string $s1, ?string $s2)
    {
        $this->ctxt = $ctxt;
        $this->s1 = $s1;
        $this->s2 = $s2;
    }

    public function compact(string $msg): string
    {
        if ($this->s1 === null || $this->s2 === null  || $this->areStringsEqual()) {
            return $this->format($msg, $this->s1, $this->s2);
        }

        $this->pfx = 0;
        for (; $this->pfx < min(strlen($this->s1),strlen($this->s2)); $this->pfx++) {
            if($this->s1[$this->pfx] !== $this->s2[$this->pfx]){
                break;
            }
        }

        $sfx1 = strlen($this->s1) - 1;
        $sfx2 = strlen($this->s2) - 1;

        for (; $sfx2 >= $this->pfx && $sfx1 >= $this->pfx;$sfx1--,$sfx2--){
            if($this->s1[$sfx1] !== $this->s2[$sfx2]) {
                break;
            }
        }

        $this->sfx = strlen($this->s1) - $sfx1;

        $cmp1 = $this->compactString($this->s1);
        $cmp2 = $this->compactString($this->s2);

        return $this->format($msg, $cmp1, $cmp2);
    }

    private function compactString(string $s): string
    {
        $result = "[" . $this->substring($s, $this->pfx, strlen($s) - $this->sfx + 1) . "]";

        if ($this->pfx > 0) {
            $result = ($this->pfx > $this->ctxt ? "..." : "") .
                $this->substring($this->s1, max(0, $this->pfx - $this->ctxt), $this->pfx) .
                $result;
        }

        if ($this->sfx > 0) {
            $end = min(strlen($this->s1) - $this->sfx + 1 + $this->ctxt, strlen($this->s1));
            $result .= $this->substring($this->s1, strlen($this->s1) - $this->sfx + 1, $end) .
                (strlen($this->s1) - $this->sfx + 1 < strlen($this->s1) - $this->ctxt ? "..." : "");
        }

        return $result;
    }

    private function format(?string $msg, ?string $s1, ?string $s2): string
    {
        return trim(sprintf('%s expected:<%s> but was:<%s>', $msg, $s1 ?? 'null', $s2 ?? 'null'));
    }

    private function substring(string $str, int $start, int $end = null): string
    {
        if ($end !== null) {
            return substr($str, $start, $end - $start);
        }

        return substr($str, $start);
    }

    private function areStringsEqual(): bool
    {
        return $this->s1 === $this->s2;
    }
}