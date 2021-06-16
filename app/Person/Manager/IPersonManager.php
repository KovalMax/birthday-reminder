<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Manager;

use BirthdayReminder\Person\Http\Request\AddPersonRequest;
use BirthdayReminder\Person\Http\Request\PaginationRequest;
use BirthdayReminder\Person\Http\Response\PersonListResponse;
use Exception;

interface IPersonManager
{
    /** @throws Exception */
    public function listPersons(PaginationRequest $request): PersonListResponse;

    /** @throws Exception */
    public function addNewPerson(AddPersonRequest $request): string;
}
