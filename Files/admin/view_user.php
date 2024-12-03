<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
   // Fetch distinct values for dropdowns
$year_query = "SELECT DISTINCT year FROM records WHERE user_id = '$user_id'";
$thana_query = "SELECT DISTINCT thana FROM records WHERE user_id = '$user_id'";
$crime_type_query = "SELECT DISTINCT crime_type FROM records WHERE user_id = '$user_id'";
$court_order_query = "SELECT DISTINCT court_order FROM records WHERE user_id = '$user_id'";
$status_query = "SELECT DISTINCT status FROM records WHERE user_id = '$user_id'";
$judge_query = "SELECT DISTINCT judge FROM records WHERE user_id = '$user_id'";

// Execute queries to get dropdown values
$years = $conn->query($year_query);
$thanas = $conn->query($thana_query);
$crime_types = $conn->query($crime_type_query);
$court_orders = $conn->query($court_order_query);
$statuses = $conn->query($status_query);
$judges = $conn->query($judge_query);
   // Query to fetch user details
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    // Query to fetch records based on filters
    $filters = [];
    $sql_filter = "SELECT * FROM records WHERE user_id = '$user_id'";
    if (isset($_POST['submit'])) {
        // Add filters to the query based on the input fields
        $year = $_POST['year'] ?? '';
        $thana = $_POST['thana'] ?? '';
        $case_number = $_POST['case_number'] ?? '';
        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $crime_type = $_POST['crime_type'] ?? '';
        $property_type = $_POST['property_type'] ?? '';
        $accused_name = $_POST['accused_name'] ?? '';
        $from_store_id = $_POST['from_store_id'] ?? '';
        $to_store_id = $_POST['to_store_id'] ?? '';
        $from_decision_date = $_POST['from_decision_date'] ?? '';
        $to_decision_date = $_POST['to_decision_date'] ?? '';
        $court_order = $_POST['court_order'] ?? '';
        $judge = $_POST['judge'] ?? '';
        $status = $_POST['status'] ?? '';
        $decision_date_filter = $_POST['decision_date_filter'] ?? '';

        if ($year) {
            $sql_filter .= " AND year = '$year'";
        }
        if ($thana) {
            $sql_filter .= " AND thana LIKE '%$thana%'";
        }
        if ($case_number) {
            $sql_filter .= " AND case_number LIKE '%$case_number%'";
        }
        if ($from_date) {
            $sql_filter .= " AND date >= '$from_date'";
        }
        if ($to_date) {
            $sql_filter .= " AND date <= '$to_date'";
        }
        if ($crime_type) {
            $sql_filter .= " AND crime_type LIKE '%$crime_type%'";
        }
        if ($property_type) {
            $sql_filter .= " AND property_type LIKE '%$property_type%'";
        }
        if ($accused_name) {
            $sql_filter .= " AND accused_name LIKE '%$accused_name%'";
        }
        if ($from_store_id) {
            $sql_filter .= " AND store_id >= '$from_store_id'";
        }
        if ($to_store_id) {
            $sql_filter .= " AND store_id <= '$to_store_id'";
        }
        if ($from_decision_date) {
            $sql_filter .= " AND decision_date >= '$from_decision_date'";
        }
        if ($to_decision_date) {
            $sql_filter .= " AND decision_date <= '$to_decision_date'";
        }
        if ($court_order) {
            $sql_filter .= " AND court_order LIKE '%$court_order%'";
        }
        if ($judge) {
            $sql_filter .= " AND judge LIKE '%$judge%'";
        }
        if ($status) {
            $sql_filter .= " AND status LIKE '%$status%'";
        }
        if ($decision_date_filter) {
            $sql_filter .= " AND decision_date IS NOT NULL";
        }
    }

    // Fetch the filtered records
    $records = $conn->query($sql_filter);
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ریکارڈ دیکھیں</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
 
    <style>
        /* Importing Jameel Noori font */
        @font-face {
    font-family: 'Jameel Noori';
    src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
}

