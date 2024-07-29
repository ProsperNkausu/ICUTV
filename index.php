<?php
session_start();

include_once "admin/connection/conn.php";

// Fetch the latest highlight record
$sql = "SELECT highlight_1, highlight_2, highlight_3 FROM highlight ORDER BY h_id DESC LIMIT 1";
$result = $conn->query($sql);

$highlight_1 = "No highlight available.";
$highlight_2 = "No highlight available.";
$highlight_3 = "No highlight available.";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $highlight_1 = $row['highlight_1'];
    $highlight_2 = $row['highlight_2'];
    $highlight_3 = $row['highlight_3'];
}

$category = ''; 

// Fetch news posts
$sql = "
SELECT np.n_id, np.news_title, np.date, np.news_image,np.author, c.category_name, COALESCE(COUNT(c2.cc_id), 0) AS comment_count
FROM news_post np
LEFT JOIN category c ON np.category = c.c_id
LEFT JOIN comments c2 ON np.n_id = c2.n_id
GROUP BY np.n_id
";

if (!empty($category)) {
    $sql .= " WHERE np.category = '$category'";
}

$sql .= " ORDER BY np.date DESC"; 
$result = $conn->query($sql);

// Check if $result contains data
if ($result === false) {
    die("Error: " . $conn->error);
}

$news_posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_posts[] = $row;
    }
}

// Fetch popular news posts based on comment count
$sql = "SELECT np.n_id, np.news_title, np.date, np.news_image, c.category_name
        FROM news_post np
        LEFT JOIN category c ON np.category = c.c_id
        LEFT JOIN comments com ON np.n_id = com.n_id
        GROUP BY np.n_id
        ORDER BY COUNT(com.n_id) DESC
        LIMIT 4"; // Adjust the limit as needed

$result = $conn->query($sql);

$popular_news = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $popular_news[] = $row;
    }
}

// Fetch the most recent news posts
$sql = "SELECT np.n_id, np.news_title, np.date, np.news_image, c.category_name
        FROM news_post np
        LEFT JOIN category c ON np.category = c.c_id
        ORDER BY np.date DESC
        LIMIT 3"; // Adjust the limit as needed

$result = $conn->query($sql);

$recent_news = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_news[] = $row;
    }
}

// Fetch trending news
$sql = "SELECT n.n_id, n.news_title, n.date, n.news_image, cat.category_name, COUNT(com.n_id) AS comment_count
        FROM news_post n
        LEFT JOIN comments com ON n.n_id = com.n_id
        JOIN category cat ON n.category = cat.c_id
        GROUP BY n.n_id
        ORDER BY comment_count DESC
        LIMIT 5";
$result = $conn->query($sql);

$trending_news = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $trending_news[] = $row;
    }
}

// Fetch advertisement data
$sql = "SELECT ad_image, ad_link FROM advertisment ORDER BY ad_id DESC LIMIT 1";
$result = $conn->query($sql);

$advertisement = null;

if ($result->num_rows > 0) {
    $advertisement = $result->fetch_assoc();
}
// $conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ICUTV</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
   
    <link href="img/favicon.ico" rel="icon">

 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    
    <link href="css/style.css" rel="stylesheet">
</head>
<style>
   
</style>

