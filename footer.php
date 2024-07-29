<?php
include_once "admin/connection/conn.php"; 

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch popular news based on comment count
$sql = "SELECT n.n_id, n.news_title, n.date, n.news_image, c.category_name, COUNT(cm.cc_id) as comment_count
        FROM news_post n
        JOIN category c ON n.category = c.c_id
        LEFT JOIN comments cm ON n.n_id = cm.n_id
        GROUP BY n.n_id, n.news_title, n.date, n.news_image, c.category_name
        ORDER BY comment_count DESC LIMIT 3"; // Adjust the limit as needed

$result = $conn->query($sql);
$popular_news = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $popular_news[] = $row;
    }
} else {
    $popular_news = null;
}
?>

<div class="container-fluid bg-dark pt-5 px-sm-3 px-md-5 mt-5">
       <div class="row py-4">
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="mb-4 text-white text-uppercase font-weight-bold">Get In Touch</h5>
                <p class="font-weight-medium"><i class="fa fa-map-marker-alt mr-2"></i>Plot No. 19877/M/1A/392 off Shantumbu Road, Kafue Lusaka ZM, 30226</p>
                <p class="font-weight-medium"><i class="fa fa-phone-alt mr-2"></i>+260 979314158</p>
                <p class="font-weight-medium"><i class="fa fa-envelope mr-2"></i>news@icutvzm.com</p>
                <h6 class="mt-4 mb-3 text-white text-uppercase font-weight-bold">Follow Us</h6>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-lg btn-secondary btn-lg-square mr-2" href="https://web.facebook.com/icutvzm"><i
                            class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-lg btn-secondary btn-lg-square mr-2" href="https://www.linkedin.com/showcase/icutvzm/"><i
                            class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-secondary btn-lg-square" href="https://www.youtube.com/@icutvzm"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="mb-4 text-white text-uppercase font-weight-bold">Popular News</h5>
                 <?php if ($popular_news): ?>
                    <?php foreach ($popular_news as $news): ?>
                        <div class="mb-3">
                            <div class="mb-2">
                                <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" href="">
                                    <?php echo htmlspecialchars($news['category_name']); ?>
                                </a>
                                <a class="text-body" href="">
                                    <small><?php echo date('M d, Y', strtotime($news['date'])); ?></small>
                                </a>
                            </div>
                            <a class="small text-body text-uppercase font-weight-medium" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>">
                                <?php echo htmlspecialchars($news['news_title']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-white">No popular news found.</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-12 mb-5">
                <h5 class="mb-4 text-white text-uppercase font-weight-bold">Categories</h5>
                <div class="m-n1">
                    <a href="category.php?category=1" class="btn btn-sm btn-secondary m-1">Business</a>
                    <a href="category.php?category=2" class="btn btn-sm btn-secondary m-1">Innovation</a>
                    <a href="category.php?category=3" class="btn btn-sm btn-secondary m-1">Sports</a>
                    <a href="category.php?category=4" class="btn btn-sm btn-secondary m-1">Travel</a>
                    <a href="category.php?category=5" class="btn btn-sm btn-secondary m-1">News</a>
                    <a href="category.php?category=6" class="btn btn-sm btn-secondary m-1">Culture</a>
                    <a href="category.php?category=7" class="btn btn-sm btn-secondary m-1">Education</a>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="container-fluid py-4 px-sm-3 px-md-5" style="background: #111111;">
        <p class="m-0 text-center">&copy; <a href="https://www.linkedin.com/showcase/icutvzm/">ICUTV</a>. All Rights Reserved.

           
            Design by Prosper and Steven.</a>
        </p>
    </div>