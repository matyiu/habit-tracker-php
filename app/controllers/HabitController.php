<?php

namespace App\Controllers;

use App\CSVStorage;
use App\Models\HabitModel;

class HabitController
{
    public function index()
    {
        $model = new HabitModel(new CSVStorage);
        $habits = $model->getAll();

        return view('index.view.php', ['habits' => $habits]);
    }

    public function store($request)
    {
        return json_encode($request);
    }
}