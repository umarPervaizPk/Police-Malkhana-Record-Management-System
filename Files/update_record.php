<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle search and fetch record by case number
$record = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $case_number = $_POST['case_number'];

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM records WHERE case_number = ? AND user_id = ?");
    $stmt->bind_param("si", $case_number, $_SESSION['user_id']);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    }

    // Close the statement
    $stmt->close();
}

// Handle deletion of record
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare the query to delete
    $stmt = $conn->prepare("DELETE FROM records WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    
    // Execute the delete query
    if ($stmt->execute()) {
        echo "<div style='color: green; text-align: center;'>ریکارڈ کامیابی سے حذف کر دیا گیا!</div>";
    } else {
        echo "<div style='color: red; text-align: center;'>ریکارڈ حذف کرنے میں خرابی: " . $conn->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ریکارڈ میں ترمیم کریں</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: right;
            font-size: 1rem;
        }
        th {
            background-color: #003366;
            color: white;
        }
        td img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .search-form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-form input, .search-form button {
            padding: 10px;
            margin: 5px 10px 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-form button {
            background-color: #003366;
            color: white;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #00509e;
        }
        .no-records {
            text-align: center;
            color: #999;
        }
        .record-actions {
            text-align: center;
        }
        /* Button styling */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            color: white;
            text-decoration: none;
            margin: 0 5px;
            display: inline-block;
        }
        .btn-edit {
            background-color: #28a745;
            transition: background-color 0.3s;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-delete {
            background-color: #dc3545;
            transition: background-color 0.3s;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .btn-print {
            background-color: #17a2b8;
            transition: background-color 0.3s;
        }
        .btn-print:hover {
            background-color: #138496;
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

<!-- Search Form in Urdu -->
<div class="search-form">
    <form method="POST" action="update_record.php">
        <label for="case_number">مقدمہ نمبر: </label>
        <input type="text" name="case_number" placeholder="مقدمہ نمبر درج کریں">
        <button type="submit" name="search">تلاش کریں</button>
    </form>
</div>

<!-- Display Record or Message -->
<?php if ($record): ?>
    <table>
        <tr>
            <th>نمبر شمار</th>
            <th>سال</th>
            <th>تھانہ</th>
            <th>مقدمہ نمبر</th>
            <th>تاریخ</th>
            <th>بجرم</th>
            <th>قسم مال مقدمہ</th>
            <th>نام ملزم</th>
            <th>مد نمبر مالخانہ</th>
            <th>تاریخ فیصلہ</th>
            <th>حکم عدالت</th>
            <th>عدالت/جج صاحب</th>
            <th>کیفیت</th>
            <th>تصویر مال مقدمہ</th>
            <th>عمل</th>
        </tr>
        <tr>
            <td><?php echo $record['id']; ?></td>
            <td><?php echo $record['year']; ?></td>
            <td><?php echo $record['thana']; ?></td>
            <td><?php echo $record['case_number']; ?></td>
            <td><?php echo $record['date']; ?></td>
            <td><?php echo $record['crime_type']; ?></td>
            <td><?php echo $record['property_type']; ?></td>
            <td><?php echo $record['accused_name']; ?></td>
            <td><?php echo $record['store_id']; ?></td>
            <td><?php echo $record['decision_date']; ?></td>
            <td><?php echo $record['court_order']; ?></td>
            <td><?php echo $record['judge']; ?></td>
            <td><?php echo $record['status']; ?></td>
            <td>
                <?php if (!empty($record['property_image'])): ?>
                    <img src="<?php echo $record['property_image']; ?>" alt="Property Image">
                <?php else: ?>
                    تصویر نہیں ہے
                <?php endif; ?>
            </td>
            <td class="record-actions">
                <a href="edit_record.php?id=<?php echo $record['id']; ?>" class="btn btn-edit">ترمیم کریں</a> 
               </td>
        </tr>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="no-records">کوئی ریکارڈ نہیں ملا</div>
<?php endif; ?>
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
