 <?php
    $servername = "localhost";
    $username = "root"; 
    $password_db = ""; 
    $dbname = "icutv";

    // Creates connection to the database 
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection is successful 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
