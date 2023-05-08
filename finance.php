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

  <title>Financial Report</title>

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


 
    <!-- Main Container -->
    <main id="main-container">
      <!-- Hero -->
      <div class="bg-body-light">
        <div class="content content-full">
          <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Financial Report</h1>
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
                  <th class="text-center">Promoters</th>
                  <th class="text-center">Total Events</th>
                  <th class="text-center">Amount Collected</th>    
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT *, SUM(amount_paid) AS amountCollected, (SELECT COUNT(events.event_id) FROM events WHERE events.promoter_id = bookings.organiser_id) AS events FROM `bookings` JOIN promoter on bookings.organiser_id = promoter.id GROUP BY bookings.organiser_id";
                $results = $conn->query($sql);

                while ($data = $results->fetch_assoc()) {

                ?>
                  <tr>
                    <td class="text-center"><?php echo $data['name']?></td>
                    <td class="text-center"><?php echo $data['events']?></td>
                    <td class="text-center">
                    <?php 
                    echo number_format($data['amountCollected'])
                     ?>/=
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