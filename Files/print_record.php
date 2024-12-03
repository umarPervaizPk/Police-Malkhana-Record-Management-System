<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the record for printing
$record = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM records WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    
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
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرنٹ ریکارڈ</title>
    <style>
        /* Importing Urdu Font 'Jameel Noori' */
        @font-face {
            font-family: 'Jameel Noori';
            src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
        }

        body {
            font-family: 'Jameel Noori', sans-serif;
            color: #333;
            padding: 20px;
            background-color: #f7f7f7;
            direction: rtl;
        }

        .print-container {
            margin: 20px auto;
            width: 80%;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .print-container h2 {
            text-align: center;
            color: #003366;
        }

        .print-details {
            font-size: 1.2rem;
            margin: 15px 0;
            line-height: 1.8;
            text-align: justify;
        }

        .print-details span {
            font-weight: bold;
        }

        .signature {
            text-align: center;
            margin-top: 50px;
        }

        .btn-print {
            display: none;
        }

        @media print {
            body {
                font-family: 'Jameel Noori', sans-serif;
            }

            .btn-print {
                display: block;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print(); // Automatically trigger the print dialog when the page is loaded
        }
    </script>
</head>
<body>

<div class="print-container">
    <h2>تفصیل مال مقدمہ</h2>
    
    <div class="print-details">
        <?php
        // Start creating the paragraph for details
        $details = "";

        if (!empty($record['case_number'])) {
            $details .= "<span>مقدمہ نمبر:</span> " . $record['case_number'] . " ";
        }
        
        if (!empty($record['thana'])) {
            $details .= "<span>تھانہ:</span> " . $record['thana'] . " ";
        }
        
        if (!empty($record['date'])) {
            $details .= "<span>بتاریخ:</span> " . $record['date'] . " ";
        }
        
        if (!empty($record['store_id'])) {
            $details .= "<span>بمطابق مد نمبر رجسٹر مالخانہ:</span> " . $record['store_id'] . " ";
        }
        
        if (!empty($record['property_type'])) {
            $details .= "<span>مال مقدمہ کی تفصیل درج زیل ہے:</span> " . $record['property_type'] . " ";
        }
        
        if (!empty($record['accused_name'])) {
            $details .= "<span>نام ملزم:</span> " . $record['accused_name'] . " ";
        }
        
        if (!empty($record['decision_date'])) {
            $details .= "<span>تاریخ فیصلہ:</span> " . $record['decision_date'] . " ";
        }
        
        if (!empty($record['court_order'])) {
            $details .= "<span>حکم عدالت:</span> " . $record['court_order'] . " ";
        }
        
        if (!empty($record['judge'])) {
            $details .= "<span>عدالت/جج صاحب:</span> " . $record['judge'] . " ";
        }
        
        if (!empty($record['status'])) {
            $details .= "<span>کیفیت:</span> " . $record['status'] . " ";
        }
        
        // Add a single full stop at the end of the paragraph
        $details = rtrim($details, " ") . "۔";  // Remove any trailing space and add one full stop at the end
        
        // Add spacing between labels and values in the display
        $details = str_replace(": ", ":&nbsp;&nbsp;", $details); // Adding two spaces between label and value
        
        echo $details;
        ?>
    </div>

    <div class="signature">
        <p>دستخط / مہر انچارج مالخانہ</p>
    </div>

    <a href="javascript:window.print()" class="btn-print">پرنٹ کریں</a>
</div>

</body>
</html>
