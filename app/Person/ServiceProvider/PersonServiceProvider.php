<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\ServiceProvider;

use BirthdayReminder\Person\Manager\PersonManager;
use BirthdayReminder\Person\Manager\IPersonManager;
use BirthdayReminder\Person\Repository\MongoPersonRepository;
use BirthdayReminder\Person\Repository\PersonRepository;
use BirthdayReminder\Person\Service\BirthdayCalculator;
use BirthdayReminder\Person\Service\BirthdayClock;
use BirthdayReminder\Person\Service\ICalculator;
use BirthdayReminder\Person\Service\IClock;
use Illuminate\Support\ServiceProvider;

final class PersonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PersonRepository::class, MongoPersonRepository::class);
        $this->app->bind(IPersonManager::class, PersonManager::class);
        $this->app->bind(ICalculator::class, BirthdayCalculator::class);
        $this->app->bind(IClock::class, BirthdayClock::class);
    }
}
