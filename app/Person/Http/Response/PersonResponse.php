<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Http\Response;

use BirthdayReminder\Person\Model\BirthdayInterval;
use JsonSerializable;

final class PersonResponse implements JsonSerializable
{
    public function __construct(
        private string $name,
        private string $birthday,
        private string $timezone,
        private string $message,
        private BirthdayInterval $interval
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'birthday' => $this->birthday,
            'timezone' => $this->timezone,
            'isBirthday' => $this->interval->isBirthday(),
            'interval' => [
                'm' => $this->interval->months(),
                'd' => $this->interval->days(),
            ],
            'message' => $this->message,
        ];
    }
}
