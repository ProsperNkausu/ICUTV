<?php
include 'admin/connection/conn.php'; 

// Fetch advertisement data
$sql = "SELECT ad_image, ad_link FROM advertisment ORDER BY ad_id DESC LIMIT 1";
$result = $conn->query($sql);

$advertisement = null;

if ($result->num_rows > 0) {
    $advertisement = $result->fetch_assoc();
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

$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
$debug_message = '';
if (isset($_GET['debug'])) {
    $debug_message = htmlspecialchars($_GET['debug']);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->
</head>
<body>
    <div class="col-lg-4">
        <!-- Ads Start -->
        <div class="mb-3">
            <div class="section-title mb-0">
                <h4 class="m-0 text-uppercase font-weight-bold">Advertisement</h4>
            </div>
            <div class="bg-white text-center border border-top-0 p-3">
                <?php if ($advertisement): ?>
                    <a href="<?php echo htmlspecialchars($advertisement['ad_link']); ?>">
                        <img class="img-fluid" src="admin/<?php echo htmlspecialchars($advertisement['ad_image']); ?>" alt="Advertisement">
                    </a>
                <?php else: ?>
                    <p>Nothing to advertise yet</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Ads End -->

        <!-- Trending News Start -->
        <div class="mb-3">
            <div class="section-title mb-0">
                <h4 class="m-0 text-uppercase font-weight-bold">Trending News</h4>
            </div>
            <div class="bg-white border border-top-0 p-3">
                <?php foreach ($trending_news as $news): ?>
                <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                    <img class="img-fluid" style="height: 8vh; width:20%;" src="admin/<?php echo htmlspecialchars($news['news_image']); ?>" alt="">
                    <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center border border-left-0">
                        <div class="mb-2">
                            <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" href=""><?php echo htmlspecialchars($news['category_name']); ?></a>
                            <a class="text-body" href=""><small><?php echo date('M d, Y', strtotime($news['date'])); ?></small></a>
                        </div>
                        <a class="h6 m-0 text-secondary text-uppercase font-weight-bold" href="single.php?n_id=<?php echo htmlspecialchars($news['n_id']); ?>">
                            <?php echo htmlspecialchars($news['news_title']); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Trending News End -->

        <!-- Newsletter Start -->
        <div class="mb-3">
            <div class="section-title mb-0">
                <h4 class="m-0 text-uppercase font-weight-bold">Newsletter</h4>
            </div>
            <div class="bg-white text-center border border-top-0 p-3">
                <p>Stay up-to-date with the latest news by subscribing to our newsletter.</p>
                <form action="subscribe.php" method="post">
                    <div class="input-group mb-2" style="width: 100%;">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Your Email" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary font-weight-bold px-3" type="submit">Sign Up</button>
                        </div>
                    </div>
                    <?php if ($message): ?>
                        <p id="subscription-message"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    <?php if ($debug_message): ?>
                        <pre><?php echo htmlspecialchars($debug_message); ?></pre>
                    <?php endif; ?>
                </form>
                <small>We respect your privacy. Unsubscribe at any time.</small>
            </div>
        </div>
        <!-- Newsletter End -->
    </div>
</body>
</html>
