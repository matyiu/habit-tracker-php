<?php

namespace App\Models;

use App\CSVStorage;
use App\Habit;

class HabitModel
{
    private $storage;

    public function __construct(CSVStorage $storage)
    {
        $this->storage = $storage;
    }

    public function getAll()
    {
        $data = $this->storage->read('habits.csv');

        $habits = [];
        foreach ($data as $value) {
            $habit = new Habit($value['name'], $value);
            array_push($habits, $habit);
        }

        return $habits;
    }
}