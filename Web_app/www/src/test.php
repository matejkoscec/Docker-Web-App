<?php

$startDate = '2020-03-05';
$endDate = '2020-01-05';

if (empty($startDate)) $startDate = $endDate;
if (empty($endDate)) $endDate = $startDate;

if (strtotime($startDate) > strtotime($endDate)) {
    $temp = $startDate;
    $startDate = $endDate;
    $endDate = $temp;
}

$dateArray = array();

if ($startDate == $endDate) $dateArray[0] = $startDate;
else {
    $i = 0;
    while ($startDate != date('Y-m-d', strtotime($endDate . '+1 day'))) {
        if (!(date_format(date_create($startDate), 'D') == 'Sat' || date_format(date_create($startDate), 'D') == 'Sun')) {
            $dateArray[$i] = $startDate;
            $i++;
        }
        $startDate = date('Y-m-d', strtotime($startDate . '+1 day'));
    }
}

for ($i = 0; $i < count($dateArray); $i++) {
    echo $dateArray[$i];
    echo '<br>';
}