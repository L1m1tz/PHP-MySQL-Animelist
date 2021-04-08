<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

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

<div class="container-fluid">
  <div class="row p-5">
    <div class="col-md-12">
      <div class="mt-5 mb-3 clearfix">
        <h2 class="pull-left">Anime Seasons</h2>
        <a href="index.php?page=season-create&show_id=<?php echo $_GET['id'] ?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Add New Season</a>
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
                  <td style="white-space: nowrap;"><?php echo $season['dub_name'] ?></td>
                  <td><?php echo $season['description'] ?></td>
                  <td><?php echo $season['season_no'] ?></td>
                  <td style="white-space: nowrap;"><?php echo $season['release_date'] ?></td>
                  <td><?php echo $season['rating'] ?></td>
                  <td>
                    <a href="index.php?page=season-view&id=<?php echo $season['id'] ?>" class="mr-3" title="View Anime  " data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                    <a href="index.php?page=season-create&season_id=<?php echo $season['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                    <a href="index.php?page=season-delete&season_id=<?php echo $season['id'] ?>&show_id=<?php echo $season['show_id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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