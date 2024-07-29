<?php
session_start();

require_once('connection/conn.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}

// Fetch subscriber count
$subscriber_count_query = "SELECT COUNT(*) as count FROM newsletter_subscribers";
$subscriber_count_result = $conn->query($subscriber_count_query);
$subscriber_count = $subscriber_count_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ICUTV Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php
    include_once 'nav.php';
    ?>
    <div id="layoutSidenav">
        <?php include 'header.php' ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Add News Post</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="add_news.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-dark text-white mb-4">
                                <div class="card-body">News Post</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="edit_news.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card  text-white mb-4" style="background-color:grey;">
                                <div class="card-body">Main Website</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="../index.php">View Website</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card  text-white mb-4" style="background-color:grey;">
                                <div class="card-body">Subscribers (<?= $subscriber_count ?>)</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" id="viewSubscribers">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="subscribersTable" style="display:none;">
                        <h2>Subscribers List</h2>
                        <table id="subscribersDataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Subscribed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        document.getElementById('viewSubscribers').addEventListener('click', function(event) {
            event.preventDefault();
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_subscribers.php', true);
            xhr.onload = function() {
                if (this.status == 200) {
                    var subscribers = JSON.parse(this.responseText);
                    var output = '';
                    for(var i in subscribers) {
                        output += '<tr>' +
                                    '<td>' + subscribers[i].id + '</td>' +
                                    '<td>' + subscribers[i].email + '</td>' +
                                    '<td>' + subscribers[i].subscribed_at + '</td>' +
                                  '</tr>';
                    }
                    document.querySelector('#subscribersDataTable tbody').innerHTML = output;
                    document.getElementById('subscribersTable').style.display = 'block';
                }
            }
            xhr.send();
        });
    </script>
</body>
</html>
