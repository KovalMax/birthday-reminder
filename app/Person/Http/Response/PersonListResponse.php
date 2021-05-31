<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Http\Response;

use JsonSerializable;

final class PersonListResponse implements JsonSerializable
{
    private int $total;
    private int $currentPage;
    private array $persons;

    public function __construct(int $total, int $currentPage, PersonResponse ...$persons)
    {
        $this->total = $total;
        $this->currentPage = $currentPage;
        $this->persons = $persons;
    }

    public function addPerson(PersonResponse $personResponse): void
    {
        $this->persons[] = $personResponse;
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => $this->persons,
            'currentPage' => $this->currentPage,
            'total' => $this->total,
        ];
    }
}
