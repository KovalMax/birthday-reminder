<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Controller;

use BirthdayReminder\Person\Http\Request\PaginationRequest;
use BirthdayReminder\Person\Manager\IPersonManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class GetPersonsAction
{
    public function __construct(private IPersonManager $manager)
    {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $paginationRequest = PaginationRequest::fromInput($request->input());
        try {
            $paginated = $this->manager->listPersons($paginationRequest);

            return response()->json($paginated);
        } catch (Exception) {
            return response()->json('Failed to get person list', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
