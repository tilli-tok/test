<?php
declare(strict_types=1);

namespace CleanCode\Classes;

class PrimeGenerator
{
    /**
     * @var int[]
     */
    private static array $primes = [];
    private static IntegerArrayList $multiplesOfPrimeFactors;

    /**
     * TODO: Ошибка в примере из книги, указано protected
     */
    public static function generate(int $n): array
    {
        self::$primes = array_fill(0, $n, 0);
        self::$multiplesOfPrimeFactors = new IntegerArrayList();
        self::set2AsFirstPrime();
        self::checkOddNumbersForSubsequentPrimes();
        return self::$primes;
    }

    private static function set2AsFirstPrime(): void
    {
        self::$primes[0] = 2;
        self::$multiplesOfPrimeFactors->add(2);
    }

    private static function checkOddNumbersForSubsequentPrimes(): void
    {
        $primeIndex = 1;
        for ($candidate = 3; $primeIndex < count(self::$primes); $candidate += 2) {
            if (self::isPrime($candidate)) {
                self::$primes[$primeIndex++] = $candidate;
            }
        }
    }

    private static function isPrime(int $candidate): bool
    {
        if (self::isLeastRelevantMultipleOfNextLargerPrimeFactor($candidate)) {
            self::$multiplesOfPrimeFactors[] = $candidate;
            return false;
        }
        return self::isNotMultipleOfAnyPreviousPrimeFactor($candidate);
    }

    private static function isLeastRelevantMultipleOfNextLargerPrimeFactor(int $candidate): bool
    {
        $nextLargerPrimeFactor = self::$primes[self::$multiplesOfPrimeFactors->size()];
        $leastRelevantMultiple = $nextLargerPrimeFactor * $nextLargerPrimeFactor;
        return $candidate === $leastRelevantMultiple;
    }

    private static function isNotMultipleOfAnyPreviousPrimeFactor(int $candidate): bool
    {
        for ($n = 1; $n < self::$multiplesOfPrimeFactors->size(); $n++) {
            if (self::isMultipleOfNthPrimeFactor($candidate, $n)) {
                return false;
            }
        }
        return true;
    }

    private static function isMultipleOfNthPrimeFactor(int $candidate, int $n): bool
    {
        return $candidate === self::smallestOddNthMultipleNotLessThanCandidate($candidate, $n);
    }

    private static function smallestOddNthMultipleNotLessThanCandidate(int $candidate, int $n): int
    {
        $multiple = self::$multiplesOfPrimeFactors[$n];
        while ($multiple < $candidate) {
            $multiple += 2 * self::$primes[$n];
        }
        self::$multiplesOfPrimeFactors[$n] = $multiple;
        return $multiple;
    }
}
