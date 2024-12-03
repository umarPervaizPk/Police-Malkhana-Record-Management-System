<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the record to edit
$record = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM records WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the record
    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    }

    // Close the statement
    $stmt->close();
}

// Update the record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Sanitize and handle empty fields by assigning them empty values if not provided
    $year = !empty($_POST['year']) ? $_POST['year'] : '';
    $thana = !empty($_POST['thana']) ? $_POST['thana'] : '';
    $crime_type = !empty($_POST['crime_type']) ? $_POST['crime_type'] : '';
    $case_number = !empty($_POST['case_number']) ? $_POST['case_number'] : '';
    $date = !empty($_POST['date']) ? $_POST['date'] : '';
    $property_type = !empty($_POST['property_type']) ? $_POST['property_type'] : '';
    $accused_name = !empty($_POST['accused_name']) ? $_POST['accused_name'] : '';
    $store_id = !empty($_POST['store_id']) ? $_POST['store_id'] : '';
    $decision_date = !empty($_POST['decision_date']) ? $_POST['decision_date'] : '';
    $court_order = !empty($_POST['court_order']) ? $_POST['court_order'] : '';
    $judge = !empty($_POST['judge']) ? $_POST['judge'] : '';
    $status = !empty($_POST['status']) ? $_POST['status'] : '';

    // Handle file upload for property image (if the user provides a new image)
    $property_image = '';
    if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] == 0) {
        $property_image = 'uploads/' . uniqid() . '.' . pathinfo($_FILES['property_image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['property_image']['tmp_name'], $property_image);
    } else {
        $property_image = $record['property_image']; // retain the old image if not updated
    }

    // Update the record in the database
    $stmt = $conn->prepare("UPDATE records SET 
        year = ?, thana = ?, crime_type = ?, case_number = ?, date = ?, property_type = ?, 
        accused_name = ?, store_id = ?, decision_date = ?, court_order = ?, judge = ?, status = ?, 
        property_image = ? WHERE id = ? AND user_id = ?");
    
    $stmt->bind_param("ssssssssssssssi", $year, $thana, $crime_type, $case_number, $date, $property_type, 
                     $accused_name, $store_id, $decision_date, $court_order, $judge, $status, $property_image, $id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        // Redirect to the same page to avoid resubmission on refresh
        header("Location: edit_record.php?id=" . $id);
        exit();
    } else {
        echo "<div style='color: red; text-align: center;'>ریکارڈ اپ ڈیٹ کرنے میں خرابی: " . $conn->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ریکارڈ ترمیم کریں</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Jameel Noori', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #003366;
        }
        form {
            margin: 20px auto;
            width: 60%;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            font-size: 1.2rem;
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="date"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #003366;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }
        button:hover {
            background-color: #00509e;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }
        button:active {
            transform: scale(1);
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

<h2>ریکارڈ ترمیم کریں</h2>

<!-- Edit Form in Urdu -->
<form method="POST" action="edit_record.php?id=<?php echo $record['id']; ?>" enctype="multipart/form-data">
    <label for="case_number">مقدمہ نمبر:</label>
    <input type="text" name="case_number" value="<?php echo $record['case_number']; ?>">

    <label for="year">سال:</label>
    <input type="text" name="year" value="<?php echo $record['year']; ?>">

    <label for="thana">تھانہ:</label>
    <input type="text" name="thana" value="<?php echo $record['thana']; ?>">

    <label for="crime_type">بجرم:</label>
    <input type="text" name="crime_type" value="<?php echo $record['crime_type']; ?>">

    <label for="date">تاریخ:</label>
    <input type="date" name="date" value="<?php echo $record['date']; ?>">

    <label for="property_type">قسم مال مقدمہ:</label>
    <input type="text" name="property_type" value="<?php echo $record['property_type']; ?>">

    <label for="accused_name">نام ملزم:</label>
    <input type="text" name="accused_name" value="<?php echo $record['accused_name']; ?>">

    <label for="store_id">مد نمبر مالخانہ:</label>
    <input type="text" name="store_id" value="<?php echo $record['store_id']; ?>">

    <label for="decision_date">تاریخ فیصلہ:</label>
    <input type="date" name="decision_date" value="<?php echo $record['decision_date']; ?>">

    <label for="court_order">حکم عدالت:</label>
    <input type="text" name="court_order" value="<?php echo $record['court_order']; ?>">

    <label for="judge">عدالت/جج صاحب:</label>
    <input type="text" name="judge" value="<?php echo $record['judge']; ?>">

    <label for="status">کیفیت:</label>
    <input type="text" name="status" value="<?php echo $record['status']; ?>">

    <label for="property_image">تصویر مال مقدمہ:</label>
    <input type="file" name="property_image">
    
    <button type="submit" name="update">اپ ڈیٹ کریں</button>
</form>
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
