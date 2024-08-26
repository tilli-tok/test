<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Functions\EmployeeFactoryImpl;


$employeeRecords = [
    new EmployeeRecord('COMMISSIONED', 'Name1', commission: 5),
    new EmployeeRecord('HOURLY', 'Name2', hourly: 15),
    new EmployeeRecord('SALARIED', 'Name3', salary: 10),
];

$factory = new EmployeeFactoryImpl();

foreach ($employeeRecords as $record) {
    $employee = $factory->makeEmployee($record);

    if ($employee->isPayday()) {
        $pay = $employee->calculatePay();
        $employee->deliverPay($pay);
    }
}