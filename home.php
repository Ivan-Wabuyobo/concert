<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
}

include 'dbconnect.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Concert Mix</title>
    <link rel="stylesheet" id="css-main" href="assets/css/dashmix.min.css">
</head>

<body>
    <?php
    if (isset($_POST['comment_btn'])) {
        $event = $_POST['event'];
        $comment = $_POST['comment'];
        $user = $_SESSION['user']['id'];
        $sql = "INSERT INTO `comments`(`comment`, `user`, `event`) VALUES ('$comment', '$user', '$event')";
        $conn->query($sql);
    }

    if(isset($_POST['book'])){
        $event=$_POST['event'];
        $packageList = explode(',', trim($_POST['package']));
        $package = $packageList[0];

        $packageAmount = $packageList[1];
        $amount = $_POST['amount'];

        //get the organiser
        $query = "SELECT * FROM `events` WHERE event_id = '$event'";
        $organiser = $conn->query($query)->fetch_assoc()['promoter_id'];
        $user = $_SESSION['user']['id'];
        $balance = $packageAmount - $amount;
        $ticket = time();

        $sql = "INSERT INTO `bookings`(`event_id`, `package_id`, `organiser_id`, `customer_id`, `amount`, `amount_paid`, `balance`, `ticket_number`)
                     VALUES ('$event', '$package', '$organiser', '$user', '$packageAmount', '$amount', '$balance', '$ticket')";
        
        $conn->query($sql);
    }
    ?>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!--Siderbar-->
        <?php include "usersidebar.php" ?>
        <!--End Siderbar-->

        <!-- Header -->
        <?php include "header.php" ?>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container ">
            <div class="content mt-6">
                <div>
                    <p class="fw-bold text-warning">Book an event with us today</p>
                </div>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM events WHERE events.event_date > CURRENT_DATE";
                    $results = $conn->query($sql);
                    while ($events = $results->fetch_assoc()) {
                    ?>
                        <div class="col-md-6 col-xl-6">
                            <!-- Story #1 -->
                            <div class="block block-rounded">
                                <div class="block-content pb-8 text-end bg-image" style="background-image: url('<?php echo $events['flier']?>');">
                                    <span class="badge bg-primary fw-bold p-2 text-uppercase">
                                        <?php
                                        $date =  $events['event_date'];
                                        $time = $events['event_time'];
                                        $event_date = new DateTime("$date $time");
                                        // Get the current date and time
                                        $current_date = new DateTime();
                                        // Calculate the time difference between the current date and time and the event date and time
                                        $time_remaining = $event_date->diff($current_date);

                                        // Get the remaining days and hours
                                        $days_remaining = $time_remaining->d;
                                        $hours_remaining = $time_remaining->h;
                                        $minutes_remaining = $time_remaining->i;


                                        // Output the time remaining
                                        if ($days_remaining > 0) {
                                            echo "$days_remaining days, $hours_remaining hrs and $minutes_remaining mins to the event.";
                                        } else if ($hours_remaining > 0) {
                                            echo "$hours_remaining hrs and $minutes_remaining mins to the event.";
                                        } else {
                                            echo "$minutes_remaining mins to the event.";
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="block-content">
                                    <a class="fw-semibold text-dark" href="javascript:void(0)">

                                        <?php echo $events['event_name'] ?>
                                    </a>
                                    <p class="fs-sm fw-medium text-muted mt-1">
                                        <?php echo $events['description'] ?>
                                    </p>
                                    <button onclick="getId(<?php echo $events['event_id'] ?>)" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#book"><i class="fa-solid fa-paper-plane"></i> Book</button>
                                </div>
                                <div class="block-content block-content-full bg-body-light">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p>
                                                Date: <span class="text-danger"><?php echo $events['event_date'] ?></span>
                                            </p>
                                            <p>
                                                Time: <span class="text-danger"><?php
                                                                                echo date("h:i A", strtotime($events['event_time']));
                                                                                ?></span>
                                            </p>
                                        </div>
                                        <div>
                                            <span>
                                                <a class="text-muted fw-semibold" href="like.php?event=<?php echo $events['event_id'] ?>">
                                                    <i class="fa fa-fw fa-heart opacity-50 me-1 text-danger"></i> <?php
                                                                                                                    $event_id = $events['event_id'];
                                                                                                                    $query = "SELECT COUNT(id) AS likes FROM `likes` WHERE event_id= '$event_id'";
                                                                                                                    echo $conn->query($query)->fetch_assoc()['likes'];
                                                                                                                    ?> Like
                                                </a>
                                            </span>
                                            <span>
                                                <a onclick="getEventId(<?php echo $events['event_id'] ?>)" class="text-muted fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fa fa-fw fa-comments opacity-50 me-1 text-success"></i> <?php
                                                                                                                        $event_id = $events['event_id'];
                                                                                                                        $query = "SELECT COUNT(id) AS comments FROM `comments` WHERE comments.event ='$event_id'";
                                                                                                                        echo $conn->query($query)->fetch_assoc()['comments'];
                                                                                                                        ?> comment
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Story #1 -->
                        </div>
                    <?php } ?>
                </div>

            </div>
            <!-- Button trigger modal -->


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add your Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" id="event" name="event">

                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" rows="10" name="comment"></textarea>
                                    <label for="floatingTextarea">Comments</label>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="comment_btn">comment</button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="book" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Book this event</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" id="eventId" name="event">
                                <select class="form-select mb-2" aria-label="Default select example" name="package">
                                    <option selected>Choose a package</option>

                                    <?php
                                    $query = "SELECT * FROM `package`";
                                    $resul = $conn->query($query);
                                    while ($package = $resul->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $package['id']?><?php echo ","?><?php echo $package['amount'] ?>"><?php echo $package['package_name'] ?>--at <?php echo $package['amount'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="mb-2">
                                    <input type="text" class="form-control" placeholder="Enter Amount" name="amount">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="book">Book</button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <?php include "footer.php" ?>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="assets/js/dashmix.app.min.js"></script>

    <!-- Page JS Plugins -->
    <script src="assets/js/plugins/chart.js/chart.min.js"></script>

    <!-- Page JS Code -->
    <script src="assets/js/pages/be_pages_dashboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <script>
        function getEventId(id) {

            document.getElementById('event').value = id;

        }

        function getId(id) {

            document.getElementById('eventId').value = id;


        }
    </script>
</body>

</html>