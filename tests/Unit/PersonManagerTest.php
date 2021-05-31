<?php

declare(strict_types=1);

namespace Tests\Unit;

use BirthdayReminder\Person\Http\Request\PaginationRequest;
use BirthdayReminder\Person\Http\Response\PersonListResponse;
use BirthdayReminder\Person\Http\Response\PersonResponse;
use BirthdayReminder\Person\Manager\PersonManager;
use BirthdayReminder\Person\Model\BirthdayInterval;
use BirthdayReminder\Person\Model\Person;
use BirthdayReminder\Person\Repository\PersonRepository;
use BirthdayReminder\Person\Service\BirthdayCalculator;
use BirthdayReminder\Person\Service\PersonResponseTransformer;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function count;

final class PersonManagerTest extends TestCase
{
    private PersonRepository|MockObject $repository;
    private PersonManager $manager;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        Validator::shouldReceive('make')
            ->once()
            ->with(
                [],
                [
                    'page' => ['int', 'min:1'],
                    'limit' => ['int', 'min:1', 'max:100'],
                ]
            )
            ->andReturn($this->createMock(\Illuminate\Validation\Validator::class));
    }

    public function getPersonListDataProvider(): iterable
    {
        $request = PaginationRequest::fromInput([]);
        $person = Person::create('Joe', 'Europe/Kiev', '1990-01-30');
        $listPersons = [$person];
        $countPersons = count($listPersons);

        yield '5 days until birthday' => [
            'request' => $request,
            'listPersons' => $listPersons,
            'countPersons' => $countPersons,
            'calculateFrom' => new DateTime('2000-01-25'),
            'expectedResult' => new PersonListResponse(
                1,
                1,
                new PersonResponse(
                    $person->name,
                    $person->birthday->format('Y-m-d'),
                    $person->timezone,
                    'Joe is 9 years old in 0 months, 5 days in Europe/Kiev',
                    new BirthdayInterval(0, 5, 9)
                )
            ),
        ];

        yield '5 hours until the end of the birthday' => [
            'request' => $request,
            'listPersons' => $listPersons,
            'countPersons' => $countPersons,
            'calculateFrom' => new DateTime('2000-01-30 19:00:00'),
            'expectedResult' => new PersonListResponse(
                1,
                1,
                new PersonResponse(
                    $person->name,
                    $person->birthday->format('Y-m-d'),
                    $person->timezone,
                    'Joe is 10 years old today (5 hours remaining in Europe/Kiev)',
                    new BirthdayInterval(0, 0, 10)
                )
            ),
        ];

        yield 'birthday next year' => [
            'request' => $request,
            'listPersons' => $listPersons,
            'countPersons' => $countPersons,
            'calculateFrom' => new DateTime('2000-01-31'),
            'expectedResult' => new PersonListResponse(
                1,
                1,
                new PersonResponse(
                    $person->name,
                    $person->birthday->format('Y-m-d'),
                    $person->timezone,
                    'Joe is 10 years old in 11 months, 30 days in Europe/Kiev',
                    new BirthdayInterval(11, 30, 10)
                )
            ),
        ];
    }

    /** @dataProvider getPersonListDataProvider */
    public function testGetPersonList(
        PaginationRequest $request,
        array $listPersons,
        int $countPersons,
        DateTimeInterface $calculateFrom,
        PersonListResponse $expectedResult
    ): void {
        $this->repository
            ->expects($this->once())
            ->method('listPersons')
            ->with($request->limit(), $request->offset())
            ->willReturn($listPersons);

        $this->repository
            ->expects($this->once())
            ->method('countPersons')
            ->with()
            ->willReturn($countPersons);

        $testCaseResult = $this->manager->getPersonList($request, $calculateFrom);

        $this->assertEquals($expectedResult, $testCaseResult);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(PersonRepository::class);
        $this->manager = new PersonManager(
            $this->repository,
            new PersonResponseTransformer(new BirthdayCalculator())
        );
    }
}

