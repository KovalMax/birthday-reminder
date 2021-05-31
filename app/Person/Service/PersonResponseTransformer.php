<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use BirthdayReminder\Person\Http\Response\PersonResponse;
use BirthdayReminder\Person\Model\BirthdayInterval;
use BirthdayReminder\Person\Model\Person;
use DateTime;
use DateTimeInterface;
use Exception;

use function sprintf;

final class PersonResponseTransformer
{
    private const BIRTHDAY_REMINDER_TEMPLATE = '%s is %s years old';
    private const TODAY_PART                 = ' today (%d hours remaining in %s)';
    private const UPCOMING_PART              = ' in %d months, %d days in %s';

    public function __construct(private BirthdayCalculator $birthdayCalculator)
    {
    }


    /**
     * @throws Exception
     */
    public function transform(Person $person, ?DateTimeInterface $calculateFrom = null): PersonResponse
    {
        $interval = $this->birthdayCalculator->calculateBirthdayInterval($person->birthday, $calculateFrom);
        $reminder = $this->buildReminder($person->name, $person->timezone, $interval, $calculateFrom);

        return new PersonResponse(
            $person->name,
            $person->birthday->format('Y-m-d'),
            $person->timezone,
            $reminder,
            $interval
        );
    }

    /**
     * @throws Exception
     */
    private function buildReminder(
        string $name,
        string $timezone,
        BirthdayInterval $interval,
        ?DateTimeInterface $calculateFrom = null
    ): string {
        return match ($interval->isBirthday()) {
            true => $this->renderMessage(
                self::BIRTHDAY_REMINDER_TEMPLATE . self::TODAY_PART,
                $name,
                $interval->age(),
                $this->birthdayCalculator->hoursUntilTheEndOfDay($calculateFrom),
                $timezone
            ),
            false => $this->renderMessage(
                self::BIRTHDAY_REMINDER_TEMPLATE . self::UPCOMING_PART,
                $name,
                $interval->age(),
                $interval->months(),
                $interval->days(),
                $timezone
            )
        };
    }

    private function renderMessage(string $template, string|int ...$params): string
    {
        return sprintf($template, ...$params);
    }
}
