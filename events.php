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

    <title>Events</title>

    <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" href="assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/dashmix.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <style>
        table,
        th,
        td {
            border: 1px solid #D3D3D3;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <?php
    include "dbconnect.php";
    if (isset($_POST['edit_event'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $time = $_POST['time'];
        $date = $_POST['date'];
        $venue = $_POST['venue'];
        $description = $_POST['description'];
        $sql = "UPDATE `events` SET`event_name`='$name',`event_date`='$date',`event_time`='$time', `venue`='$venue',`description`='$description' WHERE events.event_id = '$id'";
        $results = $conn->query($sql);
        if ($results) {
            $user =  $_SESSION['user']['id'];
            $transaction_id = "#" . date('Ym') . time();
            $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Edited an  Event($name)',  '$user')";
            $conn->query($sql);
        }
    }

    if (isset($_POST['add_event'])) {
        $name = $_POST['name'];
        $time = $_POST['time'];
        $date = $_POST['date'];
        if($_SESSION['user']['role'] == '1'){
            $organiser = $_POST['organiser'];
        }else{
            $organiser = $_SESSION['user']['user_id'];
        }
        $venue = $_POST['venue'];
        $description = $_POST['description'];

        $sql = "INSERT INTO `events`(`event_name`, `event_date`, `event_time`, `promoter_id`, `venue`, `description`, `flier`)
     VALUES ('$name', '$date', '$time', '$organiser', '$venue', '$description', 'flier')";
        $results = $conn->query($sql);
        if ($results) {
            $user =  $_SESSION['user']['id'];
            $transaction_id = "#" . date('Ym') . time();
            $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Added a new event called $name',  '$user')";
            $conn->query($sql);
        } else {
            echo $conn->error;
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
                                    <div class="d-flex justify-content-between">
                                        <h3>Events</h3>
                                        <span>
                                            <button type="button" class="js-swal-confirm btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomer">
                                                <i class="fa fa-plus text-white me-1"></i> New Event
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
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th class="text-center">Event</th>
                                    <th class="text-center">Organiser</th>
                                    <th class="text-center">Bookings</th>
                                    <th class="text-center">Packages</th>
                                    <th class="text-center">Venue</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Amount collected</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($_SESSION['user']['role'] == 1) {
                                    $sql = "SELECT * FROM `events` JOIN promoter ON events.promoter_id = promoter.id";
                                } else {
                                    $id = $_SESSION['user']['user_id'];
                                    $sql = "SELECT * FROM `events` JOIN promoter ON events.promoter_id = promoter.id WHERE events.promoter_id = '$id'";
                                }
                                $events = $conn->query($sql);
                                if ($events->num_rows > 0) {
                                    $sn = 0;
                                    foreach ($events as $event) :
                                ?>
                                        <tr>
                                            <td hidden><?php echo $event['event_id']; ?></td>
                                            <td hidden><?php echo $event['event_name']; ?></td>
                                            <td hidden><?php echo $event['name']; ?></td>
                                            <td hidden><?php echo $event['event_date']; ?></td>
                                            <td hidden><?php echo $event['event_time']; ?></td>
                                            <td hidden><?php echo $event['venue']; ?></td>
                                            <td hidden><?php echo $event['description']; ?></td>
                                            <td class="fw-semibold text-center">
                                                <?php echo $event['event_name'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $event['name'] ?>
                                            </td>
                                            <td class="text-center">
                                                5
                                            </td>
                                            <td class="text-center">
                                                vip 500000
                                            </td>
                                            <td class="text-center">
                                                <?php echo $event['venue'] ?>
                                            </td>

                                            <td class="text-center">
                                                Date:
                                                <?php echo $event['event_date'] ?></br>
                                                Time: <?php 
                                                $time24 = $event['event_time'];
                                                $time = new DateTime($time24);
                                                echo $time->format('h:i a');
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format(100000)?>
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

    <!--Add Event Modal -->
    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="js-validation-signin" action="" method="POST">
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="name" placeholder="Event name">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-plus"></i>
                                </span>
                            </div>
                        </div>
                        <?php if($_SESSION['user']['role'] == '1'){?>
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="organiser">
                                    <?php
                                    $sql = "SELECT * FROM `promoter` WHERE promoter.status='1' AND promoter.enrolled='1'";
                                    $results = $conn->query($sql);
                                    while ($promoters = $results->fetch_assoc()) { ?>
                                        <option value="<?php echo $promoters['id']; ?>"><?php echo $promoters['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-text">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                            </div>
                        </div>
                        <?php }?>
                        <div class="mb-4">
                            <label for="floatingSelect">Event Date</label>

                            <div class="input-group input-group-lg">
                                <input type="date" class="form-control" name="date">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="floatingSelect">Event Time</label>

                            <div class="input-group input-group-lg">
                                <input type="time" class="form-control" name="time">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="venue" placeholder="Venue">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-location-plus"></i>
                                </span>
                            </div>
                        </div>
                        <textarea name="description" id="editor" placeholder="Add event description">
            <!-- &lt;p&gt;Ad&lt;/p&gt; -->
            </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="add_event">Add Event</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Event Modal -->
    <div class="modal fade" id="editCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="js-validation-signin" action="" method="POST">
                        <input type="hidden" class="form-control" id="id" name="id" placeholder="Email">
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Event name">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-arrows-to-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="floatingSelect">Event Date</label>
                            <div class="input-group input-group-lg">
                                <input type="date" id="date" class="form-control" name="date">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="floatingSelect">Event Time</label>

                            <div class="input-group input-group-lg">
                                <input type="time" class="form-control" name="time" id="time">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="venue" name="venue" placeholder="Venue">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-money-check-dollar"></i>
                                </span>
                            </div>
                        </div>
                        <textarea name="description" placeholder="Add event description" id="description">
            <!-- &lt;p&gt;Ad&lt;/p&gt; -->
            </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="edit_event">Save changes</button>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id2">
                    </div>
                    <i class="fa-duotone fa-brake-warning text-danger"></i>
                    <h3 class="container">
                        Are you sure, you want to delete this Event
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

    </script>
    <script>
        $(document).ready(function() {
            $('.delete_btn').click(function() {
                var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
                $('#id2').val(id);
            });

            let editor2;
            ClassicEditor
                .create(document.querySelector('#description'))
                .then(newEditor => {
                    editor2 = newEditor;

                    console.log('EDITOR INSTANCE', editor2)
                })
                .catch(error => {
                    ceditor2.setData(description)
                    console.error(error);
                });

            $('.edit_btn').click(function() {
                var id = $(this).closest("tr").find('td:nth-child(1)').text().trim();
                console.log("===================TEST=====================", id);
                $('#id').val(id);
                var name = $(this).closest("tr").find('td:nth-child(2)').text().trim();
                $('#name').val(name);
                var date = $(this).closest("tr").find('td:nth-child(4)').text().trim();
                $('#date').val(date);
                var time = $(this).closest("tr").find('td:nth-child(5)').text().trim();
                $('#time').val(time);
                var venue = $(this).closest("tr").find('td:nth-child(6)').text().trim();
                $('#venue').val('Kampala');
                var description = $(this).closest("tr").find('td:nth-child(7)').html().trim();
                console.log('DESC', description)

                editor2.setData(description)


            });

        });
    </script>
    <script>
        let editor;
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    </script>

    <script>


    </script>
</body>

</html>