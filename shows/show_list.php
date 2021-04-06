<?php
// Include config file
require_once "../config.php";

// Attempt select query execution
$stmt = $pdo->query("SELECT * FROM type");
$types = $stmt->fetchAll();

$sql = "SELECT *
        FROM anime_show";

$showStatement = $pdo->query($sql);

$shows = $showStatement->fetchAll();

//var_dump($shows['type_id']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
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
        <div class="col-12 text-center m-5">
          <a href="show_create.php" class="btn btn-success"><i class="fa fa-plus"></i> Add New Anime</a>
        </div>
        <div class="col-md-12">
          <div class="mt-5 mb-3 clearfix">
            <h2 class="pull-left">Anime Details</h2>

          </div>
          <?php
          if (!!$shows) {
            if (count($shows) > 0) { ?>
              <table class="table table-bordered table-striped table-hover">

                <thead>
                  <tr>
                    <th>#</th>
                    <th>DB ID</th>
                    <th>Anime Name</th>
                    <th>Anime Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $count = 0;
                  $loop = 0;

                  foreach ($shows as $show) {
                    $count++;


                  ?>
                    <tr>
                      <td><?php echo $count ?></td>
                      <td><?php echo $show['id'] ?></td>
                      <td><?php echo $show['anime_name'] ?></td>
                      <td><?php
                          foreach ($types as $typeOption) {
                            if ($typeOption['id'] == $show['type_id']) {
                              $value =  $typeOption['name'];
                              echo $value;
                            }}
                            ?></td>

                      <td>
                        <a href="../seasons/season_list.php?id=<?php echo $show['id'] ?>" class="mr-3" title="View Anime" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                        <a href="show_create.php?id=<?php echo $show['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                        <a href="show_delete.php?id=<?php echo $show['id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                      </td>
                    </tr>
                <?php 
                        } ?>
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

        <div class="col-12 text-center m-5">
          <a href="show_create.php" class="btn btn-success"><i class="fa fa-plus"></i> Add New Anime</a>
        </div>
        <div class="col-md-12">
          <div class="mt-5 mb-3 clearfix">
            <h2 class="pull-left">Watched Anime</h2>

          </div>
          <?php
          if (!!$shows) {
            if (count($shows) > 0) { ?>
              <table class="table table-bordered table-striped table-hover">

                <thead>
                  <tr>
                    <th>#</th>
                    <th>Anime Name</th>
                    <th>Anime Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($shows as $show) { ?>
                    <tr>
                      <td><?php echo $show['id'] ?></td>
                      <td><?php echo $show['anime_name'] ?></td>
                      <td><?php echo $show['type_id'] ?></td>

                      <td>
                        <a href="../seasons/season_list.php?id=<?php echo $show['id'] ?>" class="mr-3" title="View Anime" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                        <a href="show_create.php?id=<?php echo $show['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                        <a href="show_delete.php?id=<?php echo $show['id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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