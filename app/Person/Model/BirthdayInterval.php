<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Model;

final class BirthdayInterval implements IBirthdayInterval
{
    public function __construct(private int $months, private int $days, private int $age)
    {
    }

    public function months(): int
    {
        return $this->months;
    }

    public function days(): int
    {
        return $this->days;
    }

    public function age(): int
    {
        return $this->age;
    }

    public function isBirthday(): bool
    {
        return $this->days === 0 && $this->months === 0;
    }
}
