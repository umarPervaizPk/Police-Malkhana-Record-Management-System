<?php
session_start();
include 'config.php';

// Check if user is super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    // Delete notification
    $sql = "DELETE FROM notifications WHERE id = '$notification_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: send_notification.php?success=Notification deleted successfully");
    } else {
        header("Location: send_notification.php?error=Error deleting notification");
    }
}
?>
