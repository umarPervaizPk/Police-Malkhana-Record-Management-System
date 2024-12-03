<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a super_admin
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

// Handle password change
if (isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new password and confirm password match
    if ($new_password === $confirm_password) {
        // Validate new password (you can add more complex rules here)
        if (strlen($new_password) >= 8) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
            if ($conn->query($update_sql)) {
                echo "<p>پاسورڈ کامیابی سے تبدیل ہو گیا ہے!</p>";
            } else {
                echo "<p>پاسورڈ تبدیل کرنے میں مسئلہ آیا ہے۔ دوبارہ کوشش کریں۔</p>";
            }
        } else {
            echo "<p>نیا پاسورڈ کم از کم 8 حروف پر مشتمل ہونا چاہیے۔</p>";
        }
    } else {
        echo "<p>نیا پاسورڈ اور تصدیق شدہ پاسورڈ میل نہیں کھاتے۔</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پاسورڈ تبدیل کریں</title>
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

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        form label {
            font-size: 1.1rem;
            display: block;
            margin: 5px 0;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        form button {
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
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

<header>پاسورڈ تبدیل کریں</header>

<main>
    <form method="POST" action="change_password.php">
        <label for="new_password">نیا پاسورڈ</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">نیا پاسورڈ دوبارہ ڈالیں</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" name="change_password">پاسورڈ تبدیل کریں</button>
    </form>
</main>
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