body {
    font-family: 'Jameel Noori', sans-serif;
    direction: rtl;
    padding: 20px;
    background-color: #f7f7f7;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    text-align: center; /* Centering the table content */
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
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

.filters-container, .records-container {
    margin: 20px 0;
}

.filters-container select, .filters-container input {
    margin: 5px 10px;
    padding: 12px;
    font-family: 'Jameel Noori', sans-serif;
    font-size: 16px;
}

.filters-container label {
    font-family: 'Jameel Noori', sans-serif;
    font-size: 18px;
    margin-right: 10px;
}

.filters-container button {
    padding: 12px 20px;
    font-family: 'Jameel Noori', sans-serif;
    font-size: 18px;
    background-color: #0056b3;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-right: 15px; /* Added margin between buttons */
}

.filters-container button.clear-btn {
    background-color: #28a745;
}

.filters-container button.submit-btn {
    background-color: #28a745;
}



.filters-container button:hover {
    background-color: #004080;
    transform: translateY(-2px);
}

.filters-container button.clear-btn:hover {
    background-color: #218838;
}

.print-btn {
    margin-top: 10px;
    background-color: #003366;
    color: white;
    padding: 14px 24px;
    font-family: 'Jameel Noori', sans-serif;
    font-size: 18px;
    cursor: pointer;
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.print-btn:hover {
    background-color: #00224d;
    transform: translateY(-2px);
}

.record {
    margin: 20px 0;
    padding: 12px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
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
       <li> <a href="index.php" class="menu-link">ہوم  </a> </li>
       <li> <a href="send_notification.php" class="menu-link">نوٹیفیکیشن بھیجیں</a></li>
       <li>   <a href="view_notifications.php" class="menu-link">  نوٹیفیکیشن دیکھیں</a></li>
       <li>   <a href="logout.php" class="menu-link">لاگ آؤٹ</a></li>
        </ul>
    </nav>







</header>

<body>
  
    <form method="POST">
        <div class="filters-container">
        <label for="year">سال</label>
<select name="year" id="year">
    <option value="">تمام سال</option>
    <?php while ($year = $years->fetch_assoc()) { ?>
        <option value="<?php echo $year['year']; ?>"><?php echo $year['year']; ?></option>
    <?php } ?>
</select>

<label for="thana">تھانہ</label>
<select name="thana" id="thana">
    <option value="">تمام تھانہ جات</option>
    <?php while ($thana = $thanas->fetch_assoc()) { ?>
        <option value="<?php echo $thana['thana']; ?>"><?php echo $thana['thana']; ?></option>
    <?php } ?>
</select>


            <label for="case_number">مقدمہ نمبر</label>
            <input type="text" name="case_number" id="case_number" placeholder="مقدمہ نمبر لکھیں">

            <label for="from_date">تاریخ سے</label>
            <input type="date" name="from_date" id="from_date">

            <label for="to_date">تاریخ تک</label>
            <input type="date" name="to_date" id="to_date">

            <label for="crime_type">بجرم</label>
<select name="crime_type" id="crime_type">
    <option value="">تمام نوعیت</option>
    <?php while ($crime_type = $crime_types->fetch_assoc()) { ?>
        <option value="<?php echo $crime_type['crime_type']; ?>"><?php echo $crime_type['crime_type']; ?></option>
    <?php } ?>
</select>



<br>  <label for="property_type">تفصیل مال مقدمہ</label>
            <input type="text" name="property_type" id="property_type" placeholder="تفصیل مال مقدمہ لکھیں">

            <label for="accused_name">ملزم کا نام</label>
            <input type="text" name="accused_name" id="accused_name" placeholder="ملزم کا نام لکھیں">

            <label for="from_store_id">مد نمبر  سے</label>
            <input type="text" name="from_store_id" id="from_store_id" placeholder="مد نمبر  سے">

            <br>   <label for="to_store_id">مد نمبر  تک</label>
            <input type="text" name="to_store_id" id="to_store_id" placeholder="مد نمبر  تک">

            <label for="from_decision_date">تاریخ فیصلہ سے</label>
            <input type="date" name="from_decision_date" id="from_decision_date"> 

            <label for="to_decision_date">تاریخ فیصلہ تک</label>
            <input type="date" name="to_decision_date" id="to_decision_date">

            <label for="court_order">عدالتی حکم</label>
<select name="court_order" id="court_order">
    <option value="">تمام حکم</option>
    <?php while ($court_order = $court_orders->fetch_assoc()) { ?>
        <option value="<?php echo $court_order['court_order']; ?>"><?php echo $court_order['court_order']; ?></option>
    <?php } ?>
</select>
<label for="judge">جج کا نام</label>
<select name="judge" id="judge">
    <option value="">تمام ججز</option>
    <?php while ($judge = $judges->fetch_assoc()) { ?>
        <option value="<?php echo $judge['judge']; ?>"><?php echo $judge['judge']; ?></option>
    <?php } ?>
</select>
            <label for="status">کیفیت</label>
<select name="status" id="status">
    <option value="">تمام کیفیت</option>
    <?php while ($status = $statuses->fetch_assoc()) { ?>
        <option value="<?php echo $status['status']; ?>"><?php echo $status['status']; ?></option>
    <?php } ?>
</select>
            <label for="decision_date_filter">تاریخ فیصلہ فلٹر</label>
            <input type="checkbox" name="decision_date_filter" id="decision_date_filter" value="1">
        </div>
        
        <button 
    type="submit" 
    name="submit" 
    style="
        padding: 12px 24px; 
        font-family: 'Jameel Noori', sans-serif; 
        font-size: 18px; 
        color: #ffffff; 
        background-color: #003366; 
        border: none; 
        border-radius: 5px; 
        cursor: pointer; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        transition: all 0.3s ease;
    " 
    onmouseover="this.style.backgroundColor='#00224d'; this.style.transform='translateY(-2px)';" 
    onmouseout="this.style.backgroundColor='#003366'; this.style.transform='translateY(0px)';" 
    onmousedown="this.style.backgroundColor='#001a33'; this.style.transform='translateY(1px)';"
>
    فلٹر لگائیں
</button>
<button class="print-btn" onclick="printFilteredRecords()">پرنٹ کریں</button>






   </form>

    <table>
        <thead>
            <tr>
                <th>سال</th>
                <th>تھانہ</th>
                <th>مقدمہ نمبر</th>
                <th>تاریخ</th>
                <th>بجرم</th>
                <th>تفصیل مال مقدمہ</th>
                <th>ملزم کا نام</th>
                <th>مد نمبر </th>
                <th>تاریخ فیصلہ</th>
                <th>عدالتی حکم</th>
                <th>جج کا نام</th>
                <th>کیفیت</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($record = $records->fetch_assoc()) { ?>
                <tr>
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
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
</body>
<script>
    function printFilteredRecords() {
        // Create a new window
        var printWindow = window.open('', '_blank', 'width=800,height=600');

        // Get the content of the table
        var tableContent = document.querySelector('table').outerHTML;

        // Create a simple HTML structure for the print window
        printWindow.document.write('<html><head><title>پرنٹ ریکارڈ</title>');
        
        // Include the Jameel Noori font for printing
        printWindow.document.write('<style>@font-face { font-family: "Jameel Noori"; src: url("fonts/JameelNooriNastaleeq.ttf") format("truetype"); }');
        printWindow.document.write('@media print { body { font-family: "Jameel Noori", sans-serif; direction: rtl; } table { width: 100%; border-collapse: collapse; margin-top: 20px; } th, td { padding: 8px; text-align: center; border: 1px solid #ddd; } th { background-color: #003366; color: white; } }</style>');
        
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2 style="font-family: \'Jameel Noori\', sans-serif;">مال مقدمات جمع شدہ مالخانہ سرکل ـــــــــــــــــــــــــــــــــــــــــــــــــ</h2>');
        printWindow.document.write(tableContent); // Add the table content
        printWindow.document.write('</body></html>');

        // Close the document to finish writing
        printWindow.document.close();

        // Wait until the content is fully loaded, then trigger the print dialog
        printWindow.onload = function() {
            printWindow.print();
        };
    }
</script>
<div class="footer-container">
    <div class="link-container">
    <a href="index.php" class="footer-link">ہوم  </a>
        <a href="send_notification.php" class="footer-link">نوٹیفیکیشن بھیجیں</a>
        <a href="view_notifications.php" class="footer-link">  نوٹیفیکیشن دیکھیں</a>
        <a href="logout.php" class="footer-link">لاگ آؤٹ</a>
       
    </div>
</div>
<div class="developer">
        <p>Developed with <i class="mdi mdi-heart text-red-600"></i> by <a href="https://www.linkedin.com/in/umar-pervaiz/" target="_blank" class="text-reset">Umar Pervaiz</a><br>
        CEO CodeCrafters Lab - IT Solutions <a href="https://www.codexcrafters.com" target="_blank">codexcrafters.com</a></p>
    </div>
</html>