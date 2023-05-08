<?php
session_start();

if(!isset($_SESSION['user'])){
    header("location:login.php");
}?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Events</title>

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
    if (isset($_POST['edit_package'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['amount'];
        $sql = "UPDATE `package` SET `package_name`='$name',`amount`='$price' WHERE package.id = '$id'";
        $results = $conn->query($sql);
        if ($results) {
        }
    }

    if (isset($_POST['add_package'])) {
        $name = $_POST['name'];
        $event = $_POST['event'];
        $price = $_POST['amount'];
        $promoterId = $_SESSION['user']['user_id'];                                                                                                         
        $sql = "INSERT INTO `package`(`package_name`, `amount`, `event`, `event_promoter`) VALUES ('$name', '$price', '$event', '$promoterId')";
        $results = $conn->query($sql);
        if ($results) {
            echo "it worked";
        } else {
            echo $conn->error;
        }
    }


    if (isset($_POST['delete_customer'])) {
        $id = $_POST['id'];
        $sql = "UPDATE `customers` SET `status`=0 WHERE id = $id";
        $results = $conn->query($sql);
        if ($results) {
        }
    }

    ?>

    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Sidebar -->
        <!--
        Sidebar Mini Mode - Display Helper classes

        Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
          If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

        Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
        Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
        Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
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
                                        <h3>Events' Packages and Pricing</h3>
                                        <span>
                                            <button type="button" class="js-swal-confirm btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomer">
                                                <i class="fa fa-plus text-white me-1"></i> Add Package
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <tr>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th class="text-center">Package</th>
                                    <th class="text-center">Event</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Date Added</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                function time_ago($datetime)
                                {
                                    $timestamp = strtotime($datetime);
                                    $difference = time() - $timestamp;

                                    if ($difference < 60) {
                                        return $difference . " sec ago";
                                    } elseif ($difference < 3600) {
                                        return round($difference / 60) . " min ago";
                                    } elseif ($difference < 86400) {
                                        return round($difference / 3600) . " hour ago";
                                    } elseif ($difference < 31536000) {
                                        return round($difference / 86400) . " day ago";
                                    } else {
                                        return round($difference / 31536000) . " year ago";
                                    }
                                }
                                if($_SESSION['user']['role'] == '1'){
                                $sql = "SELECT * FROM `package` JOIN events ON package.event = events.event_id";

                                }else{
                                    $promoterId = $_SESSION['user']['user_id'];                                    
                                    $sql = "SELECT * FROM `package` JOIN events ON package.event = events.event_id WHERE  event_promoter = '$promoterId'";
                                }
                                $packages = $conn->query($sql);
                                if ($packages->num_rows > 0) {
                                    foreach ($packages as $package) :

                                ?>
                                        <tr>
                                            <td hidden><?php echo $package['id']; ?></td>
                                            <td hidden><?php echo $package['package_name']; ?></td>
                                            <td hidden> <?php echo $package['amount']; ?></td>
                                            <td class="text-center">
                                                <?php echo $package['package_name'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $package['event_name'] ?>
                                            </td>
                                            <td class="text-center">
                                            UGX <?php echo number_format($package['amount']); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo time_ago($package['date']) ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group flex-nowrap">
                                                    <button class="btn btn-info edit_btn" data-bs-toggle='modal' data-bs-target='#editCustomer'><i class="fa-sharp fa-solid fa-pen-to-square"></i></button>
                                                    <button class="btn btn-danger btn delete_btn " data-bs-toggle='modal' data-bs-target='#deleteCustomer'><i class="fa-sharp fa-solid fa-trash "></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                } ?>

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

    <!--Add Modal -->
    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Package</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="js-validation-signin" action="" method="POST">
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="name" placeholder="Package name">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="event">
                                    <option selected>Select Event</option>
                                    <?php
                                    if($_SESSION['user']['role'] != 1){
                                    $promoterId = $_SESSION['user']['user_id'];
                                    $sql = "SELECT * FROM `events` WHERE  events.promoter_id = '$promoterId'";
                                    }else{
                                    $sql = "SELECT * FROM `events`";
                                    }
                                    
                                    $results = $conn->query($sql);
                                    while ($events = $results->fetch_assoc()) { ?>
                                        <option value="<?php echo $events['event_id']; ?>"><?php echo $events['event_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="floatingSelect">Package Price</label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="amount">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="add_package">Add Package</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Modal -->
    <div class="modal fade" id="editCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">change Package name or price</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="js-validation-signin" action="" method="POST">
                        <input type="hidden" class="form-control" id="id" name="id">

                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Package name">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="floatingSelect">Package Price</label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="amount" name="amount">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="edit_package">Save Changes</button>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Package</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id2">
                    </div>
                    <i class="fa-duotone fa-brake-warning text-danger"></i>
                    <h3 class="container">
                        Are you sure, you want to delete this package
                    </h3>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" name="delete_customer">Delete</button>
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

    <!-- Page JS Code -->
    <script src="assets/js/pages/be_tables_datatables.min.js"></script>
    <script src="assets/js/pages/be_comp_dialogs.min.js"></script>
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
                var amount = $(this).closest("tr").find('td:nth-child(3)').text().trim();
                $('#amount').val(amount);

            });

        });
    </script>
</body>

</html>