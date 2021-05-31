<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Http\Request;

use BirthdayReminder\Person\Http\RequestValueObject;
use Illuminate\Support\Facades\Validator;

final class AddPersonRequest implements RequestValueObject
{
    private static array $rules = [
        'name' => ['bail', 'required', 'string', 'max:128'],
        'birthday' => ['bail', 'required', 'date', 'before:yesterday'],
        'timezone' => ['bail', 'required', 'timezone'],
    ];

    private function __construct(private string $name, private string $birthday, private string $timezone)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function birthday(): string
    {
        return $this->birthday;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }

    public static function fromInput(array $input): self
    {
        self::validate($input);

        return new self(
            $input['name'],
            $input['birthday'],
            $input['timezone']
        );
    }

    public static function validate(array $input): void
    {
        $validator = Validator::make($input, self::$rules);
        $validator->validate();
    }
}
