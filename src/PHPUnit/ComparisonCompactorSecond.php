<?php
declare(strict_types=1);
namespace CleanCode\PHPUnit;

class ComparisonCompactorSecond
{
    private int $ctxt;
    private string $s1;
    private string $s2;
    private int $pfx;
    private int $sfx;

    public function __construct(int $ctxt, string $s1, string $s2)
    {
        $this->ctxt = $ctxt;
        $this->s1 = $s1;
        $this->s2 = $s2;
    }

    public function compact(string $msg): string
    {
        if ($this->s1 === null || $this->s2 === null || $this->s1 === $this->s2) {
            return $this->format($msg, $this->s1, $this->s2);
        }

        $this->pfx = 0;
        for (; $this->pfx < strlen($msg); $this->pfx++) {
            if($this->s1[$this->pfx] !== $this->s2[$this->pfx]){
                break;
            }
        }

        $sfx1 = strlen($this->s1) - 1;
        $sfx2 = strlen($this->s2) - 1;

        for (; $sfx2 >= $this->pfx && $sfx1 >= $this->pfx;$this->pfx--){
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
        $result = "[" . substr($s, $this->pfx, strlen($s) - $this->sfx + 1) . "]";

        if ($this->pfx > 0) {
            //И внезапно $s1
            $result = ($this->pfx > $this->ctxt ? "..." : "") .
                substr($s, max(0, $this->pfx - $this->ctxt), $this->pfx) .
                $result;
        }

        if ($this->sfx > 0) {
            $end = min(strlen($s) - $this->sfx + 1 + $this->ctxt, strlen($s));
            $result .= substr($s, strlen($s) - $this->sfx + 1, $end - (strlen($s) - $this->sfx + 1)) .
                (strlen($s) - $this->sfx + 1 < strlen($s) - $this->ctxt ? "..." : "");
        }

        return $result;
    }

    private function format(string $msg, string $s1, string $s2): string
    {
        return trim(sprintf('%s expected:<%s> but was:<%s>', $msg, $s1, $s2));
    }

}