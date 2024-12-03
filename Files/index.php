<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ریکارڈ مینجمنٹ سسٹم</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Jameel Noori', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f2f2f2);
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .footer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #555;
            text-align: center;
            font-family: 'JameelNooriNastaleeq', sans-serif;
        }

        .footer a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }

        .footer i {
            color: red;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        h2 {
            color: #002855; /* Dark blue for header */
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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
        .btn-add { background-color: #003366; } /* Dark Blue */
        .btn-view { background-color: #004d7a; } /* Slightly lighter dark blue */
        .btn-search { background-color: #2c3e50; } /* Dark Slate Gray */
        .btn-print { background-color: #283747; } /* Dark Blue Gray */
        .btn-edit { background-color: #1c2833; } /* Charcoal */
        .btn-delete { background-color: #8b0000; } /* Dark Red for delete */
        .btn-logout { background-color: #606060; } /* Dark Gray for logout */
        .btn-password { background-color: #003366; } 

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }



        .header {
    width: 100%;
    text-align: center;
    background-color: #003366; /* Dark Punjab Police color */
}

/* Banner Styling */
.banner {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.banner-image {
    width: 100%;
    height: auto;
    object-fit: cover;
}


        
    </style>
</head>
<header class="header">
    <!-- Punjab Police Banner -->
    <div class="banner">
        <img src="img/pp.png" alt="Punjab Police Banner" class="banner-image">
    </div>
    <!-- Navigation Menu -->
   
</header>

<body>

<h2>ریکارڈ مینجمنٹ سسٹم میں خوش آمدید</h2>

<div class="button-container">
    <a href="add_record.php" class="btn btn-add">ریکارڈ شامل کریں</a>
    <a href="search_case.php" class="btn btn-search">ریکارڈ تلاش کریں</a>
    <a href="print_final.php" class="btn btn-print">فیصلہ شدہ ریکارڈ پرنٹ کریں </a>
    <a href="print_pending.php" class="btn btn-print">غیر فیصلہ شدہ ریکارڈ پرنٹ کریں </a>
    <a href="update_record.php" class="btn btn-edit">ریکارڈ ترمیم کریں</a>
    <a href="delete_record.php" class="btn btn-delete">ریکارڈ حذف کریں</a>
    <a href="view_notifications.php" class="btn btn-search">  نوٹیفیکیشن دیکھیں</a>
    <a href="change_password.php" class="btn btn-add">پاسورڈ تبدیل </a>
   
    <a href="logout.php" class="btn btn-delete">لاگ آؤٹ</a>
</div>

<div class="footer">
    <p>پنجاب پولیس ڈیپارٹمنٹ کے زیر اہتمام</p>
</div>
<!-- Footer Container with Buttons -->

<div class="footer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a> 
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</body>
</html>
