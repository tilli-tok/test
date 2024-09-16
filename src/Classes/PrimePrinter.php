<?php
declare(strict_types=1);

namespace CleanCode\Classes;

class PrimePrinter
{
    private const NUMBER_OF_PRIMES = 1000;
    private const ROWS_PER_PAGE = 50;
    private const COLUMNS_PER_PAGE = 4;
    public static function main(): void
    {
        $primes = PrimeGenerator::generate(self::NUMBER_OF_PRIMES);

        $tablePrinter = new RowColumnPagePrinter(
            self::ROWS_PER_PAGE,
            self::COLUMNS_PER_PAGE,
            "The First " . self::NUMBER_OF_PRIMES .
            " Prime Numbers"
        );

        $tablePrinter->setOutput(new PrintStream());
        $tablePrinter->print($primes);
    }
}