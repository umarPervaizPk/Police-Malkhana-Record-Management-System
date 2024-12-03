<?php
session_start();
include 'config.php';

// Check if the user is logged in as a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate new password and confirm password match
    if ($new_password === $confirm_password) {
        // Validate new password (at least 8 characters)
        if (strlen($new_password) >= 8) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $sql = "UPDATE admins SET password = '$hashed_password' WHERE id = $user_id";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>پاسورڈ کامیابی سے تبدیل ہو گیا ہے!</p>";
            } else {
                echo "<p style='color: red;'>پاسورڈ تبدیل کرنے میں مسئلہ آیا ہے۔ دوبارہ کوشش کریں۔</p>";
            }
        } else {
            echo "<p style='color: red;'>نیا پاسورڈ کم از کم 8 حروف پر مشتمل ہونا چاہیے۔</p>";
        }
    } else {
        echo "<p style='color: red;'>نیا پاسورڈ اور تصدیق شدہ پاسورڈ میل نہیں کھاتے۔</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پاسورڈ تبدیل کریں</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
        @font-face {
            font-family: 'Jameel Noori';
            src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
        }
        body {
            font-family: 'Jameel Noori', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            direction: rtl;
        }

        .reset-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .reset-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .reset-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .reset-container {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            .reset-btn {
                font-size: 14px;
            }
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
        .header{
            width: 100px;  /* Set the width to 640px */
    height: 100px; /* Set the height to 640px */
    margin: 0 auto; /* Center the banner horizontally */
    overflow: hidden; /* Prevent overflow */
        }
        .banner {
    
}

.banner-image {
    width: 100px;  /* Ensure the image stretches to the width of the container */
    height: 100px; /* Ensure the image stretches to the height of the container */
    object-fit: cover; /* Ensure the image covers the entire area without distortion */
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

    </style>
</head>

<body>

<div class="reset-container">
<header class="header">
<img src="img/logo.png" alt="Punjab Police Banner" class="banner-image">
</header>

    <h1>پاسورڈ تبدیل کریں</h1>
    <form method="POST" action="admin_reset_password.php">
        <div class="input-group">
            <label for="new_password">نیا پاسورڈ</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="input-group">
            <label for="confirm_password">نیا پاسورڈ دوبارہ ڈالیں</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" class="reset-btn">پاسورڈ تبدیل کریں</button>
    </form> <br>
    <header class="header2">
    <!-- Punjab Police Banner -->
   
    <!-- Navigation Menu -->
    <nav class="navbar">
        <ul class="menu">
       <li> <a href="index.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">ہوم  </a> </li>
       <li>   <a href="logout.php" class="menu-link" style="font-family: 'Jameel Noori Nastaleeq', sans-serif;">لاگ آؤٹ</a></li>
        </ul>
    </nav>
</header>
<div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</div>

</body>
<div class="footer-container">
    <div class="link-container">
    
    </div>
</div>



</html>
