<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Controller;

use BirthdayReminder\Person\Http\Request\AddPersonRequest;
use BirthdayReminder\Person\Manager\IPersonManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class AddPersonAction
{
    public function __construct(private IPersonManager $manager)
    {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $addPerson = AddPersonRequest::fromInput($request->input());
        try {
            return response()->json($this->manager->addNewPerson($addPerson));
        } catch (Exception) {
            return response()->json('Failed to add new person', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
