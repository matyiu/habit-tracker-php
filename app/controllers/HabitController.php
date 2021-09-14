<?php

namespace App\Controllers;

use App\CSVStorage;

class HabitController
{
    public function index()
    {
        $csvStorage = new CSVStorage;
        $data = $csvStorage->read('habits.csv');

        return var_dump($data);
    }
}