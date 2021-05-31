<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Repository;

use BirthdayReminder\Person\Model\Person;

final class MongoPersonRepository implements PersonRepository
{
    private Person $model;

    public function __construct()
    {
        $this->model = new Person();
    }

    /**
     * @return Person[]
     */
    public function listPersons(int $limit = 0, int $offset = 0): iterable
    {
        $query = $this->model->newQuery();

        if ($limit > 0) {
            $query->limit($limit);
        }
        if ($offset > 0) {
            $query->offset($offset);
        }

        foreach ($query->get() as $item) {
            yield $item;
        }
    }

    public function countPersons(): int
    {
        return $this->model->newQuery()->count();
    }

    public function addPerson(Person $person): void
    {
        $person->save();
    }
}
