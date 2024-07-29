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
    $n_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM news_post WHERE n_id = ?");
    $stmt->bind_param("i", $n_id);
    $stmt->execute();
    header("Location: edit_news.php");
    exit;
}

// Fetch categories for the dropdown
$categories = [];
$category_result = $conn->query("SELECT c_id, category_name FROM category");
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch news posts from the database
$sql = "SELECT n_id, news_image, newsimage2, newsimage3, news_title, category, date, news_article FROM news_post";
$result = $conn->query($sql);

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
    <title>Edit News Post/ICUTV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .button {
            text-decoration: none;
            background-color: red;
            padding: 8px;
            color: white;
            border-radius: 5px;
            border:none;
        }
        .button-d {
            text-decoration: none;
            background-color: rgb(0, 101, 196);
            padding: 8px;
            color: white;
            border-radius: 5px;
            border:none;
        }

        .button-d:hover{
            color:black;
        }


        /* Custom styles for responsive table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
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
                    <h1 class="mt-4">Edit News</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit News</li>
                    </ol>
                    <div class="card mb-6">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            News Post Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N_id</th>
                                            <th>News Image</th>
                                            <th>News Image 2</th>
                                            <th>News Image 3</th>
                                            <th>News Title</th>
                                            <th>Category</th>
                                            <th>Post Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $row["n_id"] ?></td>
                                                    <td><img src="./<?= $row["news_image"] ?>" style="max-width: 30%; max-height:5vh;" /></td>
                                                    <td><img src="./<?= $row["newsimage2"] ?>" style="max-width: 30%; max-height:5vh;" /></td>
                                                    <td><img src="./<?= $row["newsimage3"] ?>" style="max-width: 30%; max-height:5vh;" /></td>
                                                    <td><?= $row["news_title"] ?></td>
                                                    <td><?= $row["category"] ?></td>
                                                    <td><?= $row["date"] ?></td>
                                                    <td>    
                                                    <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#editNewsModal" data-id="<?= $row["n_id"] ?>" data-title="<?= $row["news_title"] ?>" data-image1="<?= $row["news_image"] ?>" data-image2="<?= $row["newsimage2"] ?>" data-image3="<?= $row["newsimage3"] ?>" data-category="<?= $row["category"] ?>" data-article="<?= $row["news_article"] ?>">Edit News</button>
                                                    <a href="?delete=<?php echo $row['n_id']; ?>" class="button-d ">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="8">No news posts found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit News Modal -->
    <div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNewsModalLabel">Edit News</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNewsForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="newsId" name="newsId">
                        <div class="form-group">
                            <label for="newsTitle">News Title</label>
                            <input type="text" class="form-control" id="newsTitle" name="newsTitle" placeholder="Enter news title" required>
                        </div>
                        <div class="form-group">
                            <label for="newsImage1">News Image</label><br>
                            <input class="form-control" type="file" id="newsImage1" name="newsImage1" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="newsImage2">Image 2</label><br>
                            <input class="form-control" type="file" id="newsImage2" name="newsImage2" required>
                        </div>
                        <div class="form-group">
                            <label for="newsImage3">Image 3</label><br>
                            <input class="form-control" type="file" id="newsImage3" name="newsImage3" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="newsCategory">News Category</label><br>
                            <select class="form-select form-select-md mb-9" id="newsCategory" name="newsCategory" required>
                                <option value="" disabled selected>Choose category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['c_id'] ?>"><?= $category['category_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="newsPost">News Post</label>
                            <textarea class="form-control" id="newsPost" name="newsPost" rows="10" placeholder="Start writing article..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editNewsModal = document.getElementById('editNewsModal');
            editNewsModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var title = button.getAttribute('data-title');
                var image1 = button.getAttribute('data-image1');
                var image2 = button.getAttribute('data-image2');
                var image3 = button.getAttribute('data-image3');
                var category = button.getAttribute('data-category');
                var article = button.getAttribute('data-article');

                var modalTitle = editNewsModal.querySelector('.modal-title');
                var newsIdInput = editNewsModal.querySelector('#newsId');
                var newsTitleInput = editNewsModal.querySelector('#newsTitle');
                var newsImage1Input = editNewsModal.querySelector('#newsImage1');
                var newsImage2Input = editNewsModal.querySelector('#newsImage2');
                var newsImage3Input = editNewsModal.querySelector('#newsImage3');
                var newsCategoryInput = editNewsModal.querySelector('#newsCategory');
                var newsPostTextarea = editNewsModal.querySelector('#newsPost');

                modalTitle.textContent = 'Edit News ' + title;
                newsIdInput.value = id;
                newsTitleInput.value = title;
                newsImage1Input.value = image1;
                newsImage2Input.value = image2;
                newsImage3Input.value = image3;
                newsCategoryInput.value = category;
                newsPostTextarea.value = article;
            });

            document.getElementById('editNewsForm').addEventListener('submit', function (event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('update_news.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert('News post updated successfully.');
                    location.reload();
                })
                .catch(error => {
                    alert('An error occurred: ' + error.message);
                });
            });
        });
    </script>
</body>
</html>
