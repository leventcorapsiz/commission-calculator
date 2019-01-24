<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Services;

use PHPUnit\Framework\TestCase;
use leventcorapsiz\CommissionCalculator\Services\DateService;

final class DateServiceTest extends TestCase
{
    public function testDatesAreInSameWeek()
    {
        $this->assertEquals(
            true,
            DateService::datesAreInSameWeek('2019-01-21', '2019-01-27')
        );
        $this->assertEquals(
            false,
            DateService::datesAreInSameWeek('2019-01-20', '2019-01-27')
        );
    }
}
