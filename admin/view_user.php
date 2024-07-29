<?php
session_start();

include 'connection/conn.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}

// Get user ID from query parameter (assuming user ID is passed as a query parameter)
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    // Prepare and execute the query to get user details
    $stmt = $conn->prepare("SELECT user_id, Fullname, Email, date, Password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        // User not found
        echo "<script>alert('User not found.'); window.location.href='users.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid user ID.'); window.location.href='users.php';</script>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>View User - ICUTV Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php include_once 'nav.php'; ?>
    <div id="layoutSidenav">
        <?php include 'header.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">View User</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">View User</li>
                    </ol>
                    <div class="card mb-6">
                        <div class="card-header">
                            <i class="fas fa-user me-1"></i>
                            User Details
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>User ID</th>
                                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo htmlspecialchars($user['Fullname']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td><?php echo htmlspecialchars($user['date']); ?></td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                    <div class="mt-4 mb-0">
                        <a href="users.php" class="btn btn-primary">Back to Users</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
