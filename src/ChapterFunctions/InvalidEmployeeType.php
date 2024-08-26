<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

class InvalidEmployeeType extends Exception {
    public function __construct($type) {
    }
}