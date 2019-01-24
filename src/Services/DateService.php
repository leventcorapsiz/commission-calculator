<?php

namespace leventcorapsiz\CommissionCalculator\Services;

class DateService
{
    /**
     * @param $firstDate
     * @param $secondDate
     *
     * @return bool
     */
    public static function datesAreInSameWeek($firstDate, $secondDate)
    {
        return (new \DateTime($firstDate))->format('oW') === (new \DateTime($secondDate))->format('oW');
    }
}
