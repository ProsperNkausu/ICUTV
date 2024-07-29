
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
   
    <link href="img/favicon.ico" rel="icon">

 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    
    <link href="css/style.css" rel="stylesheet">

    <style>
        @media screen (max-width: 450px;){
            .show{
                position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 1rem;
            }
            
        }
    </style>
    
</head>
<body>
     <div class="container-fluid d-none d-lg-block">
        <div class="row align-items-center bg-dark px-lg-5 show" >
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-sm bg-dark p-0">
                    <ul class="navbar-nav ml-n2">
                       <li class="nav-item border-right border-secondary">
                            <a class="nav-link text-body small" href="index.php">
                                <?php echo date('l, F j, Y'); ?>
                            </a>
                        </li>
                        <li class="nav-item border-right border-secondary">
                            <a class="nav-link text-body small" href="contact.php">Contact</a>
                        </li>
                         <?php if(isset($_SESSION['user_loggedin']) && $_SESSION['user_loggedin']): ?>
                    <li class="nav-item">
                        <span class="nav-link">Hello, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-body small" href="admin\login\loginout.php">Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin']): ?>
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-body small" href="admin/index.php">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-body small" href="admin\login\loginout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-body small" href="admin/login/login.php">Login</a>
                    </li>
                <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 text-right d-none d-md-block">
                <nav class="navbar navbar-expand-sm bg-dark p-0">
                    <ul class="navbar-nav ml-auto mr-n2">
                        <li class="nav-item">
                            <a class="nav-link text-body" href="https://web.facebook.com/icutvzm"><small class="fab fa-facebook-f"></small></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" href="https://www.linkedin.com/showcase/icutvzm/"><small class="fab fa-linkedin-in"></small></a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" href="https://www.youtube.com/@icutvzm"><small class="fab fa-youtube"></small></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row align-items-center bg-white py-3 px-lg-5">
            <div class="col-lg-4">
                <a href="index.php" class="navbar-brand p-0 d-none d-lg-block">
                    <h1 class="m-0 display-4 text-uppercase text-primary">ICUTV <span
                            class="text-secondary font-weight-normal">News</span></h1>
                </a>
            </div>

        </div>
    </div>

     <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
            <a href="index.html" class="navbar-brand d-block d-lg-none">
                <h1 class="m-0 display-4 text-uppercase text-primary">ICUTV<span
                        class="text-white font-weight-normal">News</span></h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-0 px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">CATEGORIES</a>
                        <div class="dropdown-menu rounded-0 m-0">
                        <a href="category.php?category=1" class="dropdown-item">Business</a>
                        <a href="category.php?category=2" class="dropdown-item">Innovation</a>
                        <a href="category.php?category=3" class="dropdown-item">Sports</a>
                        <a href="category.php?category=4" class="dropdown-item">Travel</a>
                        <a href="category.php?category=5" class="dropdown-item">News</a>
                        <a href="category.php?category=6" class="dropdown-item">Culture</a>
                        <a href="category.php?category=7" class="dropdown-item">Education</a>
                    </div>

                    </div>
                    
                    <!-- <a href="single.html" class="nav-item nav-link">Single News</a> -->
                  
                    <a href="contact.php" class="nav-item nav-link">Contact</a>

                    <?php if(isset($_SESSION['user_loggedin']) && $_SESSION['user_loggedin']): ?>
                        <span class="nav-item nav-link d-lg-none">Hello, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>!</span>
                        <a class="nav-item nav-link d-lg-none" href="admin\login\loginout.php">Logout</a>
                        <?php else: ?>
                        <a class="nav-item nav-link d-lg-none" href="admin/login/login.php">Login</a>
                        <?php endif; ?>


                </div>
               
            </div>
        </nav>
    </div>
</body>
</html>