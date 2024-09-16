<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Employee\EmployeeFactoryImpl;
use CleanCode\Employee\EmployeeRecord;
use CleanCode\Employee\EmployeeType;
use CleanCode\Employee\InvalidEmployeeType;

$employeeRecords = [
    new EmployeeRecord(EmployeeType::COMMISSIONED),
    new EmployeeRecord(EmployeeType::HOURLY),
    new EmployeeRecord(EmployeeType::SALARIED),
];

$factory = new EmployeeFactoryImpl();

foreach ($employeeRecords as $record) {
    try {
        $employee = $factory->makeEmployee($record);
        $pay = $employee->calculatePay();
        $employee->deliverPay($pay);
    } catch (InvalidEmployeeType $e) {
        echo "Ошибка: " . $e->getMessage();
    }
}