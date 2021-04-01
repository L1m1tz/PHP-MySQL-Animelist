<?php
// Process delete operation after confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((isset($_POST["season_id"]) && !empty($_POST["season_id"])) &&
        (isset($_POST["show_id"]) && !empty($_POST["show_id"]))
    ) {
        // Include config file
        require_once "../config.php";

        // Prepare a delete statement
        $sql = "DELETE FROM seasons WHERE id = :id";

        $param_id = $_POST['season_id'];
        $show_id = $_POST['show_id'];

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records deleted successfully. Redirect to landing page
                header("location: http://animelist.test/seasons/season_list.php?id=$show_id");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);

        // Close connection
        unset($pdo);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="show_id" value="<?php echo trim($_GET["show_id"]); ?>" />
                            <input type="hiden" name="season_id" value="<?php echo trim($_GET["season_id"]); ?>" />
                            <p>Are you sure you want to delete this anime record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="http://animelist.test/seasons/season_list.php?id=<?php echo $_GET['show_id'] ?>" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>