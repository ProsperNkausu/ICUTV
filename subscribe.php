<?php
include_once "admin/connection/conn.php";
// Debugging information
$debug_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    // Check if email already exists
    $sql = "SELECT * FROM newsletter_subscribers WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $message = "You are already subscribed.";
    } else {
        // Insert the new subscriber
        $sql = "INSERT INTO newsletter_subscribers (email) VALUES ('$email')";
        if ($conn->query($sql) === TRUE) {
            $message = "Thank you for subscribing!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    $message = "Invalid request.";
    $debug_message = 'Request Method: ' . $_SERVER['REQUEST_METHOD'] . ', POST Data: ' . print_r($_POST, true);
}

$conn->close();
header('Location: index.php?message=' . urlencode($message) . '&debug=' . urlencode($debug_message));
exit();
?>
