<?php

namespace App\Controllers;

use App\CSVStorage;
use App\Habit;
use App\Models\HabitModel;

class HabitController
{
    private HabitModel $model;

    public function __construct()
    {
        $this->model = new HabitModel(new CSVStorage);
    }

    public function index()
    {
        $habits = $this->model->getAll();

        return view('index.view.php', ['habits' => $habits]);
    }

    public function store($request)
    {
        $habit = new Habit($request['name'], [
            'duration' => (int) $request['duration'],
        ]);
        $this->model->saveHabit($habit);

        return $this->sendResponse($habit->toArray(), 'Habit saved succesfully');
    }

    private function sendResponse($data, $messsage)
    {
        return json_encode([
            'success' => true,
            'data' => $data,
            'message' => $messsage,
        ]);
    }
}