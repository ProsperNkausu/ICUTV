<?php
session_start();
include 'connection/conn.php'; 

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login/login.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $cc_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE cc_id = ?");
    $stmt->bind_param("i", $cc_id);
    $stmt->execute();
    header("Location: comments.php");
    exit;
}

// Handle reply request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply']) && isset($_POST['comment_id'])) {
    $reply = $_POST['reply'];
    $comment_id = $_POST['comment_id'];
    $admin_reply = "Admin: " . $reply;
    $stmt = $conn->prepare("INSERT INTO comments (user, news_title, n_id, comment, date) SELECT user, news_title, n_id, ?, NOW() FROM comments WHERE cc_id = ?");
    $stmt->bind_param("si", $admin_reply, $comment_id);
    $stmt->execute();
    header("Location: comments.php");
    exit;
}

// Fetch comments from database
$comments = [];
$result = $conn->query("SELECT * FROM comments");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
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
    <title>Comments/ICUTV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
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
                    <h1 class="mt-4">Comments</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Comments</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Comments Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>User name</th>
                                        <th>Comment</th>
                                        <th>Post</th>
                                        <th>Post ID</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comments as $comment) : ?>
                                        <tr>
                                            <td><?php echo $comment['user']; ?></td>
                                            <td><?php echo $comment['comment']; ?></td>
                                            <td><?php echo $comment['news_title']; ?></td>
                                            <td><?php echo $comment['n_id']; ?></td>
                                            <td><?php echo $comment['date']; ?></td>
                                            <td>
                                                <a href="?delete=<?php echo $comment['cc_id']; ?>" class="button-d">Delete comment</a>
                                                <button class="btn btn-primary btn-reply" data-comment-id="<?php echo $comment['cc_id']; ?>">Reply</button>
                                            </td>
                                        </tr>
                                        <tr class="reply-section" id="reply-<?php echo $comment['cc_id']; ?>" style="display: none;">
                                            <td colspan="6">
                                                <form method="POST">
                                                    <div class="form-group">
                                                        <label for="reply">Reply</label>
                                                        <textarea class="form-control" name="reply" rows="3" required></textarea>
                                                        <input type="hidden" name="comment_id" value="<?php echo $comment['cc_id']; ?>">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
    <script>
        document.querySelectorAll('.btn-reply').forEach(button => {
            button.addEventListener('click', () => {
                const commentId = button.getAttribute('data-comment-id');
                document.getElementById(`reply-${commentId}`).style.display = 'table-row';
            });
        });
    </script>
</body>

</html>