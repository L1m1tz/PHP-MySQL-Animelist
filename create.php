<?php
// Include config file
require_once "config.php";

$stmt = $pdo->query("SELECT * FROM type");
$types = $stmt->fetchAll();

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
$anime_name = $dub_name = $season_no = $description = $season_statuses = $release_date = $rating = $licensors = $link = $type = "";
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

    // Validate anime dub title
    $input_dub_name = trim($_POST["dub_name"]);
    if (empty($input_dub_name)) {
        $dub_name_err = "Please enter an address.";
    } else {
        $dub_name = $input_dub_name;
    }

    // Validate season number
    $input_season_no = trim($_POST["season_no"]);
    if (empty($input_season_no)) {
        $salary_err = "Please enter the salary amount.";
    } elseif (!ctype_digit($input_season_no)) {
        $season_no_err = "Please enter a positive integer value.";
    } else {
        $season_no = $input_season_no;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Please enter a Description.";
    } else {
        $description = $input_description;
    }

    // Validate type
    $input_type = isset($_POST["type"]) ? trim($_POST['type']) : null;
    if (empty($input_type) && !checkIds($input_type, $types)) {
        $type_err = "Please select a valid type.";
    } else {
        $type = $input_type;
    }

    // Validate season status
    $input_season_statuses = isset($_POST["season_status"]) ? trim($_POST["season_status"]) : null;
    if (empty($input_season_statuses)) {
        $season_statuses_err = "Please enter Season Status.";
    } else {
        $season_statuses = $input_season_statuses;
    }

    // Validate release_date
    $input_release_date = trim($_POST["release_date"]);
    if (empty($input_release_date) || !preg_match("/\d{4}-([0]\d|[1][0-2])-([0-2]\d|[3][0-1])/", $input_release_date) || strlen($input_release_date) > 10) {
        $release_date_err = "Please enter a release date.";
    } else {
        //$new_release_date = date("YYYY-mm-dd", strtotime($input_release_date));
        $release_date = $input_release_date;
    }

    // Validate rating
    $input_rating = trim($_POST["rating"]);
    if (empty($input_rating)) {
        $rating_err = "Please enter rating.";
    } else {
        $rating = $input_rating;
    }

    // Validate licensors
    $input_licensors = trim($_POST["licensors"]);
    if (empty($input_licensors)) {
        $licensors_err = "Please enter a licensor.";
    } else {
        $licensors = $input_licensors;
    }

    // validate link
    $input_link = trim($_POST["link"]);
    if (empty($_POST["link"])) {
        $link_err = "";
    } else {
        $link = $input_link;
    }
    // if (filter_var($input_link, FILTER_VALIDATE_URL)) {
    //     echo ("$ is a valid URL");
    // } else {
    //     echo ("$input_link is not a valid URL");
    // }


    // Check input errors before inserting in database
    if (empty($anime_name_err) && empty($dub_name_err) && empty($season_no_err) && empty($description_err) && empty($release_date_err) && empty($season_statuses_err) &&  empty($rating_err) && empty($licensors_err) && empty($link_err)) {

        // Set parameters
        $param_anime_name = $anime_name;
        $param_dub_name = $dub_name;
        $param_season_no = $season_no;
        $param_description = $description;
        $param_release_date = $release_date;
        $param_season_statuses = $season_statuses;
        $param_rating = $rating;
        $param_licensors = $licensors;
        $param_link = $link;
        $param_type = $type;

        // Prepare an insert statement
        $sql = "INSERT INTO anime_show (anime_name, type_id) VALUES (:anime_name, :type_id)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":anime_name", $param_anime_name);
            $stmt->bindParam(":type_id", $param_type);
        }

        $showCreated = $stmt->execute();
        $showId = $pdo->lastInsertId();

        $sql2 = "INSERT INTO  seasons (show_id, dub_name, season_no, description, season_status_id, release_date, rating, licensors, link ) 
        VALUES (:show_id, :dub_name, :season_no, :description, :season_status_id, :release_date, :rating, :licensors, :link )";

        if ($stmt2 = $pdo->prepare($sql2)) {
            $stmt2->bindParam(":show_id", $showId);
            $stmt2->bindParam(":dub_name", $param_dub_name);
            $stmt2->bindParam(":season_no", $param_season_no);
            $stmt2->bindParam(":description", $param_description);
            $stmt2->bindParam(":season_status_id", $param_season_statuses);
            $stmt2->bindParam(":release_date", $param_release_date);
            $stmt2->bindParam(":rating", $param_rating);
            $stmt2->bindParam(":licensors", $param_licensors);
            $stmt2->bindParam(":link", $param_link);
        }

        $seasonCreated = $stmt2->execute();

        // Attempt to execute the prepared statement
        if ($showCreated && $seasonCreated) {
            // Records created successfully. Redirect to landing page
            header("location: index.php");
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
                            <input type="text" name="anime_name" class="form-control <?php echo (!empty($anime_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $anime_name; ?>">
                            <span class="invalid-feedback"><?php echo $anime_name_err; ?></span>
                        </div>

                        <!-- anime dub name-->
                        <div class="form-group">
                            <label>Anime Dub Title</label>
                            <textarea name="dub_name" class="form-control <?php echo (!empty($dub_name_err)) ? 'is-invalid' : ''; ?>"><?php echo $dub_name; ?></textarea>
                            <span class="invalid-feedback"><?php echo $dub_name_err; ?></span>
                        </div>

                        <!-- Season number-->
                        <div class="form-group">
                            <label>Season Number</label>
                            <textarea name="season_no" class="form-control <?php echo (!empty($season_no_err)) ? 'is-invalid' : ''; ?>"><?php echo $season_no; ?></textarea>
                            <span class="invalid-feedback"><?php echo $season_no_err; ?></span>
                        </div>

                        <!--Details-->
                        <div class="form-group">
                            <label>Plot</label>
                            <textarea type="text" name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"> <?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>

                        <!--Anime Type-->
                        <label>Anime Type</label>
                        <select name="type" class="form-select" aria-label="Default select example">
                            <option selected value="">Open this select menu</option>

                            <?php foreach ($types as $type) : ?>
                                <option value="<?= $type['id']; ?>"><?= $type['name']; ?></option>
                            <?php endforeach; ?>


                        </select>

                        <br>

                        <!--status-->
                        <div class="form-group">
                            <label>Status</label>
                            <!--<textarea type="checkbox" name="season_status" class="form-control <?php echo (!empty($season_status_err)) ? 'is-invalid' : ''; ?>"><?php echo $season_status; ?></textarea>-->
                            <input type="radio" name="season_status" <?php if (isset($season_status) && $season_status == "1") echo "checked"; ?> value="1"> Ongoing <br>
                            <input type="radio" name="season_status" <?php if (isset($season_status) && $season_status == "2") echo "checked"; ?> value="2"> Complete
                            <span class="invalid-feedback"><?php echo $season_status_err; ?></span>
                        </div>

                        <!--release date-->
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" name="release_date" class="form-control <?php echo (!empty($release_date_err)) ? 'is-invalid' : ''; ?>"><?php echo $release_date; ?></textarea>
                            <span class="invalid-feedback"><?php echo $release_date_err; ?></span>
                        </div>

                        <!--
                            <div class="form-group">
                            <label>Genre</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Episodes</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>-->
                        <div class="form-group">
                            <label>Rating</label>
                            <input type="number" name="rating" class="form-control <?php echo (!empty($rating_err)) ? 'is-invalid' : ''; ?>"><?php echo $rating; ?></textarea>
                            <span class="invalid-feedback"><?php echo $rating_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Licensors</label>
                            <textarea name="licensors" class="form-control <?php echo (!empty($licensorss_err)) ? 'is-invalid' : ''; ?>"><?php echo $licensors; ?></textarea>
                            <span class="invalid-feedback"><?php echo $licensors_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Website Link</label>
                            <textarea name="link" class=" form-control <?php echo (!empty($link_err)) ? 'is-invalid' : ''; ?>"><?php echo $link; ?></textarea>
                            <span class="invalid-feedback"><?php echo $link_err; ?></span>
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