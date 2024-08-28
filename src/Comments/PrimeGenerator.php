<?php

declare(strict_types=1);

namespace CleanCode\Comments;
class PrimeGenerator
{
    private static array $crossedOut = [];
    private static array $result = [];
    public static function generatePrimes(int $maxValue): array
    {
        if ($maxValue < 2) {
            return [];
        } else {
            PrimeGenerator::uncrossIntegersUpTo($maxValue);
            PrimeGenerator::crossOutMultiples();
            PrimeGenerator::putUncrossedIntegersIntoResult();
            return PrimeGenerator::$result;
        }
    }
    private static function uncrossIntegersUpTo(int $maxValue): void
    {
        PrimeGenerator::$crossedOut = array_fill(0, $maxValue + 1, false);
        for ($i = 2; $i < count(PrimeGenerator::$crossedOut); $i++) {
            PrimeGenerator::$crossedOut[$i] = false;
        }
    }
    private static function crossOutMultiples(): void
    {
        $limit = PrimeGenerator::determineIterationLimit();
        for ($i = 2; $i <= $limit; $i++)
        {
            if (PrimeGenerator::notCrossed($i))
            {
                PrimeGenerator::crossOutMultiplesOf($i);
            }
        }
    }
    private static function determineIterationLimit(): int
    {
        // Каждое кратное в массиве имеет простой множитель, больший либо равный
        // квадратному корню из размера массива. Следовательно, вычеркивать элементы,
        // кратные числам, превышающих квадратный корень, не нужно.
        $iterationLimit = sqrt(count(PrimeGenerator::$crossedOut));
        return (int) $iterationLimit;
    }
    private static function crossOutMultiplesOf(int $i): void
    {
        for ($multiple = 2*$i; $multiple < count(PrimeGenerator::$crossedOut); $multiple += $i){
            PrimeGenerator::$crossedOut[$multiple] = true;
        }

    }
    private static function notCrossed(int $i): bool
    {
        return PrimeGenerator::$crossedOut[$i] == false;
    }
    private static function putUncrossedIntegersIntoResult(): void
    {
        $count = PrimeGenerator::numberOfUncrossedIntegers();
        PrimeGenerator::$result = array_fill(0, $count, 0);
        for ($j = 0, $i = 2; $i < count(PrimeGenerator::$crossedOut); $i++) {
            if (PrimeGenerator::notCrossed($i)) {
                PrimeGenerator::$result[$j++] = $i;
            }
        }
    }
    private static function numberOfUncrossedIntegers(): int
    {
        $count = 0;
        for ($i = 2; $i < count(PrimeGenerator::$crossedOut); $i++)
        {
            if (PrimeGenerator::notCrossed($i))
            {
                $count++;
            }
        }
        return $count;
    }
}