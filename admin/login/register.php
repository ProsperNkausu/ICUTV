<?php

include '../connection/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('User already exists.')</script>";
    } else {
        // Hash the password using MD5
        $hashed_password = md5($password);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (Fullname, Email, Password, date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script> alert('Account created successfully.');</script>";
            header("Location: login.php"); // Redirect to login page after successful registration
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
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
    <title>Register</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .gb{
            background-image: url('login.jpg');
            background-size: cover;
        }

        #card-bg{
             background-color: #020202a4;
        }
    </style>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content" class="gb">
            <main style="margin-top:70px;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-3 mt-5" id="card-bg">
                                <div class="card-header">
                                    <h3 class="text-center text-light font-weight-light my-4">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <form action="register.php" method="post" onsubmit="return validatePassword()">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputFullname" name="fullname" type="text" placeholder="Enter your full name" required />
                                                    <label for="inputFullname">Fullname</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control md-12" id="inputEmail" name="email" type="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3 mb-md-12">
                                                    <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Create a password" required />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputPasswordConfirm" name="confirm_password" type="password" placeholder="Confirm password" required />
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid"><button class="btn btn-primary btn-block" type="submit">Create Account</button></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        function validatePassword() {
            var password = document.getElementById("inputPassword").value;
            var confirmPassword = document.getElementById("inputPasswordConfirm").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>