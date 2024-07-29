<?php
session_start();
include 'connection/conn.php'; 

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}


// Delete user if delete request is received
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $delete_query = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $delete_query->bind_param("i", $user_id);
    $delete_query->execute();
    header("Location: users.php");
    exit;
}

// Fetch all users from the database
$users_query = "SELECT user_id, Fullname, Email, date FROM users";
$result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Users - ICUTV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .button {
            text-decoration: none;
            padding: 8px;
            color: white;
            border-radius: 5px;
        }

        .button:hover {
            color: lightblue;
        }

        .button-d {
            text-decoration: none;
            background-color: red;
            padding: 8px;
            color: white;
            border-radius: 5px;
        }

        .button-d:hover {
            color: lightgray;
        }
    </style>
</head>

<body class="sb-nav-fixed">
      <?php
    include_once 'nav.php';
    ?>
    <div id="layoutSidenav">

        <?php include 'header.php' ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                    <div class="card mb-6">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Users Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo $row['user_id']; ?></td>
                                            <td><?php echo $row['Fullname']; ?></td>
                                            <td><?php echo $row['Email']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td>
                                                <a href="view_user.php?user_id=<?php echo $row['user_id']; ?>" class="button bg-primary">View user</a>
                                                <a href="users.php?delete_user_id=<?php echo $row['user_id']; ?>" class="button-d" onclick="return confirm('Are you sure you want to delete this user?')">Delete user</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>