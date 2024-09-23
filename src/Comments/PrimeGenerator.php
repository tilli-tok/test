<?php
declare(strict_types=1);

namespace CleanCode\Comments;

/**
 * Класс генерирует простые числа до максимального значения, заданного
 * пользователем, по алгоритму "Решета Эратосфена".
 * Берем массив целых чисел, начиная с 2, и вычеркиваем
 * из него все числа, кратные 2. Находим следующее невычеркнутое число
 * и вычеркиваем все числа, кратные ему. Повторяем до тех пор, пока из массива
 * не будут вычеркнуты все кратные.
 */
class PrimeGenerator
{
    /**
     * @var boolean[] $crossedOut
     */
    private static array $crossedOut = [];
    /**
     * @var int[] $result
     */
    private static array $result = [];

    /**
     * @return array|int[]
     */
    public static function generatePrimes(int $maxValue): array
    {
        if ($maxValue < 2) {
            return [];
        }
        self::uncrossIntegersUpTo($maxValue);
        self::crossOutMultiples();
        self::putUncrossedIntegersIntoResult();

        return self::$result;
    }

    private static function uncrossIntegersUpTo(int $maxValue): void
    {
        self::$crossedOut = array_fill(0, $maxValue + 1, false);
        for ($i = 2; $i < count(self::$crossedOut); $i++) {
            self::$crossedOut[$i] = false;
        }
    }

    private static function crossOutMultiples(): void
    {
        $limit = self::determineIterationLimit();
        for ($i = 2; $i <= $limit; $i++) {
            if (self::notCrossed($i)) {
                self::crossOutMultiplesOf($i);
            }
        }
    }

    private static function determineIterationLimit(): int
    {
        // Каждое кратное в массиве имеет простой множитель, больший либо равный
        // квадратному корню из размера массива. Следовательно, вычеркивать элементы,
        // кратные числам, превышающих квадратный корень, не нужно.
        $iterationLimit = sqrt(count(self::$crossedOut));
        return (int)$iterationLimit;
    }

    private static function crossOutMultiplesOf(int $i): void
    {
        for ($multiple = 2 * $i; $multiple < count(self::$crossedOut); $multiple += $i) {
            self::$crossedOut[$multiple] = true;
        }

    }

    private static function notCrossed(int $i): bool
    {
        return self::$crossedOut[$i] == false;
    }

    private static function putUncrossedIntegersIntoResult(): void
    {
        $count = self::numberOfUncrossedIntegers();
        self::$result = array_fill(0, $count, 0);
        for ($j = 0, $i = 2; $i < count(self::$crossedOut); $i++) {
            if (self::notCrossed($i)) {
                self::$result[$j++] = $i;
            }
        }
    }

    private static function numberOfUncrossedIntegers(): int
    {
        $count = 0;
        for ($i = 2; $i < count(self::$crossedOut); $i++) {
            if (self::notCrossed($i)) {
                $count++;
            }
        }
        return $count;
    }
}