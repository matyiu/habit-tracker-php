<?php

function datePeriod2Calendar(DatePeriod $datePeriod)
{
    $weeksArr = [];
    $endDate = $datePeriod->getEndDate()->sub(new DateInterval('P1D'));

    $currentWeek = [];
    foreach ($datePeriod as $date) {
        if ($date->getTimestamp() === $datePeriod->getStartDate()->getTimestamp()) {
            $weekDay = $date->format('N') - 1;
            for ($i=0; $i < $weekDay; $i++) { 
                array_push($currentWeek, [
                    'day' => null,
                    'month' => null,
                ]);
            }
        }

        array_push($currentWeek, [
            'day' => $date->format('j'),
            'month' => $date->format('n')
        ]);

        if (count($currentWeek) === 7) {
            array_push($weeksArr, $currentWeek);
            $currentWeek = [];
        } 
        
        if ($date->getTimestamp() === $endDate->getTimestamp()) {
            $fillerDaysNumber = (7 - $date->format('N'));
            for ($i=0; $i < $fillerDaysNumber; $i++) { 
                array_push($currentWeek, [
                    'day' => null,
                    'month' => null,
                ]);
            }
            array_push($weeksArr, $currentWeek);
        }
    }

    return $weeksArr;
}