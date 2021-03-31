<?php
// Include config file
require_once "../config.php";


//call from database
$stmt = $pdo->query("SELECT * FROM type");
$types = $stmt->fetchAll();

$stmt2 = $pdo->query("SELECT * FROM season_statuses");
$season_statuses = $stmt2->fetchAll();

$stmt3 = $pdo->query("SELECT * FROM genre");
$genres = $stmt3->fetchAll();



/**
 * Undocumented function
 *
 * @param string $id
 * @param array $validAliasModel
 * @return boolean
 */
function checkIds(string $id, array $validAliasModel): bool
{
    $validItemArray = array_filter($validAliasModel, function ($model) use ($id) {
        return $model['id'] === $id;
    });

    return count($validItemArray) > 0;
}

// Define variables and initialize with empty values
$anime_name = $dub_name = $season_no = $description  = $release_date = $rating = $licensors = $link = $type = "";
$anime_name_err = $dub_name_err = $season_no_err = $description_err = $release_date_err = $season_statuses_err = $rating_err = $licensors_err = $link_err = $new_release_date_err = $type_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate anime name
    $input_anime_name = trim($_POST["anime_name"]);
    if (empty($input_anime_name)) {
        $anime_name_err = "Please enter anime name.";
    } elseif (!filter_var($input_anime_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $anime_name_err = "Please enter a valid anime name.";
    } else {
        $anime_name = $input_anime_name;
    }

    // Validate type
    $input_type = isset($_POST["type"]) ? trim($_POST['type']) : null;
    if (empty($input_type) && !checkIds($input_type, $types)) {
        $type_err = "Please select a valid type.";
    } else {
        $type = $input_type;
    }

    //Validate season genre
    $input_genre = isset($_POST["genre"]) ? trim($_POST['genre']) : null;
    if (empty($input_genre) && !checkIds($input_genre, $types)) {
        $genre_err = "Please select a valid type.";
    } else {
        $genre = $input_genre;
    }


    // Check input errors before inserting in database
    if (empty($anime_name_err) && empty($type_err) && empty($genre_err)) {

        // Set parameters
        $param_anime_name = $anime_name;
        $param_dub_genre = $genre;
        $param_type = $type;

        // Prepare an insert statement
        $sql = "INSERT INTO anime_show (anime_name, type_id) VALUES (:anime_name, :type_id)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":anime_name", $param_anime_name);
            $stmt->bindParam(":type_id", $param_type);
        }

        $showCreated = $stmt->execute();
        

        // Attempt to execute the prepared statement
        if ($showCreated) {
            // Records created successfully. Redirect to landing page
            header("location: show_list.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }


        // Close statement
        unset($stmt);
        unset($stmt2);
    }

    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Anime Record</h2>
                    <p>Please fill this form and submit to add anime record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">

                            <!--Anime name-->
                            <label>Anime Title</label>
                            <input type="text" name="anime_name" value="<?php echo $anime_name; ?>" class="form-control <?php echo (!empty($anime_name_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $anime_name_err; ?></span>
                        </div>

                        <!--Anime Type-->
                        <div class="form-group">
                            <label>Anime Type</label>
                            <select name="type" class="form-select" aria-label="Default select example">


                                <?php foreach ($types as $type) : ?>
                                    <option value="<?= $type['id']; ?>"><?= $type['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Genre</label>
                            <select name="genre" class="form-select" aria-label="Default select example">

                                <?php foreach ($genres as $genre) : ?>
                                    <option value="<?= $genre['id']; ?>"><?= $genre['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>