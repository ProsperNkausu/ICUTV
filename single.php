<?php
session_start();
include "admin/connection/conn.php"; 
// Fetch the news ID from the URL
$news_id = isset($_GET['n_id']) ? intval($_GET['n_id']) : 0;

// Initialize variables
$comment_count = 0;
$comments_result = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate input
    if (!empty($name) && !empty($message)) {
        // Fetch news title
        $news_query = "SELECT news_title FROM news_post WHERE n_id = ?";
        $news_stmt = $conn->prepare($news_query);
        $news_stmt->bind_param('i', $news_id);
        $news_stmt->execute();
        $news_result = $news_stmt->get_result();
        $news_title = $news_result->num_rows > 0 ? $news_result->fetch_assoc()['news_title'] : 'Unknown Title';

        // Insert comment into the comments table
        $comment_query = "INSERT INTO comments (user, author, news_title, n_id, comment, date) VALUES (?, ?, ?, ?, ?, NOW())";
        $comment_stmt = $conn->prepare($comment_query);
        $comment_stmt->bind_param('sssis', $name, $name, $news_title, $news_id, $message);

        if ($comment_stmt->execute()) {
            echo '<div class="alert alert-success">Comment submitted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error submitting comment. Please try again later.</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Please fill in all required fields.</div>';
    }
}

// Query to fetch news details based on the news ID
$query = "SELECT * FROM news_post WHERE n_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $news_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the news item was found
if ($result->num_rows > 0) {
    $news = $result->fetch_assoc();
    $news_title = htmlspecialchars($news['news_title']);
    $news_article = htmlspecialchars($news['news_article']);
    $news_images = [
        'admin/' . htmlspecialchars($news['news_image']),
        'admin/' . htmlspecialchars($news['newsImage2']),
        'admin/' . htmlspecialchars($news['newsImage3'])
    ];
    $news_category = htmlspecialchars($news['category']);
    $news_date = date('F j, Y', strtotime($news['date']));
    $author = htmlspecialchars($news['author']); // Fetching the author's name
} else {
    echo "News item not found.";
    exit;
}

// Query to fetch the category name based on the category in news_post
$category_id_query = "SELECT category FROM news_post WHERE n_id = ?";
$category_id_stmt = $conn->prepare($category_id_query);
$category_id_stmt->bind_param('i', $news_id);
$category_id_stmt->execute();
$category_id_result = $category_id_stmt->get_result();
$category_id = $category_id_result->num_rows > 0 ? $category_id_result->fetch_assoc()['category'] : 0;

$category_name_query = "SELECT category_name FROM category WHERE c_id = ?";
$category_name_stmt = $conn->prepare($category_name_query);
$category_name_stmt->bind_param('i', $category_id);
$category_name_stmt->execute();
$category_name_result = $category_name_stmt->get_result();
$category_name = $category_name_result->num_rows > 0 ? $category_name_result->fetch_assoc()['category_name'] : 'Unknown Category';

// Query to fetch comments for the news item
$comments_query = "SELECT * FROM comments WHERE n_id = ? ORDER BY date DESC";
$comments_stmt = $conn->prepare($comments_query);
$comments_stmt->bind_param('i', $news_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comment_count = $comments_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $news_title; ?> | ICUTV</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="position-relative mb-3">
                        <div id="news-carousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <?php foreach ($news_images as $index => $image) {
                                    if (!empty($image)) { ?>
                                        <li data-target="#news-carousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                                    <?php }
                                } ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php foreach ($news_images as $index => $image) {
                                    if (!empty($image)) { ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img class="d-block w-100" src="<?php echo $image; ?>" alt="Slide <?php echo $index + 1; ?>" style="height: 550px;">
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                            <a class="carousel-control-prev" href="#news-carousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#news-carousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="bg-white border border-top-0 p-4">
                            <div class="mb-2">
                                <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"><?php echo $category_name; ?></a>
                                <a class="text-body" href=""><small><?php echo $news_date; ?></small></a>
                            </div>
                            <h1 class="mb-3 text-secondary text-uppercase font-weight-bold"><?php echo $news_title; ?></h1>
                            <p><?php echo nl2br($news_article); ?></p>
                        </div>
                        <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                            <div class="d-flex align-items-center">
                                <span><?php echo htmlspecialchars($author); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="ml-3"><i class="far fa-comment mr-2"></i><?php echo $comment_count; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Display comments -->
                    <div class="mb-3">
                        <h4>Comments (<?php echo $comment_count; ?>)</h4>
                        <?php if ($comments_result && $comments_result->num_rows > 0) {
                            while ($comment = $comments_result->fetch_assoc()) { ?>
                                <div class="bg-light p-3 mb-3">
                                    <h5><?php echo htmlspecialchars($comment['author']); ?></h5>
                                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                                    <span class="text-muted"><?php echo date('F j, Y', strtotime($comment['date'])); ?></span>
                                </div>
                            <?php }
                        } else { ?>
                            <p>No comments yet.</p>
                        <?php } ?>
                    </div>

                    <!-- Comment form -->
                    <div class="bg-light p-3">
                        <h4>Leave a Comment</h4>
                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Comment</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                 <?php
                include_once "news_trend.php";
                ?>
            </div>
        </div>
    </div>
    <?php 
    include "footer.php";
    ?>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
        <script src="js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-c+UCCXFxQDoBRKpEhbF5ZCr+Cv/T4TZBBKOFsB9ZExTxjvCmlcTAT+jefGLf5B64" crossorigin="anonymous"></script>
    </body>
</html>
