<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Manager;

use BirthdayReminder\Person\Http\Request\AddPersonRequest;
use BirthdayReminder\Person\Http\Request\PaginationRequest;
use BirthdayReminder\Person\Http\Response\PersonListResponse;
use DateTimeInterface;
use Exception;

interface PersonManagerInterface
{
    /** @throws Exception */
    public function getPersonList(
        PaginationRequest $request,
        ?DateTimeInterface $calculateFrom = null
    ): PersonListResponse;

    /** @throws Exception */
    public function addNewPerson(AddPersonRequest $request): string;
}
