<?php
session_start();

include_once "connection/conn.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_highlight'])) {
    $h_id = $_POST['h_id'];
    $highlight_1 = $_POST['Highlight_1'];
    $highlight_2 = $_POST['Highlight_2'];
    $highlight_3 = $_POST['Highlight_3'];

    $sql = "UPDATE highlight SET highlight_1=?, highlight_2=?, highlight_3=? WHERE h_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $highlight_1, $highlight_2, $highlight_3, $h_id);

    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

// Fetch highlights
$result = $conn->query("SELECT * FROM highlight");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add Highlight/ICUTV Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .form-group {
            margin-top: 10px;
        }
    </style>
</head>
<body>
      <?php
    include_once 'nav.php';
    ?>
    <div id="layoutSidenav">
        <?php include 'header.php' ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Add News Highlight</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Highlightt</li>
                    </ol>
                    <div class="container form-container">
                        <!-- Update the form to use POST and handle file uploads -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="advertTitle">News Highlight</label>
                                <input type="text" class="form-control" id="a_Title" name="Highlight_1" placeholder="Write news Highlight" required>
                            </div>

                             <div class="form-group">
                                <label for="advertTitle">Highlight 2</label>
                                <input type="text" class="form-control" id="a_Title" name="Highlight_2" placeholder="Write news Highlight" required>
                            </div>

                             <div class="form-group">
                                <label for="advertTitle">Highlight 3</label>
                                <input type="text" class="form-control" id="a_Title" name="Highlight_3" placeholder="Write news Highlight" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>

                    <div class="card mb-6">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            News Post Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>h_id</th>
                                        <th>Highlight 1</th>
                                        <th>Highlight 2</th>
                                        <th>Highlight 3</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                         <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo $row['h_id']; ?></td>
                                            <td><?php echo $row['highlight_1']; ?></td>
                                            <td><?php echo $row['highlight_2']; ?></td>
                                            <td><?php echo $row['highlight_3']; ?></td>
                                            <td>
                                             <button type="button" class="btn btn-primary" style="width:100%; font-size:12px;"  data-bs-toggle="modal" data-bs-target="#highlightModal<?php echo $row['h_id']; ?>">View Highlight</button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="highlightModal<?php echo $row['h_id']; ?>" tabindex="-1" aria-labelledby="highlightModalLabel<?php echo $row['h_id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="highlightModalLabel<?php echo $row['h_id']; ?>">Update Highlight</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST">
                                                            <div class="form-group">
                                                                <label for="highlight_1">News Highlight</label>
                                                                <input type="text" class="form-control" id="highlight_1" name="Highlight_1" value="<?php echo $row['highlight_1']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="highlight_2">Highlight 2</label>
                                                                <input type="text" class="form-control" id="highlight_2" name="Highlight_2" value="<?php echo $row['highlight_2']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="highlight_3">Highlight 3</label>
                                                                <input type="text" class="form-control" id="highlight_3" name="Highlight_3" value="<?php echo $row['highlight_3']; ?>" required>
                                                            </div>
                                                            <input type="hidden" name="h_id" value="<?php echo $row['h_id']; ?>">
                                                            <button type="submit" class="btn btn-primary" name="update_highlight">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
