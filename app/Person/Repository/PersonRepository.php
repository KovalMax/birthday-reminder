<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Repository;

use BirthdayReminder\Person\Model\Person;

interface PersonRepository
{
    /**
     * @return Person[]
     */
    public function listPersons(int $limit = 0, int $offset = 0): iterable;

    public function countPersons(): int;

    public function addPerson(Person $person): void;
}
