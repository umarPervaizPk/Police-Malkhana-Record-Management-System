<?php
session_start();
include 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if notification_id is set in POST
if (isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];

    // Update the 'seen' column in the notifications table
    $query = "UPDATE notifications SET seen = 1 WHERE id = '$notification_id'";
    if ($conn->query($query)) {
        echo "Notification marked as read.";
    } else {
        echo "Error marking notification as read.";
    }
}
?>
