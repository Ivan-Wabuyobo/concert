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
        <main id="main-container ">
            <div class="content">
                <div>
                    <p></p>
                </div>
                <canvas id="myChart"></canvas>
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
        <?php
        include "dbconnect.php";

        $sql = "SELECT promoter.name, COUNT(events.event_id) AS num_events
        FROM promoter
        LEFT JOIN events ON promoter.id = events.promoter_id
        GROUP BY promoter.name";
        $results = $conn->query($sql);
        $xValues =  array();
        $yValues = array();
       

        while ($data = $results->fetch_assoc()) {
            $xValues[] = $data['name'];
            $yValues[] = $data['num_events'];
        }
        $xValues_json = json_encode($xValues);
        $yValues_json = json_encode($yValues);
        ?>
        var barColors = ["red", "purple", "blue", "orange", "brown", "purple", "lemon green", "cream"];

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: <?php echo $xValues_json; ?>,
                datasets: [{
                    backgroundColor: barColors,
                    data: <?php echo $yValues_json; ?>
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Events analysis as organised by the promoters"
                }
            }
        });
    </script>

</body>

</html>