<?php
declare(strict_types=1);

namespace PHPUnit;

use CleanCode\PHPUnit\IntermediateVersionOld;
use PHPUnit\Framework\TestCase;

class IntermediateVersionOldTest extends TestCase
{
    public function testMessage(): void
    {
        $compact = new IntermediateVersionOld(0, 'b', 'c');
        $failure = $compact->compact('a');
        $this->assertTrue($failure === 'a expected:<[b]> but was:<[c]>');
    }

    public function testStartSame(): void
    {
        $compact = new IntermediateVersionOld(1, 'ba', 'bc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<b[a]> but was:<b[c]>', $failure);
    }

    public function testEndSame(): void
    {
        $compact = new IntermediateVersionOld(1, 'ab', 'cb');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<[a]b> but was:<[c]b>', $failure);
    }

    public function testSame(): void
    {
        $compact = new IntermediateVersionOld(1, 'ab', 'ab');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<ab> but was:<ab>', $failure);
    }

    public function testNoContextStartAndEndSame(): void
    {
        $compact = new IntermediateVersionOld(0, 'abc', 'adc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<...[b]...> but was:<...[d]...>', $failure);
    }

    public function testStartAndEndContext(): void
    {
        $compact = new IntermediateVersionOld(1, 'abc', 'adc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<a[b]c> but was:<a[d]c>', $failure);
    }

    public function testStartAndEndContextWithEllipses(): void
    {
        $compact = new IntermediateVersionOld(1, 'abcde', 'abfde');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<...b[c]d...> but was:<...b[f]d...>', $failure);
    }

    public function testComparisonErrorStartSameComplete(): void
    {
        $compact = new IntermediateVersionOld(2, 'ab', 'abc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<ab[]> but was:<ab[c]>', $failure);
    }

    public function testComparisonErrorEndSameComplete(): void
    {
        $compact = new IntermediateVersionOld(0, 'bc', 'abc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<[]...> but was:<[a]...>', $failure);
    }

    public function testComparisonErrorEndSameCompleteContext(): void
    {
        $compact = new IntermediateVersionOld(2, 'bc', 'abc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<[]bc> but was:<[a]bc>', $failure);
    }

    public function testComparisonErrorOverlapingMatches(): void
    {
        $compact = new IntermediateVersionOld(0, 'abc', 'abbc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<...[]...> but was:<...[b]...>', $failure);
    }

    public function testComparisonErrorOverlapingMatchesContext(): void
    {
        $compact = new IntermediateVersionOld(2, 'abc', 'abbc');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<ab[]c> but was:<ab[b]c>', $failure);
    }

    public function testComparisonErrorOverlapingMatches2(): void
    {
        $compact = new IntermediateVersionOld(0, 'abcdde', 'abcde');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<...[d]...> but was:<...[]...>', $failure);
    }

    public function testComparisonErrorOverlapingMatches2Context(): void
    {
        $compact = new IntermediateVersionOld(2, 'abcdde', 'abcde');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<...cd[d]e> but was:<...cd[]e>', $failure);
    }

    public function testComparisonErrorWithActualNull(): void
    {
        $compact = new IntermediateVersionOld(0, 'a', null);
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<a> but was:<null>', $failure);
    }

    public function testComparisonErrorWithActualNullContext(): void
    {
        $compact = new IntermediateVersionOld(2, 'a', null);
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<a> but was:<null>', $failure);
    }

    public function testComparisonErrorWithExpectedNull(): void
    {
        $compact = new IntermediateVersionOld(0, null, 'a');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<null> but was:<a>', $failure);
    }

    public function testComparisonErrorWithExpectedNullContext(): void
    {
        $compact = new IntermediateVersionOld(2, null, 'a');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<null> but was:<a>', $failure);
    }

    public function testBug609972(): void
    {
        $compact = new IntermediateVersionOld(10, 'S&P500', '0');
        $failure = $compact->compact((string)null);
        $this->assertEquals('expected:<[S&P50]0> but was:<[]0>', $failure);
    }
}
