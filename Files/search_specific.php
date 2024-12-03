<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all Thanas for the dropdown
$thanas = [];
$stmt = $conn->prepare("SELECT DISTINCT thana FROM records WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $thanas[] = $row['thana'];
}
$stmt->close();

// Perform search if form is submitted
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $thana = $_POST['thana'];
    $case_number = $_POST['case_number'];

    // Validate input
    if (!empty($thana) && !empty($case_number)) {
        $stmt = $conn->prepare("SELECT * FROM records WHERE thana = ? AND case_number = ? AND user_id = ?");
        $stmt->bind_param("ssi", $thana, $case_number, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $search_results = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $search_results = [];
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کیس تلاش کریں</title>
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
        input[type="text"], select {
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
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th, td {
            padding: 12px;
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

<h2>کیس تلاش کریں</h2>

<!-- Search Form -->
<form method="POST" action="search_case.php">
    <label for="thana">تھانہ منتخب کریں:</label>
    <select name="thana" required>
        <option value="">-- تھانہ منتخب کریں --</option>
        <?php foreach ($thanas as $thana): ?>
            <option value="<?php echo $thana; ?>"><?php echo $thana; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="case_number">مقدمہ نمبر:</label>
    <input type="text" name="case_number" required>

    <button type="submit" name="search">تلاش کریں</button>
</form>

<!-- Display Search Results -->
<?php if (count($search_results) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>مقدمہ نمبر</th>
                <th>سال</th>
                <th>تھانہ</th>
                <th>بجرم</th>
                <th>تاریخ</th>
                <th>ملزم کا نام</th>
                <th>کیفیت</th>
                <th>تفصیلات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($search_results as $record): ?>
                <tr>
                    <td><?php echo $record['case_number']; ?></td>
                    <td><?php echo $record['year']; ?></td>
                    <td><?php echo $record['thana']; ?></td>
                    <td><?php echo $record['crime_type']; ?></td>
                    <td><?php echo $record['date']; ?></td>
                    <td><?php echo $record['accused_name']; ?></td>
                    <td><?php echo $record['status']; ?></td>
                    <td><a href="edit_record.php?id=<?php echo $record['id']; ?>">تفصیل دیکھیں</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div style="color: red; text-align: center;">مطلوبہ مقدمہ نمبر اور تھانہ نہیں ملا!</div>
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
