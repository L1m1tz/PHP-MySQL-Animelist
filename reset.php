<?php
require_once 'config.php';
require_once 'data/init.php';

$result  = $pdo->exec($resetSql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titile ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-6">
                <?php echo $result ? "<p>Database Sucessfully reset</p>" : '<p>Something went wrong!</p>' ?>

                <button type="button" class="btn btn-success"><a href="index.php?show-list">Back to Animelist</a></button>

            </div>
        </div>
    </div>
</body>

</html>