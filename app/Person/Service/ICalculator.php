<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use BirthdayReminder\Person\Model\IBirthdayInterval;
use DateTimeInterface;
use DateTimeZone;

interface ICalculator
{
    public function calculateBirthdayInterval(DateTimeInterface $birthday): IBirthdayInterval;

    public function hoursUntilTheEndOfDay(DateTimeZone $timeZone): int;
}
