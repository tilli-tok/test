<?php
declare(strict_types=1);
namespace CleanCode\Classes;

class PrimePrinter
{

    public static function main(): void
    {
        $primes = PrimeGenerator::generate(PrimePrinterConfig::NUMBER_OF_PRIMES->value);

        $tablePrinter = new RowColumnPagePrinter(
            PrimePrinterConfig::ROWS_PER_PAGE->value,
            PrimePrinterConfig::COLUMNS_PER_PAGE->value,
                        "The First " . PrimePrinterConfig::NUMBER_OF_PRIMES->value .
                        " Prime Numbers"
        );

        $tablePrinter->setOutput(new PrintStream());
        $tablePrinter->print($primes);
    }
}