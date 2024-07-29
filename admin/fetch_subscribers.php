<?php
require_once('connection/conn.php');

// Fetch all subscribers
$subscribers_query = "SELECT id, email, subscribed_at FROM newsletter_subscribers";
$subscribers_result = $conn->query($subscribers_query);

$subscribers = [];
if ($subscribers_result->num_rows > 0) {
    while ($row = $subscribers_result->fetch_assoc()) {
        $subscribers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($subscribers);
?>