<body>
    <?php 
    include "header.php";
    ?>
   
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 px-0">
    <div class="owl-carousel main-carousel position-relative">
        <?php foreach ($recent_news as $news): ?>
        <div class="position-relative overflow-hidden" style="height: 500px;">
            <img class="img-fluid h-100" src="admin/<?php echo htmlspecialchars($news['news_image']); ?>" style="object-fit: cover;">
            <div class="overlay">
                <div class="mb-2">
                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                        href="category.php?category=<?php echo htmlspecialchars($news['category_name']); ?>"><?php echo htmlspecialchars($news['category_name']); ?></a>
                    <a class="text-white" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>"><?php echo date('M d, Y', strtotime($news['date'])); ?></a>
                </div>
                <a class="h2 m-0 text-white text-uppercase font-weight-bold" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>">
                    <?php echo htmlspecialchars($news['news_title']); ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

           <div class="col-lg-5 px-0">
    <div class="row mx-0">
        <?php foreach ($popular_news as $news): ?>
        <div class="col-md-6 px-0">
            <div class="position-relative overflow-hidden" style="height: 250px;">
                <img class="img-fluid w-100 h-100" src="admin/<?php echo htmlspecialchars($news['news_image']); ?>" style="object-fit: cover;">
                <div class="overlay">
                    <div class="mb-2">
                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                            href="category.php?category=<?php echo htmlspecialchars($news['category_name']); ?>"><?php echo htmlspecialchars($news['category_name']); ?></a>
                        <a class="text-white" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>"><small><?php echo date('M d, Y', strtotime($news['date'])); ?></small></a>
                    </div>
                    <a class="h6 m-0 text-white text-uppercase font-weight-semi-bold" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>">
                        <?php echo htmlspecialchars($news['news_title']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
        </div>
    </div>
 

    
  <!-- highlights -->
    <div class="container-fluid bg-dark py-3 mb-3">
        <div class="container">
            <div class="row align-items-center bg-dark">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div class="bg-primary text-light text-center button-news font-weight-medium py-2" style="width: 170px; align-content: center;">
                            Breaking News</div>
                        <div class="owl-carousel tranding-carousel position-relative d-inline-flex align-items-center ml-3"
                            style="width: calc(100% - 170px); padding-right: 90px;">
                            <div class="text-truncate"><h6 class="text-white text-uppercase font-weight-semi-bold"
                                    ><?php echo htmlspecialchars($highlight_1); ?></h6></div>
                            <div class="text-truncate"><h6 class="text-white text-uppercase font-weight-semi-bold"
                                    > <?php echo htmlspecialchars($highlight_2); ?></h6></div>
                            <div class="text-truncate"><h6 class="text-white text-uppercase font-weight-semi-bold"
                                        > <?php echo htmlspecialchars($highlight_3); ?></h6></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


   <!-- featured news -->
      <!-- Featured News Slider Start -->
    <div class="container-fluid pt-5 mb-3">
        <div class="container">
            <div class="section-title">
                <h4 class="m-0 text-uppercase font-weight-bold">Featured News</h4>
            </div>
            <div class="owl-carousel news-carousel carousel-item-4 position-relative">
            <?php
                if (isset($news_posts) && count($news_posts) > 0) {
                    foreach ($news_posts as $post) {
                        echo '<div class="position-relative overflow-hidden" style="height: 300px;">';
                        echo '<img class="img-fluid h-100" src="admin/' . htmlspecialchars($post["news_image"]) . '" style="object-fit: cover;">';
                        echo '<div class="overlay">';
                        echo '<div class="mb-2">';
                        echo '<a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2" href="">' . htmlspecialchars($post["category_name"]) . '</a>';
                        echo '<a class="text-white" id="date" href=""><small>' . htmlspecialchars($post["date"]) . '</small></a>';
                        echo '</div>';
                        echo '<a class="h6 m-0 text-white text-uppercase font-weight-semi-bold" href="single.php?n_id=' . htmlspecialchars($post["n_id"]) . '">' . htmlspecialchars($post["news_title"]) . '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No news posts available.";
                }
                ?>
        </div>
        </div>
    </div>
    <!-- Featured News Slider End -->
   


    
     <!-- News With Sidebar Start -->
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                     <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4 class="m-0 text-uppercase font-weight-bold">Latest News</h4>
                                
                            </div>
                        </div>
                        <?php foreach ($news_posts as $post): ?>
                        <div class="col-lg-6">
                            <div class="position-relative mb-3">
                                <img class="img-fluid w-100" src="admin/<?php echo htmlspecialchars($post['news_image']); ?>" style=" height:40vh">
                                <div class="bg-white border border-top-0 p-4">
                                    <div class="mb-2">
                                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </a>
                                        <a class="text-body" href="">
                                            <small><?php echo date('M d, Y', strtotime($post['date'])); ?></small>
                                        </a>
                                    </div>
                                    <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold" href="single.php?n_id=<?php echo htmlspecialchars($post['n_id']); ?>">
                                    <?php echo htmlspecialchars($post['news_title']); ?>
                                    </a>
                                </div>
                                <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                                    <div class="d-flex align-items-center">
                                        
                                        <small><?php echo htmlspecialchars($post['author']); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <small class="ml-3">
                                            <i class="far fa-comment mr-2"></i>
                                            <?php echo htmlspecialchars($post['comment_count']); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                
                </div>

                <?php
                include_once "news_trend.php";
                ?>
            </div>
        </div>
    </div>
    <!-- News With Sidebar End -->
          
                   
    
                
                </div>
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