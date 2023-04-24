<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("location:login.php");
} ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title>Promoters</title>

  <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
  <meta name="author" content="pixelcave">
  <meta name="robots" content="noindex, nofollow">

  <!-- Open Graph Meta -->
  <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework">
  <meta property="og:site_name" content="Dashmix">
  <meta property="og:description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:image" content="">

  <!-- Icons -->
  <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
  <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
  <!-- END Icons -->

  <!-- Stylesheets -->
  <!-- Page JS Plugins CSS -->
  <link rel="stylesheet" href="assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">

  <!-- Dashmix framework -->
  <link rel="stylesheet" id="css-main" href="assets/css/dashmix.min.css">

  <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
  <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
  <!-- END Stylesheets -->
</head>

<body>
  <?php
  include "dbconnect.php";
  if (isset($_POST['add_anpromoter'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "INSERT INTO `promoter`(`name`, `email`, `contact`, `enrolled`, `address`) VALUES ('$name', '$email', '$contact', '1', '$address')";
    $results = $conn->query($sql);
    if ($results) {

      //Add a user
      $password = time();
      $userId = mysqli_insert_id($conn);
      $sql = "INSERT INTO `users`(`username`, `password`, `role`, `user_id`) VALUES ('$name', '$password', '2', '$userId' )";
      $conn->query($sql);
      
      //insert into logs
      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Added new promoter called $name',  '$user')";
      $conn->query($sql);
    }
  }
  if (isset($_POST['edit_promoter'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['canontact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $sql = "UPDATE `promoter` SET `name`='$name',`email`='$email',`contact`='$contact',`address`='$address' WHERE promoter.id = '$id'";
    $results = $conn->query($sql);
    if ($results) {
      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Edited promoter details of $name',  '$user')";
      $conn->query($sql);
    }
  }

  if (isset($_POST['delete_promoter'])) {
    $id = $_POST['id'];
    $sql = "UPDATE `promoter` SET `status`= 0 WHERE id = $id";
    $results = $conn->query($sql);
    if ($results) {
      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Deleted a promoter',  '$user')";
      $conn->query($sql);
    }
  }
  ?>
  <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
    <?php include "sidebar.php" ?>
    <!-- END Sidebar -->

    <!-- Header -->
    <?php include "header.php" ?>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
      <!-- Page Content -->
      <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
          <div class="block-content block-content-full">
            <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
              <thead>
                <div class="container">
                  <div class=" d-flex justify-content-between">
                    <h3>Promoters</h3>
                    <span>
                      <button type="button" class="js-swal-confirm btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomer">
                        <i class="fa fa-plus text-white me-1"></i> New Promoter
                      </button>
                    </span>
                  </div>
                </div>
                <tr>
                  <th hidden></th>
                  <th hidden></th>
                  <th hidden></th>
                  <th hidden></th>
                  <th hidden></th>

                  <th>Name</th>
                  <th>Contact</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Number of Events</th>
                  <th>Enrollement Status</th>
                  <th>Action</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM `promoter` WHERE status=1";
                $promoters = $conn->query($sql);
                while ($promoter = $promoters->fetch_assoc()) {
                ?>
                  <tr>
                    <td hidden><?php echo $promoter['id'] ?></td>
                    <td hidden><?php echo $promoter['name'] ?></td>
                    <td hidden><?php echo $promoter['contact'] ?></td>
                    <td hidden><?php echo $promoter['email'] ?></td>
                    <td hidden><?php echo $promoter['address'] ?></td>
                    <td class="text-center"><?php echo $promoter['name'] ?></td>
                    <td class="text-center">
                      <?php echo $promoter['contact'] ?>
                    </td>
                    <td class="text-center">
                      <?php echo $promoter['email'] ?>
                    </td>
                    <td class="text-center">
                      <?php echo $promoter['address'] ?>
                    </td>
                    <td class="text-center">
                      2000
                    </td>
                    <td>
                      <button class="btn btn-switch" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                        Toggle button
                      </button>
                    </td>
                    <td>
                      <div class="input-group flex-nowrap">
                        <button class="btn btn-info edit_btn" data-bs-toggle='modal' data-bs-target='#editCustomer'><i class="fa-sharp fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-danger btn delete_btn " data-bs-toggle='modal' data-bs-target='#deleteCustomer'><i class="fa-sharp fa-solid fa-trash "></i></button>
                      </div>
                    </td>
                  </tr>

                <?php } ?>

              </tbody>
            </table>
          </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
      </div>
      <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <?php include "footer.php" ?>
    <!-- END Footer -->
  </div>
  <!-- END Page Container -->
  <!--Add promoter Modal -->
  <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Promoter</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="js-validation-signin" action="" method="POST">
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="name" placeholder="Promoter">
                <span class="input-group-text">
                  <i class="fa fa-user-circle"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="contact" placeholder="Office Contact">
                <span class="input-group-text">
                  <i class="fa-solid fa-phone"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="email" placeholder="Email">
                <span class="input-group-text">
                  <i class="fa-sharp fa-regular fa-envelope"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="address" placeholder="Official Address">
                <span class="input-group-text">
                  <i class="fa-sharp fa-regular fa-envelope"></i>
                </span>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info" name="add_promoter">Add Promoter</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!--Edit customer Modal -->
  <div class="modal fade" id="editCustomer" tabindex="2" aria-labelledby="editCustomer" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add customer</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="js-validation-signin" action="" method="POST">
            <input type="hidden" class="form-control" id="id" name="id" placeholder="Email">

            <div class="mb-4">customer
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="name" name="name" placeholder="name">
                <span class="input-group-text">
                  <i class="fa fa-user-circle"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="phone" name="contact" placeholder="contact">
                <span class="input-group-text">
                  <i class="fa-solid fa-phone"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                <span class="input-group-text">
                  <i class="fa-sharp fa-regular fa-envelope"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="address" name="address" placeholder="Official Address">
                <span class="input-group-text">
                  <i class="fa-sharp fa-regular fa-envelope"></i>
                </span>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="edit_promoter">Save changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="deleteCustomer" tabindex="-1" aria-labelledby="deleteCustomer" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Customer</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <input type="hidden" name="id" id="id2">
          </div>
          <i class="fa-duotone fa-brake-warning text-danger"></i>
          <h3 class="container">
            Are you sure, you want to delete this promoter!!!!
          </h3>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="delete_promoter"><i class="fa-sharp fa-regular fa-trash"></i>Delete</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  <script src="assets/js/dashmix.app.min.js"></script>

  <!-- jQuery (required for DataTables plugin) -->
  <script src="assets/js/lib/jquery.min.js"></script>

  <!-- Page JS Plugins -->
  <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
  <script src="assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
  <script src="assets/js/plugins/datatables-buttons/buttons.print.min.js"></script>
  <script src="assets/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
  <script src="assets/js/pages/be_tables_datatables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.delete_btn').click(function() {
        var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
        $('#id2').val(id);
      });

      $('.edit_btn').click(function() {
        var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
        $('#id').val(id);
        var name = $(this).closest("tr").find('td:nth-child(2)').text().trim();
        $('#name').val(name);
        var contact = $(this).closest("tr").find('td:nth-child(3)').text().trim();
        $('#phone').val(contact);
        var email = $(this).closest("tr").find('td:nth-child(4)').text().trim();
        $('#email').val(email);
        var address = $(this).closest("tr").find('td:nth-child(5)').text().trim();
        $('#address').val(address);
      });

    });
  </script>
</body>

</html>