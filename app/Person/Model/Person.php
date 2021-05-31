<?php

declare(strict_types=1);

namespace BirthdayReminder\Person\Model;

use DateTime;
use DateTimeInterface;
use Exception;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property DateTimeInterface $birthday
 * @property string $timezone
 */
class Person extends Model
{
    protected $dates = ['birthday'];

    /**
     * @throws Exception
     */
    public static function create(string $name, string $timezone, string $birthday): self
    {
        $model = new self();
        $model->name = $name;
        $model->timezone = $timezone;
        $model->birthday = new DateTime($birthday);

        return $model;
    }
}
