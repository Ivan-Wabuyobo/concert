<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title>Login</title>

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
  <!-- Dashmix framework -->
  <link rel="stylesheet" id="css-main" href="assets/css/dashmix.min.css">

  <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
  <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
  <!-- END Stylesheets -->
</head>

<body>
  <?php
  include "dbconnect.php";
  if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //sql to check whether the username exists
    $sql = "SELECT * FROM `users` WHERE users.username='$username' AND users.password='$password'";
    $results = $conn->query($sql);
    //echo $results->num_rows;

    if ($results->num_rows > 0) {
      //the user exists its time to log them in
      $row = $results->fetch_assoc();
      $_SESSION['user'] = $row;
    
      if ($_SESSION['user']['role'] == '1') {
        $userId = $_SESSION['user']['id'];
        $transaction_id = "#" . date('Ym') . time();
        $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'logged in successfully', '$userId')";
        $conn->query($sql);
        header("location:dashboard.php");

      } else if ($_SESSION['user']['role'] == '2') {
        $userId = $_SESSION['user']['id'];
        $transaction_id = "#" . date('Ym') . time();
        $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'logged in successfully', '$userId')";
        $conn->query($sql);
        header("location:promoter_dashboard.php");
      } else if ($_SESSION['user']['role'] == '3') {
        $userId = $_SESSION['user']['id'];
        $transaction_id = "#" . date('Ym') . time();
        $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'logged in successfully', '$userId')";
        $conn->query($sql);
        header("location:home.php");

      }
    }
  }

  ?>
  <div id="page-container">

    <!-- Main Container -->
    <main id="main-container">
      <!-- Page Content -->
      <div class="row g-0 justify-content-center bg-body-dark">
        <div class="hero-static col-sm-10 col-md-8 col-xl-6 d-flex align-items-center p-2 px-sm-0">
          <!-- Sign In Block -->
          <div class="block block-rounded block-transparent block-fx-pop w-100 mb-0 overflow-hidden bg-image" style="background-image: url('assets/media/photos/photo20@2x.jpg');">
            <div class="row g-0">
              <div class="col-md-6 order-md-1 bg-body-extra-light">
                <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                  <!-- Header -->
                  <div class="mb-2 text-center">
                    <a class="link-fx fw-bold fs-1" href="index.html">
                      <span class="text-dark">Concert</span><span class="text-primary">mix</span>
                    </a>
                    <p class="text-uppercase fw-bold fs-sm text-muted">Sign In</p>
                  </div>
                  <!-- END Header -->

                  <!-- Sign In Form -->
                  <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                  <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                  <form class="js-validation-signin" action="" method="POST">
                    <div class="mb-4">
                      <input type="text" class="form-control form-control-alt" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="mb-4">
                      <input type="password" class="form-control form-control-alt" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn w-100 btn-hero btn-primary" name="login_btn">
                        <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Login
                      </button>
                    </div>
                  </form>
                  <!-- END Sign In Form -->
                </div>
              </div>
              <div class="col-md-6 order-md-0 bg-primary-dark-op d-flex align-items-center">
                <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                  <div class="d-flex">
                    <a class="flex-shrink-0 img-link me-3" href="javascript:void(0)">
                      <img class="img-avatar img-avatar-thumb" src="assets/media/avatars/avatar13.jpg" alt="">
                    </a>
                    <div class="flex-grow-1">
                      <p class="text-white fw-semibold mb-1">
                        Login to get started with concert mix!!
                      </p>
                      <a class="text-white-75 fw-semibold" href="javascript:void(0)">Here to serve you bettter!!</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Sign In Block -->
        </div>
      </div>
      <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
  </div>
  <!-- END Page Container -->

  <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
  <script src="assets/js/dashmix.app.min.js"></script>

  <!-- jQuery (required for jQuery Validation plugin) -->
  <script src="assets/js/lib/jquery.min.js"></script>

  <!-- Page JS Plugins -->
  <script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>

  <!-- Page JS Code -->
  <script src="assets/js/pages/op_auth_signin.min.js"></script>
</body>

</html>