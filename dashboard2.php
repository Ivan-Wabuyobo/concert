<?php
session_start();
include "dbconnect.php";
if(!isset($_SESSION['user'])){
    header("location:login.php");
}

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
  <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

    <!--Siderbar-->
    <?php include "sidebar.php" ?>
    <!--End Siderbar-->

    <!-- Header -->
    <?php include "header.php" ?>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
      <!-- Hero -->
      <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
          <div>
            <h1 class="h3 mb-1">
              Dashboard
            </h1>
            <p class="fw-medium mb-0 text-muted">
              You are Welcome,<b> <?php echo $_SESSION['user']['username']?>!</b> Use the Dashboard to know how the system is <a class="fw-medium" href="javascript:void(0)">performing</a>.
            </p>
          </div>
          <div class="mt-4 mt-md-0">
            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-sm btn-alt-primary px-3" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Last 30 days <i class="fa fa-fw fa-angle-down"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
                <a class="dropdown-item" href="javascript:void(0)">This Week</a>
                <a class="dropdown-item" href="javascript:void(0)">Previous Week</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0)">This Month</a>
                <a class="dropdown-item" href="javascript:void(0)">Previous Month</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END Hero -->

      <!-- Page Content -->
      <div class="content">
        <!-- Overview -->
        <div class="row items-push">
          <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1">
                <div class="item rounded-3 bg-body mx-auto my-3">
                  <i class="fa fa-users fa-lg text-primary"></i>
                </div>
                <?php
                $sql = "SELECT * FROM `users`";
                $results = $conn->query($sql);
                ?>
                <div class="fs-1 fw-bold"><?php echo $results->num_rows ?></div>
                <div class="text-muted mb-3">Organised Events</div>

              </div>
              <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                <a class="fw-medium" href="users.php">
                  see more...
                  <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1">
                <div class="item rounded-3 bg-body mx-auto my-3">
                  <i class="fa fa-users fa-lg text-success"></i>
                  
                </div>
                <div class="fs-1 fw-bold"><?php

                                          $sql = "SELECT * FROM `promoter`";
                                          $results = $conn->query($sql);
                                          echo $results->num_rows;
                                          ?></div>
                <div class="text-muted mb-3">Upcoming Events</div>
              </div>
              <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                <a class="fw-medium" href="promoters.php">
                  Details..
                  <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1">
                <div class="item rounded-3 bg-body mx-auto my-3">
                  <i class="fa fa-money-bill fa-lg text-warning"></i>
                </div>
                <div class="fs-1 fw-bold"><?php

                                          $sql = "SELECT * FROM `customers`";
                                          $results = $conn->query($sql);
                                          echo $results->num_rows;
                                          ?></div>
                <div class="text-muted mb-3">Today's bookings</div>

              </div>
              <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                <a class="fw-medium" href="customers.php">
                  Details..
                  <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1">
                <div class="item rounded-3 bg-body mx-auto my-3">
                <i class="fa-solid fa-money-check-dollar fa-lg text-danger"></i>

                </div>
                <div class="fs-1 fw-bold"><?php
                                          $sql = "SELECT SUM(payments.amount) AS payments FROM `payments`";
                                          $results = $conn->query($sql);
                                          echo $results->fetch_assoc()['payments'];
                                          ?></div>
                <div class="text-muted mb-3">Today's Payments</div>

              </div>
              <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                <a class="fw-medium" href="payments.php">
                  Details..
                  <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <!-- END Overview -->

        <!-- Latest Orders + Stats -->
        <div class="row">
          <div class="col-md-12">
            <!--  Latest Orders -->
            <div class="block block-rounded block-mode-loading-refresh">
              <div class="block-header block-header-default">
                <h3 class="block-title">
                  Latest Transactions
                </h3>
                <div class="block-options">
                  <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                  </button>
                  <div class="dropdown">
                    <button type="button" class="btn-block-option" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="si si-chemistry"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item" href="javascript:void(0)">
                        <i class="far fa-fw fa-dot-circle opacity-50 me-1"></i> Pending
                      </a>
                      <a class="dropdown-item" href="javascript:void(0)">
                        <i class="far fa-fw fa-times-circle opacity-50 me-1"></i> Canceled
                      </a>
                      <a class="dropdown-item" href="javascript:void(0)">
                        <i class="far fa-fw fa-check-circle opacity-50 me-1"></i> Completed
                      </a>
                      <div role="separator" class="dropdown-divider"></div>
                      <a class="dropdown-item" href="javascript:void(0)">
                        <i class="fa fa-fw fa-eye opacity-50 me-1"></i> View All
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="block-content">
                <table class="table table-striped table-hover table-borderless table-vcenter fs-sm">
                  <thead>
                    <tr class="text-uppercase">
                      <th>Transaction Id</th>
                      <th>Transaction Type</th>
                      <th>Performed by</th>
                      <th>user Role</th>
                      <th>Transaction Time</th>
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
                    $sql = "SELECT * FROM `log` JOIN users ON users.id = log.user ORDER BY log.id DESC" ;
                    $results = $conn->query($sql);
                    while ($transaction = $results->fetch_assoc()) {
                    ?>
                      <tr>
                        <td>
                          <span class="fw-semibold"><?php echo $transaction['transaction_id'] ?></span>
                        </td>
                        <td>
                          <?php echo $transaction['transaction'] ?>
                        </td>
                        <td>
                          <span class="fs-sm text-muted"><?php echo $transaction['username'] ?></span>
                        </td>

                        <td>
                          <?php if ($transaction['role'] == '1') {
                            echo "Admin";
                          } else if ($transaction['role'] == '2') {
                            echo "Promoter/Organiser";
                          } else {
                            echo "Customer/member";
                          }
                          ?>
                        </td>

                        <td class="text-center">
                          <?php echo
                           time_ago($transaction['uploaded_at']);
                           ?>
                        </td>
                      </tr>
                    <?php }  ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- END Latest Orders -->
          </div>

        </div>
        <!-- END Latest Orders + Stats -->
      </div>
      <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <?php include "footer.php" ?>
    <!-- END Footer -->
  </div>
  <!-- END Page Container -->
  <script src="assets/js/dashmix.app.min.js"></script>

  <!-- Page JS Plugins -->
  <script src="assets/js/plugins/chart.js/chart.min.js"></script>

  <!-- Page JS Code -->
  <script src="assets/js/pages/be_pages_dashboard.min.js"></script>
</body>

</html>