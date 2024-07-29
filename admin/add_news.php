<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}

include 'connection/conn.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsTitle = $_POST['newsTitle'];
    $newsArticle = $_POST['newsPost'];
    $newsCategory = $_POST['newsCategory'];
    $author = "Admin"; // Sets author auto to "Admin"

    // Create an array to hold image file paths
    $imagePaths = [];
    $uploadDir = 'uploads/'; // Directory where images will be uploaded

    // Handle multiple image uploads
    for ($i = 1; $i <= 3; $i++) {
        $imageKey = 'newsImage' . $i;
        if (isset($_FILES[$imageKey]) && $_FILES[$imageKey]['error'] == UPLOAD_ERR_OK) {
            $newsImage = $_FILES[$imageKey];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($newsImage['type'], $allowedTypes)) {
                $filePath = $uploadDir . basename($newsImage['name']);

                // Move the file to the target directory
                if (move_uploaded_file($newsImage['tmp_name'], $filePath)) {
                    $imagePaths[] = $filePath;
                } else {
                    echo "Failed to move uploaded file: " . $newsImage['name'] . "<br>";
                }
            } else {
                echo "Invalid file type for file: " . $newsImage['name'] . "<br>";
            }
        } else {
            echo "Error uploading file: " . $_FILES[$imageKey]['name'] . " - Error code: " . $_FILES[$imageKey]['error'] . "<br>";
        }
    }

    if (count($imagePaths) == 3) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO news_post (news_title, news_article, news_image, newsimage2, newsimage3, category, date, author) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        if (!$stmt) {
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
        }
        // Bind the author parameter
        $stmt->bind_param("sssssss", $newsTitle, $newsArticle, $imagePaths[0], $imagePaths[1], $imagePaths[2], $newsCategory, $author);

        if ($stmt->execute()) {
            echo "News post added successfully.";
            // Redirect or show success message
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "All three images must be uploaded.<br>";
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
    <title>Add News/ICUTV Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .form-group {
            margin-top: 10px;
        }
        .newsImage{
            margin-bottom:10px;
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
                    <h1 class="mt-4">Add News Post</h1>
                    <ol class="breadcrumb mb4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add News Post</li>
                    </ol>
                    <div class="container form-container">
                        <!-- Update the form to use POST and handle file uploads -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newsTitle" class="newsImage">News Title</label>
                                <input type="text" class="form-control" id="newsTitle" name="newsTitle" placeholder="Enter news title" required>
                            </div>
                            <div class="form-group">
                                <label for="newsImage1" class="newsImage">News Image</label><br>
                                <input class="form-control" type="file" id="newsImage1" name="newsImage1" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="newsImage2" class="newsImage">Image 2</label><br>
                                <input class="form-control" type="file" id="newsImage2" name="newsImage2" required>
                            </div>
                            <div class="form-group">
                                <label for="newsImage3" class="newsImage">Image 3</label><br>
                                <input class="form-control" type="file" id="newsImage3" name="newsImage3" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="newsCategory">News Category</label><br>
                                <select class="form-select form-select-md mb-9" id="newsCategory" name="newsCategory" required>
                                    <option value="" disabled selected>Choose category</option>
                                    <?php
                                    // Fetch categories from database
                                    $result = $conn->query("SELECT c_id, category_name FROM category");
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['c_id'] . '">' . $row['category_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="newsPost">News Post</label>
                                <textarea class="form-control" id="newsPost" name="newsPost" rows="10" placeholder="Start writing article..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
