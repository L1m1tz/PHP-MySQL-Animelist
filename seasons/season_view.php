<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "../config.php";

    // Prepare a select statement
    $sql = "SELECT *,
            seasons.id AS season_id
            FROM seasons
            JOIN anime_show on anime_show.id = seasons.show_id
            WHERE seasons.id = :show_id";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":show_id", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve individual field value
                $anime_name = $row["anime_name"];
                $description = $row["description"];
                $season_no = $row["season_no"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h1 class="mt-5 mb-3"><u>View Record</u></h1>

                    <div class="form-group">
                        <h3>Anime Name</h3>
                        <p><b><?php echo $row["anime_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>English Name</label>
                        <p><b><?php echo $row["dub_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Season Number</label>
                        <p><b><?php echo $row["season_no"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Season Plot</label>
                        <p><b><?php echo $row["description"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Season Status</label>
                        <p><b><?php echo $row["season_status_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Release Date</label>
                        <p><b><?php echo $row["release_date"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Anime Type</label>
                        <p><b><?php echo $row["type_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Licensors</label>
                        <p><b><?php echo $row["licensors"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <p><b><?php echo $row["rating"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>link</label>
                        <p><b><a href="<?php echo $row["link"]; ?>"> <?php echo $row["link"]; ?></a></b></p>
                    </div>

                    <p><a href="http://animelist.test/seasons/season_list.php?id=<?php echo $_GET['id']?>" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
</body>

</html>