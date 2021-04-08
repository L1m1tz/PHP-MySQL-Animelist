<?php


//call from database
$stmt1 = $pdo->query("SELECT * FROM season_statuses");
$season_statuses = $stmt1->fetchAll();

$stmt2 = $pdo->query("SELECT * FROM genre");
$genres = $stmt2->fetchAll();

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
$dub_name = $season_no = $description  = $release_date = $rating = $licensors = $link = "";
$dub_name_err = $season_no_err = $description_err = $release_date_err = $season_statuses_err = $rating_err = $licensors_err = $link_err = $new_release_date_err = "";
$show_id = null;
$season_id = null;

//check if ID is set
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['season_id'])) {

        $sql = "SELECT * FROM seasons WHERE id = :id";

        $param_id = $_GET['season_id'];

        if ($stmt3 = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt3->bindParam(":id", $param_id);
        }

        $stmt3->execute();
        $season = $stmt3->fetch(PDO::FETCH_ASSOC);

        $dub_name = $season['dub_name'];
        $season_no = $season['season_no'];
        $description = $season['description'];
        $release_date = $season['release_date'];
        $rating = $season['rating'];
        $licensors = $season['licensors'];
        $link = $season["link"];
        $season_id = $_GET['season_id'];
        $show_id = $season['show_id'];
    } elseif (isset($_GET['show_id'])) {
        $show_id = $_GET['show_id'];
    }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $show_id = isset($_POST['show_id']) ? $_POST['show_id'] : null;
    $season_id = isset($_POST['season_id']) ? $_POST['season_id'] : null;

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

    // Validate season status
    $input_season_status = isset($_POST["season_status"]) ? trim($_POST['season_status']) : null;
    if (empty($input_season_status) && !checkIds($input_season_status, $season_statuses)) {
        $season_statuses_err     = "Please select a valid type.";
    } else {
        $season_status = $input_season_status;
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
    if (empty($dub_name_err) && empty($season_no_err) && empty($description_err) && empty($release_date_err) && empty($season_statuses_err) &&  empty($rating_err) && empty($licensors_err) && empty($link_err)) {

        // Set parameters
        $param_dub_name = $dub_name;
        $param_season_no = $season_no;
        $param_description = $description;
        $param_release_date = $release_date;
        $param_season_status = $season_status;
        $param_rating = $rating;
        $param_licensors = $licensors;
        $param_link = $link;

        //check if its for updating
        if (isset($season_id) && $season_id !== '') {

            // Perform update sql query
            $sql = 'UPDATE seasons SET dub_name = :dub_name, season_no = :season_no, description =:description ,season_status_id = :season_status_id ,release_date = :release_date , rating = :rating, licensors =:licensors, link = :link  WHERE id = :season_id ';

            if ($stmt = $pdo->prepare($sql)) {

                $stmt->bindParam(":season_id", $season_id);
                $stmt->bindParam(":dub_name", $param_dub_name);
                $stmt->bindParam(":season_no", $param_season_no);
                $stmt->bindParam(":description", $param_description);
                $stmt->bindParam(":season_status_id", $param_season_status);
                $stmt->bindParam(":release_date", $param_release_date);
                $stmt->bindParam(":rating", $param_rating);
                $stmt->bindParam(":licensors", $param_licensors);
                $stmt->bindParam(":link", $param_link);
            }
            $seasonEdited = $stmt->execute();

            if ($seasonEdited) {
                header("location: http://animelist.test?page=season-list&id=$show_id");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else {
            // Prepare an insert statement
            $sql2 = "INSERT INTO  seasons (show_id, dub_name, season_no, description, season_status_id, release_date, rating, licensors, link ) 
            VALUES (:show_id, :dub_name, :season_no, :description, :season_status_id, :release_date, :rating, :licensors, :link )";

            if ($stmt3 = $pdo->prepare($sql2)) {
                $stmt3->bindParam(":show_id", $show_id);
                $stmt3->bindParam(":dub_name", $param_dub_name);
                $stmt3->bindParam(":season_no", $param_season_no);
                $stmt3->bindParam(":description", $param_description);
                $stmt3->bindParam(":season_status_id", $param_season_status);
                $stmt3->bindParam(":release_date", $param_release_date);
                $stmt3->bindParam(":rating", $param_rating);
                $stmt3->bindParam(":licensors", $param_licensors);
                $stmt3->bindParam(":link", $param_link);
            }

            $seasonCreated = $stmt3->execute();

            // Attempt to execute the prepared statement
            if ($seasonCreated) {
                // Records created successfully. Redirect to landing page
                header("location: http://animelist.test?page=season-list&id=$show_id");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }


            // Close statement
            unset($stmt);
            unset($stmt3);
        }
    }

    // Close connection
    unset($pdo);
}
?>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Create Anime Record</h2>
                <p>Please fill this form and submit to add anime record to the database.</p>
                <form action="<?php echo htmlspecialchars('index.php?page=season-create'); ?>" method="post">
                    <div class="form-group">


                        <!-- anime dub name-->
                        <div class="form-group">
                            <label>Anime Dub Title</label>
                            <input type="text" name="dub_name" value="<?php echo $dub_name ?>" class="form-control <?php echo (!empty($dub_name_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $dub_name_err; ?></span>
                        </div>

                        <!-- Season number-->
                        <div class="form-group">
                            <label>Season Number</label>
                            <input type="number" name="season_no" value="<?php echo $season_no ?>" class="form-control <?php echo (!empty($season_no_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $season_no_err; ?></span>
                        </div>

                        <!--Details-->
                        <div class="form-group">
                            <label>Plot</label>
                            <textarea name="description" value="<?php echo $description; ?>" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>

                        <!--status-->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="season_status" class="form-select" aria-label="Default select example">

                                <?php foreach ($season_statuses as $season_status) : ?>
                                    <option value="<?= $season_status['id']; ?>"><?= $season_status['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!--release date-->
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" placeholder="yyyy-mm-dd" name="release_date" class="form-control <?php echo (!empty($release_date_err)) ? 'is-invalid' : ''; ?>"><?php echo $release_date; ?></input>
                            <span class="invalid-feedback"><?php echo $release_date_err; ?></span>
                        </div>

                        <!-- rating -->
                        <div class="form-group">
                            <label>Rating</label>
                            <input type="range" name="rating" value="<?php echo $rating; ?>" min="0" max="10" class="form-range <?php echo (!empty($rating_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $rating_err; ?></span>
                        </div>

                        <!-- Licensors -->
                        <div class="form-group">
                            <label>Licensors</label>
                            <input type="text" name="licensors" value="<?php echo $licensors; ?>" class="form-control <?php echo (!empty($licensorss_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $licensors_err; ?></span>
                        </div>

                        <!-- Website Link -->
                        <div class="form-group">
                            <label>Website Link</label>
                            <input type="url" name="link" value="<?php echo $link; ?>" class=" form-control <?php echo (!empty($link_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $link_err; ?></span>
                        </div>

                        <!--
                        <div class="form-group">
                            <label>Type</label>
                            <input name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></input>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Episodes</label>
                            <input name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></input>
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>-->
                        <input name="show_id" style="display: none;" value="<?php echo $show_id ?>">
                        <input name="season_id" style="display: none;" value="<?php echo $season_id ?>">

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php?season-list" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>