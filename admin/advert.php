<?php
session_start();

include 'connection/conn.php'; 

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}


// Delete user if delete request is received
if (isset($_GET['delete_user_id'])) {
    $ad_id = $_GET['delete_user_id'];
    $delete_query = $conn->prepare("DELETE FROM advertisment WHERE ad_id = ?");
    $delete_query->bind_param("i", $ad_id);
    $delete_query->execute();
    header("Location: advert.php");
    exit;
}

// Fetch all users from the database
$ad_query = "SELECT ad_id, ad_image, ad_link, date FROM advertisment";
$result = $conn->query($ad_query);



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ad_link']) && isset($_FILES['ad_image'])) {
        $advert_name = $_POST['ad_link'];
        $target_dir = "uploads/"; // Directory where files will be uploaded
        $target_file = $target_dir . basename($_FILES["ad_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Debugging: Print target directory and file path
        echo "Target directory: $target_dir<br>";
        echo "Target file: $target_file<br>";

        // Check if file is an actual image or fake image
        $check = getimagesize($_FILES["ad_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo " <script> alert('File is not an image.')</script>";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "<script> alert('Sorry, file already exists.')</script>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["ad_image"]["size"] > 500000) {
            echo "<script> alert('Sorry, your file is too large.')</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script> alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.')</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo " <script> alert('Sorry, your file was not uploaded.')</script>";
        } else {
            // Debugging: Print tmp_name of the file
            echo "Temporary file name: " . $_FILES["ad_image"]["tmp_name"] . "<br>";

            if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $target_file)) {
                // Insert data into database
                $stmt = $conn->prepare("INSERT INTO advertisment (ad_link, ad_image) VALUES (?, ?)");
                if (!$stmt) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
                }
                $stmt->bind_param("ss", $advert_name, $target_file);
                if ($stmt->execute()) {
                    echo "<script> alert('The file " . htmlspecialchars(basename($_FILES["ad_image"]["name"])) . " has been uploaded and record added to database.<br>')</script>";
                } else {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br>";
                }
                $stmt->close();
            } else {
                echo "<script> alert('Sorry, there was an error uploading your file.')</script> ";
            }
        }
    } else {
        echo "<script> alert('Required fields are missing.')</script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add Advertisment / ICUTV Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .form-group {
            margin-top: 10px;
        }

           .button-d {
            text-decoration: none;
            background-color: red;
            padding: 8px;
            color: white;
            border-radius: 5px;
            border:none;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/dgz9p6uxhe01gh2basvv13znwf2rms5b24jks58cnlrw70qo/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>
      <?php
    include_once 'nav.php';
    ?>
    <div id="layoutSidenav">
        <?php include 'header.php' ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 ">
                    <h1 class="mt-4">Add Advertisment</h1>
                    <ol class="breadcrumb mb_4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Advertisment</li>
                    </ol>
                    <div class="container form-container">
                        <!-- Update the form to use POST and handle file uploads -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="advertTitle">Advert Title</label>
                                <input type="text" class="form-control" id="a_Title" name="ad_link" placeholder="Enter advert title" required>
                            </div>
                            <div class="form-group">
                                <label for="Image">Advert Image</label><br>
                                <input class="form-control" type="file" id="a_Image" name="ad_image" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                    
                     <div class="card mb-6" style="margin-top:50px;">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Advertisment Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered">
                                    <thead>
                                    <tr>
                                         <th>ad_id</th>
                                        <th>ad_image</th>
                                        <th>ad_link</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                       <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo $row['ad_id']; ?></td>
                                            <td><img src="./<?= $row["ad_image"] ?>" style="max-width: 100%; max-height:5vh;" /></td>
                                            <td><?php echo $row['ad_link']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td>
                                                <a href="users.php?delete_user_id=<?php echo $row['ad_id']; ?>" class="button-d" onclick="return confirm('Are you sure you want to delete this user?')">Delete user</a>
                                            </td>
                                        </tr>
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
