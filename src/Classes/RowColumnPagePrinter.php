<?php
declare(strict_types=1);
namespace CleanCode\Classes;

class RowColumnPagePrinter
{
    private int $rowsPerPage;
    private int $columnsPerPage;
    private int $numbersPerPage;
    private string $pageHeader;
    private PrintStream $printStream;

    public function __construct(int $rowsPerPage, int $columnsPerPage, string $pageHeader)
    {
        $this->rowsPerPage = $rowsPerPage;
        $this->columnsPerPage = $columnsPerPage;
        $this->pageHeader = $pageHeader;
        $this->numbersPerPage = $rowsPerPage * $columnsPerPage;
    }

    public function print(array $data): void
    {
        $pageNumber = 1;
        for ($firstIndexOnPage = 0; $firstIndexOnPage < count($data); $firstIndexOnPage += $this->numbersPerPage) {
            $lastIndexOnPage = min($firstIndexOnPage + $this->numbersPerPage - 1, count($data) - 1);
            $this->printPageHeader($this->pageHeader, $pageNumber);
            $this->printPage($firstIndexOnPage, $lastIndexOnPage, $data);
            $this->printStream->write("\f\n");
            $pageNumber++;
        }
    }

    private function printPage(int $firstIndexOnPage, int $lastIndexOnPage, array $data): void
    {
        $firstIndexOfLastRowOnPage = $firstIndexOnPage + $this->rowsPerPage - 1;
        for ($firstIndexInRow = $firstIndexOnPage; $firstIndexInRow <= $firstIndexOfLastRowOnPage; $firstIndexInRow++) {
            $this->printRow($firstIndexInRow, $lastIndexOnPage, $data);
            $this->printStream->write("\n");
        }
    }

    private function printRow(int $firstIndexInRow, int $lastIndexOnPage, array $data): void
    {
        for ($column = 0; $column < $this->columnsPerPage; $column++) {
            $index = $firstIndexInRow + $column * $this->rowsPerPage;
            if ($index <= $lastIndexOnPage) {
                $this->printStream->write(sprintf("%10d", $data[$index]));
            }
        }
    }

    private function printPageHeader(string $pageHeader, int $pageNumber): void
    {
        $this->printStream->write($pageHeader . " --- Page " . $pageNumber);
        $this->printStream->write("\n");
    }

    public function setOutput(PrintStream $printStream): void
    {
        $this->printStream = $printStream;
    }
}