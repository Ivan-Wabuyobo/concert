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
     if(isset($_POST['pay'])){
        $bookId=$_POST['bookId'];
        $amount = $_POST['amount'];

        $sql = "SELECT * FROM `bookings` WHERE book_id = '$bookId'";
        $BillAmount = $conn->query($sql)->fetch_assoc()['amount'];
        $oldAmount = $conn->query($sql)->fetch_assoc()['amount_paid'];
        $newAmount = $amount + $oldAmount;
        $balance = $BillAmount - $newAmount;
       

        $sql = "UPDATE `bookings` SET `amount_paid`='$newAmount',`balance`='$balance' WHERE book_id = '$bookId'";
       $results = $conn->query($sql);
      $role = $_SESSION['user']['role'];
      $userId = $_SESSION['user']['user_id'];
      $username = $_SESSION['user']['username'];
       if($results){
        if($role == 3){
          echo "===========$role============";
          $sql = "SELECT * FROM `customers` WHERE customers.id = '$userId'";
        }else{
          echo "===========$role============";
          $sql = "SELECT * FROM `promoter` WHERE promoter.id = '$userId'";
        }
        echo "===========$sql============";
         
          $contact = $conn->query($sql)->fetch_assoc()['contact'];
          echo "===========contact============";
          echo "===========$contact============";
          echo "===========contact============";

          $phone = substr($contact, -9);
          $reciever= "256".$phone;
          $message = "Dear $username:\n Your amount of  $amount was successfully received and your new balance is $balance\nconcert Mix for details 0772458553";
          $data = 'api_id=api34770800361&api_password=nugsoft@2020&phonenumber='.$reciever.'&sms_type=P&sender_id=bulksms&from=nugsoft&encoding=T&textmessage='.rawurlencode($message);
          $ch = curl_init('http://apidocs.speedamobile.com/api/SendSMS?'.$data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          echo "===================================";
          echo $response;
          echo "===================================";

          curl_close($ch);     
      
       }
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
                    <p class="fw-bold text-warning">Bookings</p>
                </div>
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
                  <th>Customer</th>
                  <th class="text-center">Event</th>
                  <th class="text-center">Package</th>
                  <th class="text-center">Event Promoter</th>
                  <th class="text-center">Amount Paid</th>
                  <th class="text-center">Balance</th>
                  <th class="text-center">Ticket Number</th>
                  <th class="text-center">Actions</th>
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

                $userId = $_SESSION['user']['id'];
                $sql = "SELECT * FROM `bookings` JOIN users ON users.id = bookings.customer_id JOIN events ON events.event_id = bookings.event_id JOIN package ON package.id = bookings.package_id JOIN promoter ON promoter.id = bookings.organiser_id WHERE bookings.customer_id = '$userId' ORDER BY book_id DESC";
                $results = $conn->query($sql);

                while ($user = $results->fetch_assoc()) {

                ?>
                  <tr>
                    <td hidden><?php echo $user['id'];?></td>
                    <td class="text-center"><?php echo $user['username']?></td>
                    <td class="text-center">
                    <?php 
                    echo $user['event_name']
                     ?>
                    </td>
                    <td class="text-center">
                      <?php echo $user['package_name']?>
                    </td>
                    <td class="text-center fw-bold">
                      <?php
                     echo $user['name']
                     
                      ?>
                    </td>
                    <td class="text-center">
                    <?php
                  echo $user['amount_paid'];?>
                    </td>
                    <td class="text-center">
                    <?php
                    if($user['balance'] > 0){
                      echo $user['balance'];
                    }else{
                      echo "NILL";
                    }
                  ?>
                    </td>
                    <td class="text-center">
                    <?php
                  echo $user['ticket_number'];?>
                    </td>
                    <td class="text-center text-white">

                    <?php
                     if($user['balance'] > 0){
                    ?>
                    <button onclick="bookId(<?php echo $user['book_id']?>)" class="btn btn-warning send_btn text-white" data-bs-toggle='modal' data-bs-target='#book'><i class="fa-solid fa-paper-plane d-block text-white"></i>Clear</button>
                    <?php }else{?>
                      <p class="text-center text-success fw-bold">Cleared</p>
                      <?php }?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

            </div>
            <!-- Modal -->
            <div class="modal fade" id="book" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Finishing paying for this event</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" id="bookId" name="bookId">
                                <div class="mb-2">
                                    <input type="text" class="form-control" placeholder="Enter Amount" name="amount">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning text-white" name="pay">Pay</button>
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
        function bookId(id) {

            document.getElementById('bookId').value = id;

        }
    </script>
</body>

</html>