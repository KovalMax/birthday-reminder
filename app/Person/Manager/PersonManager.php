<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Manager;

use BirthdayReminder\Person\Http\Request\AddPersonRequest;
use BirthdayReminder\Person\Http\Request\PaginationRequest;
use BirthdayReminder\Person\Http\Response\PersonListResponse;
use BirthdayReminder\Person\Model\Person;
use BirthdayReminder\Person\Repository\PersonRepository;
use BirthdayReminder\Person\Service\PersonResponseTransformer;
use DateTimeInterface;

final class PersonManager implements PersonManagerInterface
{
    public function __construct(private PersonRepository $repository, private PersonResponseTransformer $transformer)
    {
    }

    public function getPersonList(
        PaginationRequest $request,
        ?DateTimeInterface $calculateFrom = null
    ): PersonListResponse {
        $result = new PersonListResponse($this->repository->countPersons(), $request->page());
        foreach ($this->repository->listPersons($request->limit(), $request->offset()) as $person) {
            $result->addPerson($this->transformer->transform($person, $calculateFrom));
        }

        return $result;
    }

    public function addNewPerson(AddPersonRequest $request): string
    {
        $person = Person::create($request->name(), $request->timezone(), $request->birthday());
        $this->repository->addPerson($person);

        return $person->id;
    }
}
