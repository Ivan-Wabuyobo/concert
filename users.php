<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("location:login.php");
}
include "dbconnect.php";

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title>System users</title>

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
  <!-- Page Container -->

  <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">


    <?php include "sidebar.php";
    include "header.php";
    ?>


  <?php
  if(isset($_POST['suspend'])){
    $id = $_POST['id'];
    $sql = "UPDATE `users` SET `status`='0' WHERE `id` = '$id'";
    $conn->query($sql);
  }

  if(isset($_POST['activate'])){
    $id = $_POST['id'];
    $sql = "UPDATE `users` SET `status`='1' WHERE `id` = '$id'";
    $conn->query($sql);
  }

  if(isset($_POST['change_password'])){
    $id = $_POST['id'];
    $password = $_POST['password'];
    $sql = "UPDATE `users` SET `password`='$password' WHERE `id` = '$id'";
    $conn->query($sql);
  }
  
  ?>
    <!-- Main Container -->
    <main id="main-container">
      <!-- Hero -->
      <div class="bg-body-light">
        <div class="content content-full">
          <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">System Users</h1>
            </nav>
          </div>
        </div>
      </div>
      <!-- END Hero -->

      <!-- Page Content -->
      <div class="content">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
          <div class="block-header block-header-default">

          </div>
          <div class="block-content block-content-full">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
              <thead>
                <tr>
                  <th hidden></th>
                  <th>Username</th>
                  <th class="text-center">Role</th>
                  <th class="text-center">password</th>
                  <th class="text-center">Access</th>
                  <th class="text-center">Registered</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                function time_ago($datetime) {
                  $timestamp = strtotime($datetime);
                  $difference = time() - $timestamp;
               
                  if ($difference < 60) {
                     return $difference . " sec ago";
                  } elseif ($difference < 3600) {
                     return round($difference/60) . " min ago";
                  } elseif ($difference < 86400) {
                     return round($difference/3600) . " hour ago";
                  } elseif ($difference < 31536000) {
                     return round($difference/86400) . " day ago";
                  } else {
                     return round($difference/31536000) . " year ago";
                  }
               }

                $sql = "SELECT *  FROM users ORDER BY id DESC";
                $results = $conn->query($sql);

                while ($user = $results->fetch_assoc()) {

                ?>
                  <tr>
                    <td hidden><?php echo $user['id'];?></td>
                    <td class="text-center"><?php echo $user['username']?></td>
                    <td class="text-center">
                    <?php 
                    $role = $user['role'];
                    if ( $role == 1){

                      echo "Admin";

                    }else if($role == 2){
                      echo "Promoter/Organiser";
                    }else if($role == 3){
                      echo "Client/Attendee";
                    } ?>
                    </td>
                    <td class="text-center">
                      <?php echo $user['password']?>
                    </td>
                    <td class="text-center fw-bold <?php if($user['status'] == 1){echo 'text-success'; }else{echo 'text-danger';}?>">
                      <?php
                      $status = $user['status'];
                      if($status == '1'){
                        echo "Active";
                      }else{
                        echo "Suspended";
                      }
                      ?>
                    </td>
                    <td class="text-center">
                    <?php
                  
                    echo time_ago($user['created']);?>
                    </td>
                    <td class="text-center">
                      <div class="input-group flex-nowrap">
                        <button class="btn btn-info edit_btn" data-bs-toggle='modal' data-bs-target='#change'><i class="fa-sharp fa-solid fa-lock-open d-block"> </i> password</button>
                        <?php if($user['status'] == '1'){?>
                        <button class="btn btn-danger btn delete_btn " data-bs-toggle='modal' data-bs-target='#suspend'><i class="fa-sharp fa-solid fa-trash d-block"> </i>Suspend</button>
                      <?php } else{?>
                        <button class="btn btn-success btn activate_btn " data-bs-toggle='modal' data-bs-target='#activate'><i class="fa-sharp fa-solid fa-check d-block"> </i>Activate</button>
                        <?php }?>
                      <button class="btn btn-warning send_btn" data-bs-toggle='modal' data-bs-target='#change'><i class="fa-solid fa-paper-plane d-block text-white"></i>Send sms</button>

                      </div>

                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- END Dynamic Table Full -->


        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-light">
      <div class="content py-0">
        <div class="row fs-sm">
          <div class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-center text-sm-end">
            Concert Mix <i class="fa fa-heart text-danger"></i> <a class="fw-semibold" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
          </div>

        </div>
      </div>
    </footer>
    <!-- END Footer -->
  </div>
  <!-- END Page Container -->


    <!--change password Modal -->
    <div class="modal fade" id="change" tabindex="-1" aria-labelledby="change" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">change user password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="js-validation-signin" action="" method="POST">
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="password2" name="password" placeholder="Enter password">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock-open"></i>
                                </span>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="change_password">change password</button>
                    
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="suspend" tabindex="-1" aria-labelledby="suspend" aria-hidden="true">
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
                        Are you sure, you want to suspend this user
                    </h3>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger" name="suspend">Yes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

     <!-- Modal -->
     <div class="modal fade" id="activate" tabindex="-1" aria-labelledby="activate" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Activate User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id3">
                    </div>
                    <i class="fa-duotone fa-brake-warning text-danger"></i>
                    <h3 class="container">
                        Are you sure, you want to activate this user?
                    </h3>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success" name="activate">Yes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
  <script src="assets/js/dashmix.app.min.js"></script>

  <!-- jQuery (required for DataTables plugin) -->
  <script src="assets/js/lib/jquery.min.js"></script>
  <script>
        $(document).ready(function() {
            $('.delete_btn').click(function() {
                var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
                $('#id2').val(id);
            });
            $('.activate_btn').click(function() {
                var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
                $('#id3').val(id);
            });

            $('.edit_btn').click(function() {
                var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
                $('#id').val(id);
                var pass = $(this).closest("tr").find('td:nth-child(4)').text().trim();
                $('#password2').val(pass);
            });

        });
    </script>

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

  <!-- Page JS Code -->
  <script src="assets/js/pages/be_tables_datatables.min.js"></script>
</body>

</html>