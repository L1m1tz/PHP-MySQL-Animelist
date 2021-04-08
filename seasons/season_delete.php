<?php
// Process delete operation after confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((isset($_POST["season_id"]) && !empty($_POST["season_id"])) &&
        (isset($_POST["show_id"]) && !empty($_POST["show_id"]))
    ) {

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
                header("location: index.php?page=season-list&id=$show_id");
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

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars('index.php?page=season-delete'); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="show_id" value="<?php echo trim($_GET["show_id"]); ?>" />
                            <input type="hiden" name="season_id" value="<?php echo trim($_GET["season_id"]); ?>" />
                            <p>Are you sure you want to delete this anime record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php?page=season-list&id=<?php echo $_GET['show_id'] ?>" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>