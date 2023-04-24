<nav id="sidebar" aria-label="Main Navigation">
  <!-- Side Header -->
  <div class="bg-header-dark">
    <div class="content-header bg-white-5  bg-warning">
      <!-- Logo -->
      <a class="fw-semibold text-white tracking-wide" href="index.html">

        <span class="smini-hidden">
          Concert<span class="opacity-75">Mix</span>
        </span>
      </a>
      <!-- END Logo -->
      <!-- Options -->
      <div>
        <!-- Toggle Sidebar Style -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <!-- Class Toggle, functionality initialized in Helpers.dmToggleClass() -->
        <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on" onclick="Dashmix.layout('sidebar_style_toggle');Dashmix.layout('header_style_toggle');">
          <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
        </button>
        <!-- END Toggle Sidebar Style -->

        <!-- Dark Mode -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#dark-mode-toggler" data-class="far fa" onclick="Dashmix.layout('dark_mode_toggle');">
          <i class="far fa-moon" id="dark-mode-toggler"></i>
        </button>
        <!-- END Dark Mode -->

        <!-- Close Sidebar, Visible only on mobile screens -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
          <i class="fa fa-times-circle"></i>
        </button>
        <!-- END Close Sidebar -->
      </div>
      <!-- END Options -->
    </div>
  </div>
  <!-- END Side Header -->

  <!-- Sidebar Scrolling -->
  <div class="js-sidebar-scroll">
    <!-- Side Navigation -->
    <div class="content-side">
      <ul class="nav-main">
        <li class="nav-main-item">
          <a class="nav-main-link active" href="<?php if ($_SESSION['user']['role'] == '1') {
                                                  echo "dashboard.php";
                                                } else {
                                                  echo "promoter_dashboard.php";
                                                } ?>">
            <i class="nav-main-link-icon fa fa-home text-warning"></i>
            <span class="nav-main-link-name">Home</span>
          </a>
        </li>
        <?php
        if ($_SESSION['user']['role'] == '1') {
        ?>
          <li class="nav-main-heading">System users</li>

          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="users.php">
              <i class="nav-main-link-icon fa fa-user text-warning"></i>
              <span class="nav-main-link-name">users</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="promoters.php">
              <i class="nav-main-link-icon fa fa-user text-warning"></i>
              <span class="nav-main-link-name">View all Promoters</span>
            </a>
          </li>
          
          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="customers.php">
              <i class="nav-main-link-icon fa fa-user text-warning"></i>
              <span class="nav-main-link-name">View all customers</span>
            </a>
          </li>
        <?php };
1

        ?>

        <li class="nav-main-heading">Events</li>
        <?php
        if ($_SESSION['user']['role'] == 1) {

        ?>
          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="events.php">
              <i class="nav-main-link-icon fa fa-bolt text-warning"></i>
              <span class="nav-main-link-name">All Events</span>
            </a>
          </li>
        <?php } else { ?>
          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="events.php">
              <i class="nav-main-link-icon fa fa-bolt text-warning"></i>
              <span class="nav-main-link-name">My Events</span>
            </a>
          </li>
        <?php } ?>
        <li class="nav-main-item">
          <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="pending_events.php">
            <i class="nav-main-link-icon fa fa-circle-xmark text-warning"></i>
            <span class="nav-main-link-name">Pending Events</span>
          </a>
        </li>
        <li class="nav-main-item">
          <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="completed_events.php">
            <i class="nav-main-link-icon fa fa-check text-warning"></i>
            <span class="nav-main-link-name">Completed Events</span>
          </a>
        </li>

        <li class="nav-main-heading">Packages and bookings</li>
        <li class="nav-main-item">
          <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="packages.php">
            <i class="nav-main-link-icon fa fa-truck text-warning"></i>
            <span class="nav-main-link-name">Event packages</span>
          </a>
        </li>
        <li class="nav-main-item">
          <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="bookings.php">
            <i class=" nav-main-link-icon fa-solid fa-hand-point-up  text-warning"></i>
            <span class="nav-main-link-name">Bookings</span>
          </a>
        </li>
        <?php
        if ($_SESSION['user']['role'] == 1) {
        ?>
          <li class="nav-main-heading">Analytics</li>
          <li class="nav-main-item">
            <a class="nav-main-link " aria-haspopup="true" aria-expanded="false" href="graphical.php">
              <i class="nav-main-link-icon fa fa-chart-line text-warning"></i>
              <span class="nav-main-link-name">Promoter performance</span>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
    <!-- END Side Navigation -->
  </div>
  <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->