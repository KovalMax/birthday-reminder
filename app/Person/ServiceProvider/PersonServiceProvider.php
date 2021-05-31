<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\ServiceProvider;

use BirthdayReminder\Person\Manager\PersonManager;
use BirthdayReminder\Person\Manager\PersonManagerInterface;
use BirthdayReminder\Person\Repository\MongoPersonRepository;
use BirthdayReminder\Person\Repository\PersonRepository;
use Illuminate\Support\ServiceProvider;

final class PersonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PersonRepository::class, MongoPersonRepository::class);
        $this->app->bind(PersonManagerInterface::class, PersonManager::class);
    }
}
