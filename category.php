<?php 
session_start();
include "admin/connection/conn.php"; 

// Fetch the category from the GET request or other sources
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Debugging statement to check if the category is correctly fetched
// echo "<p>Selected Category: " . htmlspecialchars($category) . "</p>";

// Query to fetch news items based on the selected category
$query = "SELECT * FROM news_post WHERE category = '$category'";
$result = $conn->query($query);

// Check for query execution errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Debugging statement to check if the result set has any rows
// echo "<p>Number of News Items: " . $result->num_rows . "</p>";

// Fetch news items and comment counts
$query = "
SELECT np.*, COALESCE(COUNT(c.cc_id), 0) AS comment_count
FROM news_post np
LEFT JOIN comments c ON np.n_id = c.n_id
WHERE np.category = '$category'
GROUP BY np.n_id";
$result = $conn->query($query);


$category_id = isset($_GET['category']) ? $_GET['category'] : '';

// Query to fetch the category name from the category table
$category_query = "SELECT category_name FROM category WHERE c_id = '$category_id'";
$category_result = $conn->query($category_query);

if ($category_result->num_rows > 0) {
    $category_row = $category_result->fetch_assoc();
    $category_name = $category_row['category_name'];
} else {
    die("Category not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ICUTV</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <?php include "header.php"; ?>

    <div class="container-fluid mt-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4 class="m-0 text-uppercase font-weight-bold"><?php echo htmlspecialchars($category_name); ?></h4>
                            </div>
                        </div>

                        <!-- News Items -->
                   <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $comment_count = isset($row['comment_count']) ? $row['comment_count'] : 0;
                                // Construct the correct path to the image
                                $image_path = 'admin/' . htmlspecialchars($row['news_image']);
                                ?>
                            
                            <div class="col-lg-6">
                                <div class="position-relative mb-3">
                                    <img class="img-fluid w-100" src="admin/<?php echo htmlspecialchars($row['news_image']); ?>" style="height: 40vh;">
                                    <div class="bg-white border border-top-0 p-4">
                                        <div class="mb-2">
                                            <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" href="single.php?n_id=<?php echo htmlspecialchars($row['n_id']); ?>"><?php echo htmlspecialchars($category_name); ?></a>
                                            <a class="text-body" href=""><small><?php echo htmlspecialchars($row['date']); ?></small></a>
                                        </div>
                                        <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold" href="single.php?n_id=<?php echo htmlspecialchars($row['n_id']); ?>"><?php echo htmlspecialchars($row['news_title']); ?></a>
                                    </div>
                                    <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                                        <div class="d-flex align-items-center">
                                            <small><?php echo htmlspecialchars($row['author']); ?></small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="ml-3"><i class="far fa-comment mr-2"></i><?php echo htmlspecialchars($row['comment_count']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='col-12'><p>No news content found for this category.</p></div>";
                    }
                    ?>


                    </div>
                </div>

               <?php
                include_once "news_trend.php";
                ?>
            </div>
        </div>
    </div>

   <?php
    include 'footer.php';
    ?>
   


  
    <a href="#" class="btn btn-primary btn-square back-to-top"><i class="fa fa-arrow-up"></i></a>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>


    <script src="js/main.js"></script>
</body>

</html>