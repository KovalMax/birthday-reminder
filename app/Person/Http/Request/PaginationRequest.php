<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Http\Request;

use BirthdayReminder\Person\Http\RequestValueObject;
use Illuminate\Support\Facades\Validator;

final class PaginationRequest implements RequestValueObject
{
    public const DEFAULT_LIMIT = 50;
    public const DEFAULT_PAGE  = 1;

    private static array $rules = [
        'page' => ['int', 'min:1'],
        'limit' => ['int', 'min:1', 'max:100'],
    ];

    private function __construct(private int $page, private int $limit, private int $offset)
    {
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public static function fromInput(array $input): self
    {
        self::validate($input);

        $page = self::getParamAsInteger('page', $input, self::DEFAULT_PAGE);
        $limit = self::getParamAsInteger('limit', $input, self::DEFAULT_LIMIT);

        $offset = ($page - 1) * $limit;

        return new self($page, $limit, $offset);
    }

    public static function validate(array $input): void
    {
        $validator = Validator::make($input, self::$rules);
        $validator->validate();
    }

    private static function getParamAsInteger(string $paramName, array $input, int $default): int
    {
        $param = $input[$paramName] ?? $default;

        return (int) $param;
    }
}
