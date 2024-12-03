<?php
session_start();
include 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch all notifications for the user
$query = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY date_sent DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نوٹیفیکیشن</title>
    <link href="https://fonts.googleapis.com/css2?family=Jameel+Noori+Nastaleeq&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', sans-serif;
            direction: rtl;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        main {
            margin: 20px;
        }

        .notification {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .notification img {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
        }

        .mark-read-btn {
            background-color: #003366;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .mark-read-btn:hover {
            background-color: #00509e;
        }

        footer {
            text-align: center;
            background-color: #003366;
            color: white;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .view-notification-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
        }

        .view-notification-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .view-notification-btn:active {
            background-color: #004085;
        }
        footer {
            text-align: center;
            background-color: #003366;
            color: white;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }


        .footer-container {
    background-color: #003366;
    padding: 20px 0;
    color: white;
    margin-top: 50px; /* Space above footer */
}

.footer-container .link-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    color: white;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-container .footer-link {
    color: white;
    text-decoration: none;
    font-size: 1rem;

    font-weight: 500;
    padding: 5px 10px;
    transition: all 0.3s ease;
}

.footer-container .footer-link:hover {
    color: white;  /* Gold color for hover */
    text-decoration: underline;
}

.footer-container .footer-link:focus {
    outline: none;
}

.footer-container .footer-link:not(:last-child) {
    border-right: 1px solid #ddd;
    padding-right: 15px;
    margin-right: 15px;
}
    </style>
</head>
<body>

<header>
    نوٹیفیکیشنز
</header>

<main>
    <h2>آپ کی موصول شدہ نوٹیفیکیشنز</h2>

    <?php while ($notification = $result->fetch_assoc()) { 
        $notification_message = $notification['message'];
        $image_url = $notification['attachment'];
        $is_read = $notification['seen'];
        $notification_id = $notification['id'];
    ?>
        <div class="notification">
            <p><?php echo htmlspecialchars($notification_message); ?></p>
            <?php if ($image_url) { ?>
                <div>
                    <!-- Image for viewing -->
                    <img src="admin/<?php echo htmlspecialchars($image_url); ?>" alt="Notification Image" style="max-width: 100%; max-height: 200px; margin-top: 10px;">
                    
                    <div style="margin-top: 10px;">
                        <!-- Link to view image in full -->
                        <a href="admin/<?php echo htmlspecialchars($image_url); ?>" target="_blank" class="view-notification-btn">View Notification</a>
                    </div>
                </div>
            <?php } ?>

            <!-- If the notification is not read, show the mark as read button -->
            <?php if ($is_read == 0) { ?>
                <button class="mark-read-btn" onclick="markAsRead(<?php echo $notification_id; ?>)" id="mark-read-btn-<?php echo $notification_id; ?>">مارک کریں پڑھا ہوا</button>
            <?php } else { ?>
                <button class="mark-read-btn" disabled>پڑھا ہوا</button>
            <?php } ?>
        </div>
    <?php } ?>
</main>


<script>
    function markAsRead(notificationId) {
        // Send a request to mark the notification as read
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "mark_as_read.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the button text and disable it after the request is successful
                const button = document.getElementById('mark-read-btn-' + notificationId);
                if (button) {
                    button.innerText = "پڑھا ہوا";
                    button.disabled = true;
                }
            }
        };
        xhr.send("notification_id=" + notificationId);
    }
</script>


<div class="footer-container">
    <div class="link-container">
    <a href="index.php" class="footer-link">ہوم  </a>
     
        <a href="view_notifications.php" class="footer-link">  نوٹیفیکیشن دیکھیں</a>
    
        <a href="logout.php" class="footer-link">لاگ آؤٹ</a>
       
    </div>
</div>


</body>
<div class="footer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</html>
