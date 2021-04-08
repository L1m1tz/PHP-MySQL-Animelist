<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {


    // Prepare a select statement
    $stmt1 = $pdo->query("SELECT * FROM season_statuses");
    $season_statuses = $stmt1->fetchAll();

    $stmt = $pdo->query("SELECT * FROM type");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
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

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3"><u>View Record</u></h1>
                    <p><a href="index.php?page=season-list&id=<?php echo $_GET['id'] ?>" class="btn btn-primary">Back</a></p>
                    <hr>
                    <div class="row justify-content-center">

                        <div class="col-6">
                            <h3>Anime Name</h3>
                            <p><b><?php echo $row["anime_name"]; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>English Name</label>
                            <p><b><?php echo $row["dub_name"]; ?></b></p>
                        </div>
                        <div class="col-12">
                            <label>Season Number</label>
                            <p><b><?php echo $row["season_no"]; ?></b></p>
                        </div>
                        <div class="col-12">
                            <label>Season Plot</label>
                            <p><b><?php echo $row["description"]; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>Release Date</label>
                            <p><b><?php echo $row["release_date"]; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>Season Status</label>
                            <p><b><?php if ($row["season_status_id"] == 1) {
                                        echo "Ongoing";
                                    } else {
                                        echo "Complete";
                                    }; ?></b></p>
                        </div>

                        <div class="col-6">
                            <label>Anime Type</label>
                            <p><b><?php 
                                
                            echo $types[$row["type_id"]-1]['name']; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>Licensors</label>
                            <p><b><?php echo $row["licensors"]; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>Rating</label>
                            <p><b><?php echo $row["rating"]; ?></b></p>
                        </div>
                        <div class="col-6">
                            <label>link</label>
                            <p><b><a href="<?php echo $row["link"]; ?>"> <?php echo $row["link"]; ?></a></b></p>
                        </div>

                        <p><a href="index.php?page=season-list&id=<?php echo $_GET['id'] ?>" class="btn btn-primary">Back</a></p>
                    </div>
                </div>
            </div>
        </div>