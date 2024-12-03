<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch filter options for dropdowns (Year, Thana, Crime Type, Court Order, Judge, Status)
$years_query = "SELECT DISTINCT YEAR(date) AS year FROM records ORDER BY year DESC";
$years_result = $conn->query($years_query);

$thanas_query = "SELECT DISTINCT thana FROM records ORDER BY thana";
$thanas_result = $conn->query($thanas_query);

$crime_types_query = "SELECT DISTINCT crime_type FROM records ORDER BY crime_type";
$crime_types_result = $conn->query($crime_types_query);

$court_orders_query = "SELECT DISTINCT court_order FROM records WHERE court_order IS NOT NULL ORDER BY court_order";
$court_orders_result = $conn->query($court_orders_query);

$judges_query = "SELECT DISTINCT judge FROM records WHERE judge IS NOT NULL ORDER BY judge";
$judges_result = $conn->query($judges_query);

$statuses_query = "SELECT DISTINCT status FROM records WHERE status IS NOT NULL ORDER BY status";
$statuses_result = $conn->query($statuses_query);

// Fetch filtered records based on user input
$where_clause = "WHERE user_id = " . $_SESSION['user_id'];
$filter_params = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)) {
    // Construct the WHERE clause based on user inputs
    if (!empty($_GET['year'])) {
        $where_clause .= " AND YEAR(date) = ?";
        $filter_params[] = $_GET['year'];
    }

    if (!empty($_GET['thana'])) {
        $where_clause .= " AND thana = ?";
        $filter_params[] = $_GET['thana'];
    }

    if (!empty($_GET['case_number'])) {
        $where_clause .= " AND case_number LIKE ?";
        $filter_params[] = '%' . $_GET['case_number'] . '%';
    }

    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $where_clause .= " AND date BETWEEN ? AND ?";
        $filter_params[] = $_GET['from_date'];
        $filter_params[] = $_GET['to_date'];
    }

    if (!empty($_GET['crime_type'])) {
        $where_clause .= " AND crime_type = ?";
        $filter_params[] = $_GET['crime_type'];
    }

    if (!empty($_GET['property_type'])) {
        $where_clause .= " AND property_type LIKE ?";
        $filter_params[] = '%' . $_GET['property_type'] . '%';
    }

    if (!empty($_GET['accused_name'])) {
        $where_clause .= " AND accused_name LIKE ?";
        $filter_params[] = '%' . $_GET['accused_name'] . '%';
    }

    if (!empty($_GET['from_store_id']) && !empty($_GET['to_store_id'])) {
        $where_clause .= " AND store_id BETWEEN ? AND ?";
        $filter_params[] = $_GET['from_store_id'];
        $filter_params[] = $_GET['to_store_id'];
    }

    if (!empty($_GET['from_decision_date']) && !empty($_GET['to_decision_date'])) {
        $where_clause .= " AND decision_date BETWEEN ? AND ?";
        $filter_params[] = $_GET['from_decision_date'];
        $filter_params[] = $_GET['to_decision_date'];
    }

    // Apply court_order, judge, and status filters only if decision_date is not NULL
    if (!empty($_GET['court_order'])) {
        $where_clause .= " AND court_order = ?";
        $filter_params[] = $_GET['court_order'];
    }

    if (!empty($_GET['judge'])) {
        $where_clause .= " AND judge = ?";
        $filter_params[] = $_GET['judge'];
    }

    if (!empty($_GET['status'])) {
        $where_clause .= " AND status = ?";
        $filter_params[] = $_GET['status'];
    }

    // Add filter to show records with or without decision date
    if (isset($_GET['decision_date_filter']) && $_GET['decision_date_filter'] !== "") {
        if ($_GET['decision_date_filter'] == "has_decision_date") {
            $where_clause .= " AND decision_date IS NOT NULL";
        } else {
            $where_clause .= " AND decision_date IS NULL";
        }
    }
}

// Final SQL Query to fetch records based on the filters
$sql = "SELECT * FROM records " . $where_clause . " ORDER BY date DESC";
$stmt = $conn->prepare($sql);
if (!empty($filter_params)) {
    $stmt->bind_param(str_repeat('s', count($filter_params)), ...$filter_params); // Bind params dynamically
}
$stmt->execute();
$records = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرنٹ ریکارڈ</title>
    <style>
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

