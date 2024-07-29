<?php
session_start();

require_once '../connection/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['inputEmail'];
    $password = $_POST['inputPassword'];

    $email = mysqli_real_escape_string($conn, $email);
    $password = md5(mysqli_real_escape_string($conn, $password)); // Hash the password using MD5

    // Query to check admin credentials
    $sql_admin = "SELECT id, username, Email, password FROM admin WHERE Email = ? AND password = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ss", $email, $password);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows == 1) {
        // Admin login successful
        $_SESSION['admin_loggedin'] = true;
        $row = $result_admin->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];
        header("location: ../index.php");
        exit();
    }

    // Query to check user credentials
    $sql_user = "SELECT user_id, Fullname, Email, Password FROM users WHERE Email = ? AND Password = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ss", $email, $password);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows == 1) {
        // User login successful
        $_SESSION['user_loggedin'] = true;
        $row = $result_user->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_fullname'] = $row['Fullname'];
        header("location: ../../index.php");
        exit();
    }

    // If no matching credentials found
    $login_error = "Invalid email or password. Please try again.";
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
    <title>Login</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .gb {
            background-image: url('login.jpg');
            background-size: cover;
           
        }

        #card-bg {
            background-color: #020202a4;
        }

    @media (max-width: 780px) {
            .gb {
                background: url("login.jpg") no-repeat center center fixed;
                background-size: cover;
            }
        }

    </style>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content" class="gb">
            <main style="margin-top:150px;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5" id="card-bg">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4 text-light">Login</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            
                                            <button class="btn btn-primary" type="submit">Login</button>
                                        </div>

                                        <?php if (isset($login_error)) { ?>
                                            <div class="alert alert-danger mt-3" role="alert">
                                                <?php echo $login_error; ?>
                                            </div>
                                        <?php } ?>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
