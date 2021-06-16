<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Model;

interface IBirthdayInterval
{
    public function months(): int;

    public function days(): int;

    public function age(): int;

    public function isBirthday(): bool;
}
