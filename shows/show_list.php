<?php
// Attempt select query execution
$stmt = $pdo->query("SELECT * FROM type");
$types = $stmt->fetchAll();

$sql = "SELECT *
        FROM anime_show";

$showStatement = $pdo->query($sql);

$shows = $showStatement->fetchAll();

//var_dump($shows['type_id']);

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-12 text-center m-5">
      <a href="index.php?page=show-create" class="btn btn-success"><i class="fa fa-plus"></i> Add New Anime</a>
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
                        }
                      }
                      ?></td>

                  <td>
                    <a href="index.php?page=season-list&id=<?php echo $show['id'] ?>" class="mr-3" title="View Anime" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                    <a href="index.php?page=show-create&id=<?php echo $show['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                    <a href="index.php?page=show-delete&id=<?php echo $show['id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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
      <a href="index.php?page=show-create" class="btn btn-success"><i class="fa fa-plus"></i> Add New Anime To List</a>
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
                <th>DB ID</th>
                <th>Anime Name</th>
                <th>Anime Type</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $count = 0;

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
                        }
                      }
                      ?></td>

                  <td>
                    <a href="index.php?page=season-list&id=<?php echo $show['id'] ?>" class="mr-3" title="View Anime" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                    <a href="index.php?page=show-create&id=<?php echo $show['id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                    <a href="index.php?page=show-delete&id=<?php echo $show['id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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

  </div>
</div>