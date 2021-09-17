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

    // Need to refactor this so that is not coupled to CSVStorage class
    public function saveHabit(Habit $habit, int $id = 0) {
        $habits = $this->getAll();

        if ($id === 0) {
            array_push($habits, $habit);
        } else {
            $oldHabit = array_filter($habits, function($elm) use($habit) {
                return $elm->getId() === $habit->getId();
            })[0];
            $oldHabitIndex = array_search($oldHabit, $habits);
            array_splice($habits, $oldHabitIndex, 1, $habit);
        }

        $this->storage->write('habits.csv', $this->convertHabitsToArray($habits));
    }

    private function convertHabitsToArray(array $habits)
    {
        return array_map(function($habit) {
            return $habit->toArray();
        }, $habits);
    }
}