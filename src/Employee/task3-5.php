<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Employee\EmployeeFactoryImpl;
use CleanCode\Employee\EmployeeRecord;
use CleanCode\Employee\EmployeeType;

$employeeRecords = [
    new EmployeeRecord(EmployeeType::COMMISSIONED),
    new EmployeeRecord(EmployeeType::HOURLY),
    new EmployeeRecord(EmployeeType::SALARIED),
];

$factory = new EmployeeFactoryImpl();

foreach ($employeeRecords as $record) {
    $employee = $factory->makeEmployee($record);

    if ($employee->isPayday()) {
        $pay = $employee->calculatePay();
        $employee->deliverPay($pay);
    }
}