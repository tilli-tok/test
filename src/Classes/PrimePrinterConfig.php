<?php
namespace CleanCode\Classes;

enum PrimePrinterConfig : int
{
    case NUMBER_OF_PRIMES = 1000;
    case ROWS_PER_PAGE = 50;
    case COLUMNS_PER_PAGE = 4;
}