<!-- Filters Form -->
<div class="filters-container">
    <form action="print_pending.php" method="GET">
        <label for="year">سال منتخب کریں:</label>
        <select name="year" id="year">
            <option value="">تمام سال</option>
            <?php while ($row = $years_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['year']; ?>" <?php echo isset($_GET['year']) && $_GET['year'] == $row['year'] ? 'selected' : ''; ?>>
                    <?php echo $row['year']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="thana">تھانہ منتخب کریں:</label>
        <select name="thana" id="thana">
            <option value="">تمام تھانہ جات</option>
            <?php while ($row = $thanas_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['thana']; ?>" <?php echo isset($_GET['thana']) && $_GET['thana'] == $row['thana'] ? 'selected' : ''; ?>>
                    <?php echo $row['thana']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="case_number">مقدمہ نمبر:</label>
        <input type="text" name="case_number" id="case_number" value="<?php echo isset($_GET['case_number']) ? $_GET['case_number'] : ''; ?>" />

        <label for="from_date">تاریخ سے:</label>
        <input type="date" name="from_date" id="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>" />

        <label for="to_date">تاریخ تک:</label>
        <input type="date" name="to_date" id="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>" />

        <label for="crime_type">جرم کا نوعیت:</label>
        <select name="crime_type" id="crime_type">
            <option value="">تمام قسم</option>
            <?php while ($row = $crime_types_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['crime_type']; ?>" <?php echo isset($_GET['crime_type']) && $_GET['crime_type'] == $row['crime_type'] ? 'selected' : ''; ?>>
                    <?php echo $row['crime_type']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="property_type">مال کی قسم:</label>
        <input type="text" name="property_type" id="property_type" value="<?php echo isset($_GET['property_type']) ? $_GET['property_type'] : ''; ?>" />

        <label for="accused_name">ملزم کا نام:</label>
        <input type="text" name="accused_name" id="accused_name" value="<?php echo isset($_GET['accused_name']) ? $_GET['accused_name'] : ''; ?>" />

        <label for="from_store_id">مال خانہ سے:</label>
        <input type="number" name="from_store_id" id="from_store_id" value="<?php echo isset($_GET['from_store_id']) ? $_GET['from_store_id'] : ''; ?>" />

        <label for="to_store_id">مال خانہ تک:</label>
        <input type="number" name="to_store_id" id="to_store_id" value="<?php echo isset($_GET['to_store_id']) ? $_GET['to_store_id'] : ''; ?>" />

      

        
        <label for="decision_date_filter"َََ>غیر فیصلہ شدہ</label>
        <select name="decision_date_filter" id="decision_date_filter">
            <option value="no_decision_date" <?php echo isset($_GET['decision_date_filter']) && $_GET['decision_date_filter'] == 'no_decision_date' ? 'selected' : ''; ?>>فیصلہ نہ شدہ</option>
        </select>
        <button type="submit" class="submit-btn">فلٹر کریں</button>
        <button type="button" class="clear-btn" id="resetBtn">تمام فلٹر صاف کریں</button>
        <button type="button" class="print-btn" id="printFilteredBtn">ریکارڈز پرنٹ کریں</button>
 
    </form>
</div>

<!-- Filtered Records Table -->
<div class="records-container">
    <table>
        <thead>
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
              
                <th>کیفیت</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($records->num_rows > 0) {
                while ($row = $records->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td>" . $row['thana'] . "</td>";
                    echo "<td>" . $row['case_number'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['crime_type'] . "</td>";
                    echo "<td>" . $row['property_type'] . "</td>";
                    echo "<td>" . $row['accused_name'] . "</td>";
                    echo "<td>" . $row['store_id'] . "</td>";
             
                    echo "<td>" . $row['status'] . "</td>";
                    
                    // Display property image if available
                  
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Print Button -->


<script>
    // Reset Button Functionality
    document.getElementById('resetBtn').addEventListener('click', function () {
    const form = document.querySelector('form');
    form.reset();
    
    // Clear query parameters from the URL
    const url = new URL(window.location.href);
    url.search = ''; // Remove all query parameters
    window.history.pushState({}, document.title, url);

    // Reload the page to reset the filters
    location.href = location.pathname; // Reload page without filters
    });

    // Print Filtered Records Functionality
    document.getElementById('printFilteredBtn').addEventListener('click', function () {
        // Get the filtered records (in this case, only the table within the records container)
        const filteredContent = document.querySelector('.records-container').innerHTML;
        
        // Create a new window for print
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Filtered Records</title>');
        
        // Include the custom font for printing
        printWindow.document.write(`
            <style>
                @font-face {
                    font-family: 'Jameel Noori';
                    src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
                }
                body {
                    font-family: 'Jameel Noori', sans-serif;
                    direction: rtl;
                    padding: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
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
            </style>
        `);
        
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>فہرست مال مقدمات جمع شدہ مالخانہ سرکل ـــــــــــــــــــــــــــــــــ  </h2>');
        printWindow.document.write(filteredContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>

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

</body>
</html>