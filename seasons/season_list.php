<?php
// Include config file
require_once "config.php";

// Attempt select query execution
$sql = "SELECT *,
            seasons.id AS season_id
            FROM seasons
            JOIN anime_show on anime_show.id = seasons.show_id";

$seasonStatement = $pdo->query($sql);

$seasons = $seasonStatement->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
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
            <h2 class="pull-left">Anime Details</h2>
            <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Season</a>
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
                    <th>Type</th>
                    <th>rating</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($seasons as $season) { ?>
                    <tr>
                      <td><?php echo $season['show_id'] ?></td>
                      <td><?php echo $season['anime_name'] ?></td>
                      <td><?php echo $season['description'] ?></td>
                      <td><?php echo $season['season_no'] ?></td>
                      <td><?php echo $season['release_date'] ?></td>
                      <td><?php echo $season['type_id'] ?></td>
                      <td><?php echo $season['rating'] ?></td>
                      <td>
                        <a href="read.php?id=<?php echo $season['show_id'] ?>" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                        <a href="update.php?id=<?php echo $season['show_id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                        <a href="delete.php?id=<?php echo $season['show_id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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