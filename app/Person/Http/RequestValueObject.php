<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Http;

use Illuminate\Validation\ValidationException;

interface RequestValueObject
{
    /**
     * @throws ValidationException
     */
    public static function fromInput(array $input): object;

    /**
     * @throws ValidationException
     */
    public static function validate(array $input): void;
}
