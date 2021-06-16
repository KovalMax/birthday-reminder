<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Service;

use BirthdayReminder\Person\Http\Response\PersonResponse;
use BirthdayReminder\Person\Model\IBirthdayInterval;
use BirthdayReminder\Person\Model\Person;
use DateTimeZone;
use Exception;

use function sprintf;

final class PersonResponseTransformer
{
    private const BIRTHDAY_REMINDER_TEMPLATE = '%s is %s years old';
    private const TODAY_PART                 = ' today (%d hours remaining in %s)';
    private const UPCOMING_PART              = ' in %d months, %d days in %s';

    public function __construct(private ICalculator $birthdayCalculator)
    {
    }

    /**
     * @throws Exception
     */
    public function transform(Person $person): PersonResponse
    {
        $timezoneBirthday = $person->birthdayWithTimezone();
        $reminder = $this->buildReminder(
            $person->name,
            $timezoneBirthday->getTimezone(),
            $this->birthdayCalculator->calculateBirthdayInterval($timezoneBirthday)
        );

        return new PersonResponse(
            $person->name,
            $person->birthday->format('Y-m-d'),
            $person->timezone,
            ...$reminder
        );
    }

    /**
     * @throws Exception
     */
    private function buildReminder(
        string $name,
        DateTimeZone $timezone,
        IBirthdayInterval $interval
    ): array {
        $reminderMessage = match ($interval->isBirthday()) {
            true => $this->renderMessage(
                self::BIRTHDAY_REMINDER_TEMPLATE . self::TODAY_PART,
                $name,
                $interval->age(),
                $this->birthdayCalculator->hoursUntilTheEndOfDay($timezone),
                $timezone->getName()
            ),
            false => $this->renderMessage(
                self::BIRTHDAY_REMINDER_TEMPLATE . self::UPCOMING_PART,
                $name,
                $interval->age(),
                $interval->months(),
                $interval->days(),
                $timezone->getName()
            )
        };

        return [$reminderMessage, $interval];
    }

    private function renderMessage(string $template, string|int ...$params): string
    {
        return sprintf($template, ...$params);
    }
}
