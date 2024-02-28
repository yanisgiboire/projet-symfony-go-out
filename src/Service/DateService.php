<?php

namespace App\Service;

use DateTime;

class DateService
{
    public function __invoke(DateTime $date)
    {
        $now = new DateTime();
        if ($date < $now) {
            return 'La date limite d\'inscription doit être supérieure à la date actuelle';
        }
    }
}