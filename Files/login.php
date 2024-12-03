<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='error-msg'>Invalid credentials.</div>";
        }
    } else {
        echo "<div class='error-msg'>No user found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لاگ ان</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
        /* Define the custom font */
        @font-face {
            font-family: 'JameelNooriNastaleeq';
            src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'JameelNooriNastaleeq', sans-serif;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #003366;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #00509e;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #003366;
            color: white;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'JameelNooriNastaleeq', sans-serif; /* Apply Jameel Noori to the button */
        }

        button[type="submit"]:hover {
            background-color: #00224d;
            transform: translateY(-2px);
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-top: 10px;
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
    </style>
</head>

<body>

    <div class="login-container">
    <header class="header">
<img src="img/logo.png" alt="Punjab Police Banner" class="banner-image">
</header>
        <h2>پنجاب پولیس سسٹم میں لاگ ان کریں</h2>
        <form method="POST">
            <div class="input-group">
                <label for="username">مال خانہ</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">پاسورڈ</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">لاگ ان کریں</button>
        </form>
        <div class="error-msg">
            <?php if (isset($error)) { echo $error; } ?>
        </div>
        <div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
    </div>

</body>
</html>
