<?php

namespace App\Service;

use DateTime;

class DateService
{
    public function generateLimitDate(DateTime $startDateTime): DateTime
    {

        $limitDate = clone $startDateTime;
        $limitDate->modify('-1 day');

        return $limitDate;
    }
}