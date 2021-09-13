<?php 

require './utils/datePeriod2Calendar.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-8 mx-auto d-flex flex-column justify-content-center">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <form action="" method="POST">
                            <div class="col">
                                <div class="input-group add-habit">
                                    <input type="text" class="form-control" name="habit-name" placeholder="Habit Name" aria-label="Habit Name">
                                    <input type="number" class="form-control" name="habit-duration" aria-label="Habit Duration in Days" value="50">
                                    <button class="btn btn-primary">Add Habit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="habits row mt-5">
                    <?php foreach ($habits as $habit): ?>
                        <?php $calendar = datePeriod2Calendar($habit->getDatePeriod()) ?>
                        <div class="col-md-6">
                            <div class="habit">
                                <header class="habit-header">
                                    <p class="habit-title"><?php echo $habit->getName(); ?></p>
                                </header>
                                <div class="habit-body">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan="7">
                                                    <?php echo $habit->getStart()->format('F') . ' - ' . $habit->getEnd()->format('F') ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Mon</th>
                                                <th scope="col">Tue</th>
                                                <th scope="col">Wed</th>
                                                <th scope="col">Thu</th>
                                                <th scope="col">Fri</th>
                                                <th scope="col">Sat</th>
                                                <th scope="col">Sun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($calendar as $week): ?>
                                                <tr>
                                                    <?php foreach ($week as $day): ?>
                                                        <td><?php echo $day['day'] ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="./assets/index.js"></script>
</body>
</html>