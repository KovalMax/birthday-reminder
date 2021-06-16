<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

final class BirthdayClock implements IClock
{
    public function now(DateTimeZone $timeZone): DateTimeInterface
    {
        return new DateTime('now', $timeZone);
    }
}
