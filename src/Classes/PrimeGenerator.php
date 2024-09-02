<?php
declare(strict_types=1);
namespace CleanCode\Classes;

class PrimeGenerator
{
    private static array $primes = [];
    private static array $multiplesOfPrimeFactors = [];

    public static function generate(int $n): array
    {
        PrimeGenerator::$primes = array_fill(0, $n, 0);
        PrimeGenerator::$multiplesOfPrimeFactors = [];
        PrimeGenerator::set2AsFirstPrime();
        PrimeGenerator::checkOddNumbersForSubsequentPrimes();
        return PrimeGenerator::$primes;
    }

    private static function set2AsFirstPrime(): void
    {
        PrimeGenerator::$primes[0] = 2;
        PrimeGenerator::$multiplesOfPrimeFactors[] = 2;
    }

    private static function checkOddNumbersForSubsequentPrimes(): void
    {
        $primeIndex = 1;
        for ($candidate = 3; $primeIndex < count(PrimeGenerator::$primes); $candidate += 2) {
            if (PrimeGenerator::isPrime($candidate)) {
                PrimeGenerator::$primes[$primeIndex++] = $candidate;
            }
        }
    }

    private static function isPrime(int $candidate): bool
    {
        if (PrimeGenerator::isLeastRelevantMultipleOfNextLargerPrimeFactor($candidate)) {
            PrimeGenerator::$multiplesOfPrimeFactors[] = $candidate;
            return false;
        }
        return PrimeGenerator::isNotMultipleOfAnyPreviousPrimeFactor($candidate);
    }

    private static function isLeastRelevantMultipleOfNextLargerPrimeFactor(int $candidate): bool
    {
        $nextLargerPrimeFactor = PrimeGenerator::$primes[count(PrimeGenerator::$multiplesOfPrimeFactors)];
        $leastRelevantMultiple = $nextLargerPrimeFactor * $nextLargerPrimeFactor;
        return $candidate === $leastRelevantMultiple;
    }

    private static function isNotMultipleOfAnyPreviousPrimeFactor(int $candidate): bool
    {
        for ($n = 1; $n < count(PrimeGenerator::$multiplesOfPrimeFactors); $n++) {
            if (PrimeGenerator::isMultipleOfNthPrimeFactor($candidate, $n)) {
                return false;
            }
        }
        return true;
    }

    private static function isMultipleOfNthPrimeFactor(int $candidate, int $n): bool
    {
        return $candidate === PrimeGenerator::smallestOddNthMultipleNotLessThanCandidate($candidate, $n);
    }

    private static function smallestOddNthMultipleNotLessThanCandidate(int $candidate, int $n): int
    {
        $multiple = PrimeGenerator::$multiplesOfPrimeFactors[$n];
        while ($multiple < $candidate) {
            $multiple += 2 * PrimeGenerator::$primes[$n];
        }
        PrimeGenerator::$multiplesOfPrimeFactors[$n] = $multiple;
        return $multiple;
    }
}