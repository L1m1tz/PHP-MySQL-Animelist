<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
  // Include config file
  require_once "../config.php";

  // Set parameters
  $param_id = trim($_GET["id"]);

  // Prepare a select statement
  $sql = "SELECT * FROM seasons WHERE show_id = :id";

  if ($stmt = $pdo->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id);

    $stmt->execute();
    $seasons = $stmt->fetchAll();
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
  <title>Animelist</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</head>

<body>
  <div class="container">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="mt-5 mb-3 clearfix">
            <h2 class="pull-left">Anime Seasons</h2>
            <a href="season_create.php?show_id=<?php echo $_GET['id'] ?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Add New Season</a>
          </div>
          <?php
          if (!!$seasons) {
            if (count($seasons) > 0) { ?>
              <table class="table table-bordered table-striped table-hover">

                <thead>
                  <tr>
                    <th>#</th>
                    <th>Anime Name</th>
                    <th>Plot</th>
                    <th>Season</th>
                    <th>Release Date</th>
                    <th>rating</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($seasons as $season) { ?>
                    <tr>
                      <td><?php echo $season['id'] ?></td>
                      <td><?php echo $season['dub_name'] ?></td>
                      <td><?php echo $season['description'] ?></td>
                      <td><?php echo $season['season_no'] ?></td>
                      <td><?php echo $season['release_date'] ?></td>
                      <td><?php echo $season['rating'] ?></td>
                      <td>
                        <a href="../seasons/season_view.php?id=<?php echo $season['id'] ?>" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                        <a href="season_create.php?season_id=<?php echo $season['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                        <a href="season_delete.php?season_id=<?php echo $season['id'] ?>&show_id=<?php echo $season['show_id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <div class="alert alert-danger"><em>No records were found.</em></div>
            <?php }
          } else { ?>
            <div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div>
          <?php }

          // Close connection
          unset($pdo);
          ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>