<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use DateTimeInterface;
use DateTimeZone;
use Exception;

interface IClock
{
    /** @throws Exception */
    public function now(DateTimeZone $timeZone): DateTimeInterface;
}
