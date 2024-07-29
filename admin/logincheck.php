<?php
session_start();

require_once('connection/conn.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>