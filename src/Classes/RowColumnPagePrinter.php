<?php
declare(strict_types=1);

namespace CleanCode\Classes;

class RowColumnPagePrinter
{
    private int $numbersPerPage;
    private PrintStream $printStream;

    public function __construct(
        private int $rowsPerPage,
        private int $columnsPerPage,
        private string $pageHeader
    ) {
        $this->numbersPerPage = $rowsPerPage * $columnsPerPage;
    }

    /**
     * @param int[] $data
     */
    public function print(array $data): void
    {
        $pageNumber = 1;
        for ($firstIndexOnPage = 0; $firstIndexOnPage < count($data); $firstIndexOnPage += $this->numbersPerPage) {
            $lastIndexOnPage = min($firstIndexOnPage + $this->numbersPerPage - 1, count($data) - 1);
            $this->printPageHeader($this->pageHeader, $pageNumber);
            $this->printPage($firstIndexOnPage, $lastIndexOnPage, $data);
            $this->printStream->println("\f");
            $pageNumber++;
        }
    }

    private function printPage(int $firstIndexOnPage, int $lastIndexOnPage, array $data): void
    {
        $firstIndexOfLastRowOnPage = $firstIndexOnPage + $this->rowsPerPage - 1;
        for ($firstIndexInRow = $firstIndexOnPage; $firstIndexInRow <= $firstIndexOfLastRowOnPage; $firstIndexInRow++) {
            $this->printRow($firstIndexInRow, $lastIndexOnPage, $data);
            $this->printStream->println("\n");
        }
    }

    /**
     * @param int[] $data
     */
    private function printRow(int $firstIndexInRow, int $lastIndexOnPage, array $data): void
    {
        for ($column = 0; $column < $this->columnsPerPage; $column++) {
            $index = $firstIndexInRow + $column * $this->rowsPerPage;
            if ($index <= $lastIndexOnPage) {
                $this->printStream->format("%10d", $data[$index]);
            }
        }
    }

    private function printPageHeader(string $pageHeader, int $pageNumber): void
    {
        $this->printStream->println($pageHeader . " --- Page " . $pageNumber);
        $this->printStream->println("");
    }

    public function setOutput(PrintStream $printStream): void
    {
        $this->printStream = $printStream;
    }
}
