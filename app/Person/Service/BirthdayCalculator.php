<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use BirthdayReminder\Person\Model\BirthdayInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;

final class BirthdayCalculator
{
    /**
     * @throws Exception
     */
    public function calculateBirthdayInterval(
        DateTimeInterface $birthday,
        ?DateTimeInterface $calculateFrom = null
    ): BirthdayInterval {
        $now = $calculateFrom ?? new DateTime('now', $birthday->getTimezone());
        $now = clone $now;
        // Reset time to 00:00 - to do all calculations based on day basis no need to involve time part
        $now->setTime(0, 0);

        $age = $now->diff($birthday)->y;
        // set year to current year - to do all calculations
        $birthdayAdjusted = (new DateTime($now->format('Y-m-d'), $birthday->getTimezone()))
            ->setDate(
                (int) $now->format('Y'),
                (int) $birthday->format('m'),
                (int) $birthday->format('d')
            );

        // If birthday already passed this year set date to next year to calculate interval until next birthday
        if ($birthdayAdjusted < $now) {
            $birthdayAdjusted = (new DateTime($now->format('Y-m-d'), $birthday->getTimezone()))->setDate(
                (int) $now->format('Y') + 1,
                (int) $birthday->format('m'),
                (int) $birthday->format('d')
            );
        }

        $interval = $now->diff($birthdayAdjusted);

        return new BirthdayInterval($interval->m, $interval->d, $age);
    }

    /**
     * @throws Exception
     */
    public function hoursUntilTheEndOfDay(DateTimeZone $timeZone, ?DateTimeInterface $calculateFrom = null): int
    {
        $today = $calculateFrom ?? new DateTime('now', $timeZone);

        $tomorrow = clone $today;
        $tomorrow->modify('tomorrow');

        $hoursLeftInTheDay = ($tomorrow->getTimestamp() - $today->getTimestamp()) / 60 / 60;

        return (int) $hoursLeftInTheDay;
    }
}
