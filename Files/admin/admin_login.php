<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'super_admin';
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='error-message'>غلط معلومات</div>";
        }
    } else {
        echo "<div class='error-message'>کوئی صارف نہیں ملا</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایڈمن لاگ ان</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
         @font-face {
    font-family: 'Jameel Noori';
    src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
}

/* General page styles */
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

/* Centered container for the login form */
.login-container {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

/* Title style */
h1 {
    text-align: center;
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Input group style */
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

/* Submit button style */
.login-btn {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.login-btn:hover {
    background-color: #0056b3;
}

/* Error message style */
.error-message {
    color: #ff0000;
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
}

/* Importing the font */
@font-face {
    font-family: 'Jameel Noori';
    src: url('fonts/JameelNoori.ttf') format('truetype');
}

/* Responsive design */
@media (max-width: 480px) {
    .login-container {
        padding: 20px;
    }

    h1 {
        font-size: 20px;
    }

    .login-btn {
        font-size: 14px;
    }
}
.header{
            width: 100px;  /* Set the width to 640px */
    height: 100px; /* Set the height to 640px */
    margin: 0 auto; /* Center the banner horizontally */
    overflow: hidden; /* Prevent overflow */
        } .banner {
    
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


</style>
</head>
<body>
    <div class="login-container">
    <header class="header">
<img src="img/logo.png" alt="Punjab Police Banner" class="banner-image">
</header>
        <h1>ایڈمن لاگ ان</h1>
        <form method="POST" class="login-form">
            <div class="input-group">
                <label for="username">مال خانہ</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">پاسورڈ</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">لاگ ان</button>
        </form>
        <div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
    </div>
</body>
</html>
