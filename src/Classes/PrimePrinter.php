<?php
declare(strict_types=1);
namespace CleanCode\Classes;


use CleanCode\Classes\PrimeGenerator;

class PrimePrinter
{
    private const NUMBER_OF_PRIMES = 1000;
    private const ROWS_PER_PAGE = 50;
    private const COLUMNS_PER_PAGE = 4;

    public static function main(array $args = []): void
    {
        $primes = PrimeGenerator::generate(PrimePrinter::NUMBER_OF_PRIMES);

        $tablePrinter = new RowColumnPagePrinter(
            PrimePrinter::ROWS_PER_PAGE,
            PrimePrinter::COLUMNS_PER_PAGE,
                        "The First " . PrimePrinter::NUMBER_OF_PRIMES .
                        " Prime Numbers"
        );

        $tablePrinter->setOutput(new PrintStream());
        $tablePrinter->print($primes);
    }
}