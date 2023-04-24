<?php
session_start();
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
    if(isset($_POST['chat'])){
        $event = $_POST['eventId'];
        $user = $_SESSION['user']['id'];
        $message = $_POST['message'];
        $sql = "INSERT INTO `comments`(`comment`, `user`, `event`) VALUES ('$message', '$user', '$event')";
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
                    <p class="fw-bold text-warning">Select Chat room to Join
                    </p>
                    <form action="" method="post">
                        <select class="form-select mb-4" aria-label="Default select example" name="event">
                            <option selected>Select Chat room</option>
                            <?php
                            $sql = "SELECT * FROM `events`";
                            $results = $conn->query($sql);
                            while ($chat = $results->fetch_assoc()) {
                            ?>
                                <option value="<?php echo $chat['event_id'] ?>"><?php echo $chat['event_name'] ?></option>
                            <?php } ?>
                        </select>
                        <button class="btn btn-outline-warning mb-4" type="submit" name="chatroom">Join</button>
                    </form>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Chat #2 -->
                        <div class="block block-rounded">
                            <!-- Chat #2 Header -->
                            <div class="block-content block-content-full bg-gd-fruit text-center">
                                <img class="img-avatar img-avatar-thumb" src="assets/media/avatars/avatar7.jpg" alt="">
                                <p class="fs-lg fw-semibold text-white mt-3 mb-0">
                                    CHAT ROOM
                                </p>
                                <p class="text-white-75 mb-0">
                                    <?php
                                    $event = $_POST['event'];
                                    $sql = "SELECT * FROM `events` WHERE events.event_id = '$event'";
                                    echo $conn->query($sql)->fetch_assoc()['event_name']
                                    ?>
                                </p>
                            </div>
                            <!-- END Chat #2 Header -->

                            <!-- Chat #2 Messages -->
                            <?php
                            if (isset($_POST['chatroom'])) {
                                $chatroom = $_POST['event'];
                                $sql = "SELECT * FROM `comments` JOIN users ON comments.user = users.id WHERE event = '$chatroom'";
                                $myChats = $conn->query($sql);
                            }

                            ?>
                            <?php
                            function time_ago($datetime)
                            {
                                $timestamp = strtotime($datetime);
                                $difference = time() - $timestamp;

                                if ($difference < 60) {
                                    return $difference . " secs ago";
                                } elseif ($difference < 3600) {
                                    return round($difference / 60) . " mins ago";
                                } elseif ($difference < 86400) {
                                    return round($difference / 3600) . " hours ago";
                                } elseif ($difference < 31536000) {
                                    return round($difference / 86400) . " days ago";
                                } else {
                                    return round($difference / 31536000) . " years ago";
                                }
                            }
                            while ($chats = $myChats->fetch_assoc()) {

                            ?>
                                <div class=" d-flex  <?php if($chats['user'] == $_SESSION['user']['id']){echo 'flex-row-reverse ';}?>m-2">
                                    <div class="card  text-center <?php if($chats['user'] == $_SESSION['user']['id']){echo 'bg-success';}else{echo 'bg-secondary';}?> rounded p-4  text-white">
                                        <div class="d-flex justify-content-between">
                                            <div class="text-white fs-1 fw-bold">
                                            <?php if($chats['user'] == $_SESSION['user']['id']){echo 'You';}else{echo $chats['username'];}?>
                                            
                                            </div>

                                        </div>
                                        <span>

                                            <?php echo $chats['comment'] ?>

                                        </span>
                                        <div class="d-flex flex-row-reverse">

                                            <div  style="font-size: 10px;">
                                                <?php echo time_ago($chats['time']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Chat #2 Input -->
                            <div class="js-chat-form block-content p-2 bg-body-dark">
                                <form action="" method="POST">
                                    <input type="hidden" name="eventId" value="<?php echo $_POST['event']?>">
                                    <input type="text" class="js-chat-input form-control form-control-alt"  placeholder="Type a message.." name="message">
                                    <button type="submit" class="btn btn-success text-white fw-bold mt-2" name="chat">
                                    <i class="fa-solid fa-paper-plane"></i>
                                        Send message
                                    </button>
                                </form>
                            </div>
                            <!-- END Chat #2 Input -->
                        </div>
                        <!-- END Chat #2 -->
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
</body>

</html>