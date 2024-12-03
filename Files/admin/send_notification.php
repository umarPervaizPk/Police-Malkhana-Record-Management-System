<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: admin_login.php");
    exit();
}

// Get users list for sending notifications
$sql = "SELECT id, username FROM users";
$users = $conn->query($sql);

// Handle notification submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_notification'])) {
    $user_id = $_POST['user_id']; // 0 for all users, else specific user ID
    $message = $_POST['message'];
    $attachment = null;

    // Handle file upload if any
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to save the uploaded image
        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["attachment"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
                $attachment = $target_file; // Save the image path in the database
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error_message = "File is not an image.";
        }
    }

    if (!isset($error_message)) {
        if ($user_id == 0) {
            // Send to all users
            $sql = "INSERT INTO notifications (user_id, message, attachment) 
                    SELECT id, '$message', '$attachment' FROM users";
        } else {
            // Send to a specific user
            $sql = "INSERT INTO notifications (user_id, message, attachment) 
                    VALUES ('$user_id', '$message', '$attachment')";
        }

        if ($conn->query($sql) === TRUE) {
            $success_message = "Notification sent successfully!";
        } else {
            $error_message = "Error sending notification: " . $conn->error;
        }
    }
}

// Fetch old notifications
$notifications_sql = "SELECT notifications.id, users.username, notifications.message, notifications.created_at, notifications.attachment, notifications.seen 
                      FROM notifications
                      LEFT JOIN users ON notifications.user_id = users.id
                      ORDER BY notifications.created_at DESC";
$notifications = $conn->query($notifications_sql);
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نوٹیفیکیشن بھیجیں</title>
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
        .notification-form {
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    font-family: 'Jameel Noori Nastaleeq', sans-serif; /* Apply Jameel Noori font */
}

.notification-form select, .notification-form textarea, .notification-form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-family: 'Jameel Noori Nastaleeq', sans-serif; /* Apply Jameel Noori font */
}

        .notification-form button {
            padding: 10px 20px;
           
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .notification-form button:hover {
            background-color: #00509e;
        }
        .notification-form .success-message {
            color: green;
            margin-bottom: 15px;
        }
        .notification-form .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .notification-list table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .notification-list th, .notification-list td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .notification-list th {
            background-color: #003366;
            color: white;
        }
        .notification-list tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .notification-list .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .notification-list .delete-btn:hover {
            background-color: #d9534f;
        }
        .developer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #555;
            text-align: center;
            font-family: 'JameelNooriNastaleeq', sans-serif;
        }

        .developer a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }

        .developer i {
            color: red;
        }

        .developer a:hover {
            text-decoration: underline;
        }
        .footer-container {
    background-color: #003366;
    padding: 20px 0;
    margin-top: 50px; /* Space above footer */
}

.footer-container .link-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-container .footer-link {
    color: #ffffff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    padding: 5px 10px;
    transition: all 0.3s ease;
}

.footer-container .footer-link:hover {
    color: #ffd700;  /* Gold color for hover */
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
.navbar {
    background-color: #00224d;
    padding: 10px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.menu {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu li {
    margin: 0 15px;
}

.menu-link {
    font-family: 'Jameel Noori', sans-serif;
    font-size: 1.2rem;
    color: #ffffff;
    text-decoration: none;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.menu-link:hover {
    background-color: #ffd700; /* Gold color for hover */
    color: #00224d; /* Text color changes on hover */
    border-radius: 5px;
}
.banner {
    width: 100px;  /* Set the width to 640px */
    height: 100px; /* Set the height to 640px */
    margin: 0 auto; /* Center the banner horizontally */
    overflow: hidden; /* Prevent overflow */
}

.banner-image {
    width: 100px;  /* Ensure the image stretches to the width of the container */
    height: 100px; /* Ensure the image stretches to the height of the container */
    object-fit: cover; /* Ensure the image covers the entire area without distortion */
}
    </style>
</head>
<body>

<header class="header">
    <!-- Punjab Police Banner -->
    <img src="img/logo.png" alt="Punjab Police Banner" class="banner-image">
    <!-- Navigation Menu -->
    <nav class="navbar">
        <ul class="menu">

        <li> <a href="index.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">ہوم  </a> </li>
        <li>   <a href="view_notifications.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">  نوٹیفیکیشن دیکھیں</a></li>
        <li>   <a href="logout.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">لاگ آؤٹ</a></li>
     
     
        </ul>
    </nav>







</header>

<main>
    <section class="notification-form">
        <h2>نوٹیفیکیشن بھیجیں</h2>

        <!-- Display success or error messages -->
        <?php if (isset($success_message)) { ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php } elseif (isset($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="user_id">یوزر منتخب کریں:</label>
            <select name="user_id" id="user_id">
                <option value="0">تمام یوزرز</option>
                <?php while ($user = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                <?php } ?>
            </select>

            <label for="message">پیغام:</label>
            <textarea name="message" id="message" rows="4" required></textarea>

            <label for="attachment">تصویر اپلوڈ کریں (اختیاری):</label>
            <input type="file" name="attachment" id="attachment">

            <button type="submit" name="send_notification">نوٹیفیکیشن بھیجیں</button>
        </form>
    </section>

    <section class="notification-list">
        <h2>پچھلے نوٹیفیکیشنز</h2>

        <table>
            <thead>
                <tr>
                    <th>یوزر</th>
                    <th>پیغام</th>
                    <th>تاریخ</th>
                    <th>تصویر</th>
                    <th>دیکھا گیا</th>
                    <th>حذف کریں</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($notification = $notifications->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $notification['username'] ?: "تمام یوزرز"; ?></td>
                        <td><?php echo $notification['message']; ?></td>
                        <td><?php echo $notification['created_at']; ?></td>
                        <td>
                            <?php if ($notification['attachment']) { ?>
                                <a href="<?php echo $notification['attachment']; ?>" target="_blank">دیکھیں</a>
                            <?php } else { echo "نہیں"; } ?>
                        </td>
                        <td><?php echo $notification['seen'] ? "ہاں" : "نہیں"; ?></td>
                        <td>
                            <a href="delete_notification.php?id=<?php echo $notification['id']; ?>" class="delete-btn">حذف کریں</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</main>


<div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</body>
</html>
