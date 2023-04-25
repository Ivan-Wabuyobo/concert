<?php
session_start();
if(!isset($_SESSION['user'])){
  header("location:login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title>Customers</title>

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
  if (isset($_POST['edit_customer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $sql = "UPDATE `customers` SET `customer_name`='$name',`email`='$email',`contact`='$contact' WHERE id = '$id'";
    $results = $conn->query($sql);
    if ($results) {
      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Edited customer($name) successfully',  '$user')";
      $conn->query($sql);
    }
  }

  if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $sql = "INSERT INTO `customers`(`customer_name`, `email`, `contact`) VALUES ('$name', '$email', '$contact')";
    $results = $conn->query($sql);
    if ($results) {
      //Add a user
      $password = time();
      $userId = mysqli_insert_id($conn);
      $sql = "INSERT INTO `users`(`username`, `password`, `role`, `user_id`) VALUES ('$name', '$password', '3', '$userId' )";
      $conn->query($sql);

      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Registered new customer called $name',  '$user')";
      $conn->query($sql);

       
    }
  }


  if (isset($_POST['delete_customer'])) {
    $id = $_POST['id'];
    $sql = "UPDATE `customers` SET `status`=0 WHERE id = $id";
    $results = $conn->query($sql);
    if ($results) {
      $user =  $_SESSION['user']['id'];
      $transaction_id = "#" . date('Ym') . time();
      $sql = "INSERT INTO `log`(`transaction_id`, `transaction`, `user`) VALUES ('$transaction_id', 'Deleted customer successfully',  '$user')";
      $conn->query($sql);
    }
  }

  ?>

  <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
    <!-- Side Overlay-->
    <aside id="side-overlay">
      <!-- Side Header -->
      <div class="bg-image" style="background-image: url('assets/media/various/bg_side_overlay_header.jpg');">
        <div class="bg-primary-op">
          <div class="content-header">
            <!-- User Avatar -->
            <a class="img-link me-1" href="be_pages_generic_profile.html">
              <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar10.jpg" alt="">
            </a>
            <!-- END User Avatar -->

            <!-- User Info -->
            <div class="ms-2">
              <a class="text-white fw-semibold" href="be_pages_generic_profile.html">George Taylor</a>
              <div class="text-white-75 fs-sm">Full Stack Developer</div>
            </div>
            <!-- END User Info -->

            <!-- Close Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="ms-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
              <i class="fa fa-times-circle"></i>
            </a>
            <!-- END Close Side Overlay -->
          </div>
        </div>
      </div>
      <!-- END Side Header -->

      <!-- Side Content -->
      <div class="content-side">
        <!-- Side Overlay Tabs -->
        <div class="block block-transparent pull-x pull-t mb-0">
          <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="so-settings-tab" data-bs-toggle="tab" data-bs-target="#so-settings" role="tab" aria-controls="so-settings" aria-selected="true">
                <i class="fa fa-fw fa-cog"></i>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="so-people-tab" data-bs-toggle="tab" data-bs-target="#so-people" role="tab" aria-controls="so-people" aria-selected="false">
                <i class="far fa-fw fa-user-circle"></i>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="so-profile-tab" data-bs-toggle="tab" data-bs-target="#so-profile" role="tab" aria-controls="so-profile" aria-selected="false">
                <i class="far fa-fw fa-edit"></i>
              </button>
            </li>
          </ul>
          <div class="block-content tab-content overflow-hidden">
            <!-- Settings Tab -->
            <div class="tab-pane pull-x fade fade-up show active" id="so-settings" role="tabpanel" aria-labelledby="so-settings-tab" tabindex="0">
              <div class="block mb-0">
                <!-- Color Themes -->
                <!-- Toggle Themes functionality initialized in Template._uiHandleTheme() -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Color Themes</span>
                </div>
                <div class="block-content block-content-full">
                  <div class="row g-sm text-center">
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-default" data-toggle="theme" data-theme="default" href="#">
                        Default
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xwork" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#">
                        xWork
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xmodern" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#">
                        xModern
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xeco" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#">
                        xEco
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xsmooth" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#">
                        xSmooth
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xinspire" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#">
                        xInspire
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xdream" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#">
                        xDream
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xpro" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#">
                        xPro
                      </a>
                    </div>
                    <div class="col-4 mb-1">
                      <a class="d-block py-3 text-white fs-sm fw-semibold bg-xplay" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#">
                        xPlay
                      </a>
                    </div>
                    <div class="col-12">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" href="be_ui_color_themes.html">All Color Themes</a>
                    </div>
                  </div>
                </div>
                <!-- END Color Themes -->

                <!-- Sidebar -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Sidebar</span>
                </div>
                <div class="block-content block-content-full">
                  <div class="row g-sm text-center">
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="sidebar_style_dark" href="javascript:void(0)">Dark</a>
                    </div>
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="sidebar_style_light" href="javascript:void(0)">Light</a>
                    </div>
                  </div>
                </div>
                <!-- END Sidebar -->

                <!-- Header -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Header</span>
                </div>
                <div class="block-content block-content-full">
                  <div class="row g-sm text-center mb-2">
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="header_style_dark" href="javascript:void(0)">Dark</a>
                    </div>
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="header_style_light" href="javascript:void(0)">Light</a>
                    </div>
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="header_mode_fixed" href="javascript:void(0)">Fixed</a>
                    </div>
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="header_mode_static" href="javascript:void(0)">Static</a>
                    </div>
                  </div>
                </div>
                <!-- END Header -->

                <!-- Content -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Content</span>
                </div>
                <div class="block-content block-content-full">
                  <div class="row g-sm text-center">
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="content_layout_boxed" href="javascript:void(0)">Boxed</a>
                    </div>
                    <div class="col-6 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="content_layout_narrow" href="javascript:void(0)">Narrow</a>
                    </div>
                    <div class="col-12 mb-1">
                      <a class="d-block py-3 bg-body-dark fw-semibold text-dark" data-toggle="layout" data-action="content_layout_full_width" href="javascript:void(0)">Full Width</a>
                    </div>
                  </div>
                </div>
                <!-- END Content -->

                <!-- Layout API -->
                <div class="block-content block-content-full border-top">
                  <a class="btn w-100 btn-alt-primary" href="be_layout_api.html">
                    <i class="fa fa-fw fa-flask me-1"></i> Layout API
                  </a>
                </div>
                <!-- END Layout API -->
              </div>
            </div>
            <!-- END Settings Tab -->

            <!-- People -->
            <div class="tab-pane pull-x fade fade-up" id="so-people" role="tabpanel" aria-labelledby="so-people-tab" tabindex="0">
              <div class="block mb-0">
                <!-- Online -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Online</span>
                </div>
                <div class="block-content">
                  <ul class="nav-items">
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar3.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-success"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Betty Kelley</div>
                          <div class="fs-sm text-muted">Photographer</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar16.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-success"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Henry Harrison</div>
                          <div class="fw-normal fs-sm text-muted">Web Designer</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar6.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-success"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Lisa Jenkins</div>
                          <div class="fw-normal fs-sm text-muted">Web Developer</div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- Online -->

                <!-- Busy -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Busy</span>
                </div>
                <div class="block-content">
                  <ul class="nav-items">
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar2.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-danger"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Barbara Scott</div>
                          <div class="fw-normal fs-sm text-muted">UI Designer</div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- END Busy -->

                <!-- Away -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Away</span>
                </div>
                <div class="block-content">
                  <ul class="nav-items">
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar14.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-warning"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Scott Young</div>
                          <div class="fw-normal fs-sm text-muted">Copywriter</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar5.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-warning"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Lori Grant</div>
                          <div class="fw-normal fs-sm text-muted">Writer</div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- END Away -->

                <!-- Offline -->
                <div class="block-content block-content-sm block-content-full bg-body">
                  <span class="text-uppercase fs-sm fw-bold">Offline</span>
                </div>
                <div class="block-content">
                  <ul class="nav-items">
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar10.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-muted"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Jack Greene</div>
                          <div class="fw-normal fs-sm text-muted">Teacher</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar4.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-muted"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Laura Carr</div>
                          <div class="fw-normal fs-sm text-muted">Photographer</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar7.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-muted"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Megan Fuller</div>
                          <div class="fw-normal fs-sm text-muted">Front-end Developer</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex py-2" href="be_pages_generic_profile.html">
                        <div class="flex-shrink-0 mx-3 overlay-container">
                          <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar13.jpg" alt="">
                          <span class="overlay-item item item-tiny item-circle border border-2 border-white bg-muted"></span>
                        </div>
                        <div class="flex-grow-1">
                          <div class="fw-semibold">Carl Wells</div>
                          <div class="fw-normal fs-sm text-muted">UX Specialist</div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- END Offline -->

                <!-- Add People -->
                <div class="block-content block-content-full border-top">
                  <a class="btn w-100 btn-alt-primary" href="javascript:void(0)">
                    <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Add People
                  </a>
                </div>
                <!-- END Add People -->
              </div>
            </div>
            <!-- END People -->

            <!-- Profile -->
            <div class="tab-pane pull-x fade fade-up" id="so-profile" role="tabpanel" aria-labelledby="so-profile-tab" tabindex="0">
              <form action="be_pages_dashboard.html" method="POST" onsubmit="return false;">
                <div class="block mb-0">
                  <!-- Personal -->
                  <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Personal</span>
                  </div>
                  <div class="block-content block-content-full">
                    <div class="mb-4">
                      <label class="form-label">Username</label>
                      <input type="text" readonly class="form-control" id="so-profile-username-static" value="Admin">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="so-profile-name">Name</label>
                      <input type="text" class="form-control" id="so-profile-name" name="so-profile-name" value="George Taylor">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="so-profile-email">Email</label>
                      <input type="email" class="form-control" id="so-profile-email" name="so-profile-email" value="g.taylor@example.com">
                    </div>
                  </div>
                  <!-- END Personal -->

                  <!-- Password Update -->
                  <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Password Update</span>
                  </div>
                  <div class="block-content block-content-full">
                    <div class="mb-4">
                      <label class="form-label" for="so-profile-password">Current Password</label>
                      <input type="password" class="form-control" id="so-profile-password" name="so-profile-password">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="so-profile-new-password">New Password</label>
                      <input type="password" class="form-control" id="so-profile-new-password" name="so-profile-new-password">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="so-profile-new-password-confirm">Confirm New Password</label>
                      <input type="password" class="form-control" id="so-profile-new-password-confirm" name="so-profile-new-password-confirm">
                    </div>
                  </div>
                  <!-- END Password Update -->

                  <!-- Options -->
                  <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Options</span>
                  </div>
                  <div class="block-content">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="so-settings-status" name="so-settings-status">
                      <label class="form-check-label fw-semibold" for="so-settings-status">Online Status</label>
                    </div>
                    <p class="text-muted fs-sm">
                      Make your online status visible to other users of your app
                    </p>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="so-settings-notifications" name="so-settings-notifications">
                      <label class="form-check-label fw-semibold" for="so-settings-notifications">Notifications</label>
                    </div>
                    <p class="text-muted fs-sm">
                      Receive desktop notifications regarding your projects and sales
                    </p>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="so-settings-updates" name="so-settings-updates">
                      <label class="form-check-label fw-semibold" for="so-settings-updates">Auto Updates</label>
                    </div>
                    <p class="text-muted fs-sm">
                      If enabled, we will keep all your applications and servers up to date with the most recent features automatically
                    </p>
                  </div>
                  <!-- END Options -->

                  <!-- Submit -->
                  <div class="block-content block-content-full border-top">
                    <button type="submit" class="btn w-100 btn-alt-primary">
                      <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                    </button>
                  </div>
                  <!-- END Submit -->
                </div>
              </form>
            </div>
            <!-- END Profile -->
          </div>
        </div>
        <!-- END Side Overlay Tabs -->
      </div>
      <!-- END Side Content -->
    </aside>
    <!-- END Side Overlay -->
    <?php include "sidebar.php" ?>
    <!-- END Sidebar -->

    <!-- Header -->
    <?php include "header.php" ?>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
      <!-- Hero -->
      <div class="bg-body-light">
        <div class="content content-full">
          <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Customers in the system</h1>
          </div>
        </div>
      </div>
      <!-- END Hero -->

      <!-- Page Content -->
      <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
          <div class="block-content block-content-full">
            <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons ">
              <thead>
                <div class="container">
                  <div class=" d-flex justify-content-between">
                    <h3>Customers</h3>
                    <span>
                      <button type="button" class="js-swal-confirm btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomer">
                        <i class="fa fa-plus text-white me-1"></i> New Customer
                      </button>
                    </span>
                  </div>
                </div>
                <tr>
                  <th hidden></th>
                  <th hidden></th>
                  <th hidden></th>
                  <th hidden></th>
                  <th class="text-center">Name</th>
                  <th class="text-center">Email</th>
                  <th class="text-center">Contact</th>
                  <th class="text-center">Number of Bookings</th>
                  <th class="text-center">Total Payments</th>
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
                     return $difference . " secs ago";
                  } elseif ($difference < 3600) {
                     return round($difference/60) . " mins ago";
                  } elseif ($difference < 86400) {
                     return round($difference/3600) . " hours ago";
                  } elseif ($difference < 31536000) {
                     return round($difference/86400) . " days ago";
                  } else {
                     return round($difference/31536000) . " years ago";
                  }
               }
                $sql = "SELECT `id`, `customer_name`, `email`, `contact`, `created`, `status`, (SELECT COUNT(bookings.book_id) FROM bookings WHERE bookings.customer_id = customers.id) as bookings, (SELECT SUM(bookings.amount_paid) FROM bookings WHERE bookings.customer_id = customers.id) as payments FROM `customers`  ORDER BY customers.id DESC";
                $customers = $conn->query($sql);
                if ($customers->num_rows > 0) {
                  $sn = 0;
                  while ($customer =  $customers->fetch_assoc()) {

                ?>
                    <tr>
                      <td hidden><?php echo $customer['id']; ?></td>
                      <td hidden><?php echo $customer['customer_name']; ?></td>
                      <td hidden><?php echo $customer['contact']; ?></td>
                      <td hidden><?php echo $customer['email']; ?></td>
                      <td class="fw-semibold text-center">
                        <?php echo $customer['customer_name'] ?>
                      </td>
                      <td class="text-center">
                        <?php echo $customer['email'] ?>
                      </td>
                      <td class="text-center">
                        <?php echo $customer['contact'] ?>
                      </td>
                      <td class="text-center">
                       <?php echo $customer['bookings'] ?>
                      </td>
                      <td class="text-center">UGX
                       <?php if($customer['payments']){echo number_format($customer['payments']) ;}else{ echo "0"; }?>
                      </td>
                      <td class="text-center"> 
                        <?php echo time_ago($customer['created']) ?>
                      </td>
                      <td>
                        <div class="input-group flex-nowrap">
                          <button class="btn btn-info edit_btn" data-bs-toggle='modal' data-bs-target='#editCustomer'><i class="fa-sharp fa-solid fa-pen-to-square"></i></button>
                          <button class="btn btn-danger btn delete_btn " data-bs-toggle='modal' data-bs-target='#deleteCustomer'><i class="fa-sharp fa-solid fa-trash "></i></button>
                        </div>
                      </td>
                    </tr>
                <?php }
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


  <!--Add customer Modal -->
  <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomer" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add customer</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="js-validation-signin" action="" method="POST">
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="name" placeholder="Customer name">
                <span class="input-group-text">
                  <i class="fa fa-user-circle"></i>
                </span>
              </div>
            </div>
            <div class="mb-4">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="contact" placeholder="contact">
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info" name="add_customer">Add Customer</button>
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
                <input type="text" class="form-control" id="name" name="name" placeholder="Customer name">
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="edit_customer">Save changes</button>
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
            Are you sure, you want to delete this customer
          </h3>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="delete_customer"><i class="fa-sharp fa-regular fa-trash"></i>Delete</button>
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
        var contact = $(this).closest("tr").find('td:nth-child(4)').text().trim();
        $('#phone').val(contact);
        var email = $(this).closest("tr").find('td:nth-child(3)').text().trim();
        $('#email').val(email);
      });

    });
  </script>
</body>

</html>