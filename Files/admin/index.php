<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: admin_login.php");
    exit();
}

// Get total users
$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = $conn->query($sql);
$total_users = $result->fetch_assoc()['total_users'];

// Get users and their records count
$sql = "SELECT users.id, users.username, COUNT(records.id) AS total_records 
        FROM users LEFT JOIN records ON users.id = records.user_id 
        GROUP BY users.id";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایڈمن پینل</title>
    <link href="https://fonts.googleapis.com/css2?family=Jameel+Noori+Nastaleeq&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
         @font-face {
    font-family: 'Jameel Noori';
    src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
}
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

        .total-users {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .total-users p {
            font-size: 18px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
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

        .action-links a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 15px;
            background-color: #003366;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .action-links a:hover {
            background-color: #00509e;
            transform: scale(1.05);
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

        .logout-btn {
            padding: 10px 20px;
            background-color: #ff6600;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .logout-btn:hover {
            background-color: #e65c00;
            transform: scale(1.05);
            
        }

        .notification-btn {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 15px;
            background-color: #003366;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-btn:hover {
            background-color: #00509e;
            transform: scale(1.05);
            color: white;
        }

        .notification-list-btn {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 15px;
            background-color: #003366;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-list-btn:hover {
            background-color: #00509e;
            transform: scale(1.05);
            color: white;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 900px;
            margin: 0 auto;
        }
        .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 180px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
       
        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .footer-container {
    background-color: #003366;
    padding: 20px 0;
    margin-top: 50px; /* Space above footer */
}


/* Navbar Styling */

/* Responsive Design */
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


    </style>
</head>
<header>ایڈمن ایریا </header>
<body>



<main>
    <section class="total-users">
        <p>مجموعی یوزرز کی تعداد: <?php echo $total_users; ?></p>
    </section>

    <h2>ڈیش بورڈ</h2>
    <div class="button-container">
    <!-- Notification Action Buttons -->
    <a href="send_notification.php" class="notification-btn">نوٹیفیکیشن بھیجیں</a>
    <a href="view_notifications.php" class="notification-list-btn">پچھلے نوٹیفیکیشنز دیکھیں</a>
    <a href="logout.php" class="notification-list-btn">لاگ آؤٹ</a>
    <a href="admin_reset_password.php" class="notification-list-btn">پاسورڈ تبدیل کریں</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>سیریل نمبر</th>
                <th>مال خانہ</th>
                <th>کل مقدمات</th>
                <th>عمل</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['total_records']; ?></td>
                    <td class="action-links">
                        <a href="view_user.php?id=<?php echo $user['id']; ?>">دیکھیں</a> | 
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>">حذف کریں</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<div class="footer-container">
    <div class="link-container">
    
    </div>
</div>

</body>
<div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</html>
