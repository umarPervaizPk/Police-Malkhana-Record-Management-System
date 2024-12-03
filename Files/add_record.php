<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $year = $_POST['year'];
    $thana = $_POST['thana'];
    $case_number = $_POST['case_number'];
    $date = $_POST['date'];
    $crime_type = $_POST['crime_type'];
    $property_type = $_POST['property_type'];
    $accused_name = $_POST['accused_name'];
    $store_id = $_POST['store_id'];
    $decision_date = $_POST['decision_date'];
    $court_order = $_POST['court_order'];
    $judge = $_POST['judge'];
    $status = $_POST['status'];

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['propertyImage']) && $_FILES['propertyImage']['error'] == 0) {
        $image_name = $_FILES['propertyImage']['name'];
        $image_tmp_name = $_FILES['propertyImage']['tmp_name'];
        $image_size = $_FILES['propertyImage']['size'];
        $image_type = $_FILES['propertyImage']['type'];
        
        // Check if the file is an image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_extension, $allowed_extensions)) {
            $image_new_name = uniqid() . '.' . $image_extension; // Generate a unique file name
            $image_dir = 'uploads/'; // Make sure this directory exists and is writable
            $image_path = $image_dir . $image_new_name;

            // Move the uploaded image to the server
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                echo "<div style='color: green; text-align: center; font-size: 1.2rem;'>تصویر اپ لوڈ ہوگئی!</div>";
            } else {
                echo "<div style='color: red; text-align: center; font-size: 1.2rem;'>تصویر اپ لوڈ کرنے میں خرابی!</div>";
            }
        } else {
            echo "<div style='color: red; text-align: center; font-size: 1.2rem;'>صرف امیج فائلز ہی اپ لوڈ کی جا سکتی ہیں۔</div>";
        }
    }

    // Insert the record into the database
    $sql = "INSERT INTO records (user_id, year, thana, case_number, date, crime_type, property_type, accused_name, store_id, decision_date, court_order, judge, status, property_image)
            VALUES ('$user_id', '$year', '$thana', '$case_number', '$date', '$crime_type', '$property_type', '$accused_name', '$store_id', '$decision_date', '$court_order',  '$judge', '$status', '$image_path')";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='color: green; text-align: center; font-size: 1.2rem;'>ریکارڈ کامیابی کے ساتھ شامل کر دیا گیا!</div>";
    } else {
        echo "<div style='color: red; text-align: center; font-size: 1.2rem;'>ریکارڈ شامل کرنے میں خرابی: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
    <!-- Header with Punjab Police Banner and Navigation Menu -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ریکارڈ شامل کریں</title>
    <link rel="stylesheet" href="style2.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <style>.footer {
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
        /* Footer Container */
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
/* General Header Styling */
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

/* Navbar Styling */
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
<header class="header">
    <!-- Punjab Police Banner -->
    <div class="banner">
        <img src="img/pp.png" alt="Punjab Police Banner" class="banner-image">
    </div>
    <!-- Navigation Menu -->
    <nav class="navbar">
        <ul class="menu">
            <li><a href="index.php" class="menu-link">ہوم</a></li>
            <li><a href="add_record.php" class="menu-link">ریکارڈ شامل کریں</a></li>
            <li><a href="search_case.php" class="menu-link">ریکارڈ تلاش کریں</a></li>
            <li><a href="print_final.php" class="menu-link">فیصلہ شدہ ریکارڈ</a></li>
            <li><a href="print_pending.php" class="menu-link">غیر فیصلہ شدہ ریکارڈ</a></li>
            <li><a href="update_record.php" class="menu-link">ریکارڈ ترمیم</a></li>
            <li><a href="delete_record.php" class="menu-link">ریکارڈ حذف</a></li>
            <li><a href="logout.php" class="menu-link">لاگ آؤٹ</a></li>
        </ul>
    </nav>
</header>

<body>

<div class="container">
    <h2>ریکارڈ شامل کریں</h2>

    <form method="POST" action="add_record.php" enctype="multipart/form-data">

        <div class="row">
            <div>
                <label for="year">سال</label>
                <input type="text" id="year" name="year" placeholder="مثال: 2023">
            </div>
            <div>
                <label for="thana">تھانہ</label>
                <input type="text" id="thana" name="thana" placeholder="مثال: سٹی">
            </div>
        </div>
        
        <div class="row">
            <div>
                <label for="case_number">مقدمہ نمبر</label>
                <input type="text" id="case_number" name="case_number">
            </div>
            <div>
                <label for="date">تاریخ</label>
                <input type="date" id="date" name="date">
            </div>
        </div>
        
        <div class="row">
            <div>
                <label for="crime_type">بجرم</label>
                <input type="text" id="crime_type" name="crime_type" placeholder="مثال: چوری">
            </div>
            <div>
                <label for="property_type">قسم مال مقدمہ</label>
                <textarea id="property_type" name="property_type" rows="4" cols="50" placeholder="مال مقدمہ کی تفصیل لکھیں۔۔۔"></textarea>
            </div>
        </div>
        
        <div class="row">
            <div>
                <label for="accused_name">نام ملزم</label>
                <input type="text" id="accused_name" name="accused_name">
            </div>
            <div>
                <label for="store_id">مد نمبر مالخانہ</label>
                <input type="text" id="store_id" name="store_id">
            </div>
        </div>
        
        <div class="row">
            <div>
                <label for="decision_date">تاریخ فیصلہ</label>
                <input type="date" id="decision_date" name="decision_date">
            </div>
            <div>
                <label for="court_order">حکم عدالت</label>
                <input type="text" id="court_order" name="court_order">
            </div>
        </div>
        
        <div class="row">
            
            <div>
                <label for="judge">عدالت/جج صاحب</label>
                <input type="text" id="judge" name="judge">
            </div>
        </div>
        
        <div class="row">
            <div>
                <label for="status">کیفیت</label>
                <input type="text" id="status" name="status">
            </div>
            <div>
                <label for="propertyImage">تصویر مال مقدمہ</label>
                <input type="file" id="propertyImage" name="propertyImage" accept="image/*">
            </div>
        </div>
        
        <button type="submit">ریکارڈ شامل کریں</button>
    </form>
</div>
<!-- Footer Container with Buttons -->
<!-- Footer Container with Links -->
<div class="footer-container">
    <div class="link-container">
    <a href="index.php" class="footer-link">ہوم  </a>
        <a href="add_record.php" class="footer-link">ریکارڈ شامل کریں</a>
        <a href="search_case.php" class="footer-link">ریکارڈ تلاش کریں</a>
        <a href="print_final.php" class="footer-link">فیصلہ شدہ ریکارڈ پرنٹ کریں</a>
        <a href="print_pending.php" class="footer-link">غیر فیصلہ شدہ ریکارڈ پرنٹ کریں</a>
        <a href="update_record.php" class="footer-link">ریکارڈ ترمیم کریں</a>
        <a href="delete_record.php" class="footer-link">ریکارڈ حذف کریں</a>
        <a href="logout.php" class="footer-link">لاگ آؤٹ</a>
        
    </div>
</div>



<div class="footer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>

</body>
</html>
