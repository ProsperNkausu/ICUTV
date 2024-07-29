<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">

</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">News</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        News Article
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="add_news.php">Add News Post</a>
                            <a class="nav-link" href="edit_news.php">Edit News Post</a>
                            <a href="highlight.php" class="nav-link">Highlights</a>
                            <a class="nav-link" href="comments.php">Comments</a>
                        </nav>
                    </div>

                    <a class="nav-link" href="users.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-user"></i></div>
                        Users
                    </a>

                    <a href="advert.php" class="nav-link">
                        <div class="sb-nav-link-icon"><i class="fa fa-line-chart" aria-hidden="true"></i></div>
                        Advertisment
                    </a>
                </div>
            </div>

        </nav>
    </div>
</body>

</html>