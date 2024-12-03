<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: admin_login.php");
    exit();
}

// Fetch all notifications
$sql = "SELECT notifications.id, users.username, notifications.message, notifications.created_at, notifications.seen 
        FROM notifications
        LEFT JOIN users ON notifications.user_id = users.id
        ORDER BY notifications.created_at DESC";
$notifications = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نوٹیفیکیشنز دیکھیں</title>
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
        .header h1{
            color: white;  
        }
        main {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #003366;
            color: white;
        }
        td {
            background-color: #ffffff;
            border-bottom: 1px solid #f2f2f2;
        }
        tr:hover td {
            background-color: #f9f9f9;
        }
        .logout-btn {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 15px;
            background-color: #003366;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .logout-btn:hover {
            background-color: #00509e;
            transform: scale(1.05);
            color: white;
        }

        .navbar {
    background-color: #00224d;
    font-family: 'Jameel Noori Nastaleeq', sans-serif;
    direction: rtl;
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
    font-family: 'Jameel Noori Nastaleeq', sans-serif;
            direction: rtl;
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
    font-family: 'Jameel Noori Nastaleeq', sans-serif;
    direction: rtl;
    padding: 10px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.menu {
    display: flex;
    font-family: 'Jameel Noori Nastaleeq', sans-serif;
    direction: rtl;
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



/* Responsive Design */
@media (max-width: 768px) {
    .banner {
        height: 300px; /* Reduce banner height on smaller screens */
    }

    .menu {
        flex-direction: column;
    }

    .menu li {
        margin: 10px 0;
    }
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
       <li> <a href="send_notification.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">نوٹیفیکیشن بھیجیں</a></li>
       <li>   <a href="logout.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">لاگ آؤٹ</a></li>
        </ul>
    </nav>







</header>


<main>
   
    
    <table>
        <thead>
            <tr>
                <th>یوزر</th>
                <th>پیغام</th>
                <th>تاریخ</th>
                <th>دیکھا گیا</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($notification = $notifications->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $notification['username'] ?: "تمام یوزرز"; ?></td>
                    <td><?php echo $notification['message']; ?></td>
                    <td><?php echo $notification['created_at']; ?></td>
                    <td><?php echo $notification['seen'] ? "ہاں" : "نہیں"; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


</main>

</body>
<div class="footer-container">
    <div class="link-container">
    
    </div>
</div>

<div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</html>
