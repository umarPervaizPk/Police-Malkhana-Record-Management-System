<?php
include 'config.php';  // Include your database connection

// Function to generate random data for each record
function generateRandomData($year) {
    $thanas = ['سٹی', 'چکوال', 'لاہور', 'ملتان', 'کراچی'];
    $crime_types = ['چوری', 'دھوکہ دہی', 'قتل', 'زنا', 'ڈرگ'];
    $property_types = ['نقدی', 'گھر', 'موٹر سائیکل', 'گھریلو سامان', 'موبائل فون'];
    $statuses = ['زیر سماعت', 'فیصلہ شدہ', 'موقف سے ہٹ کر'];
    $judges = ['جج احمد', 'جج فاروق', 'جج زہرہ', 'جج فاطمہ', 'جج محمد'];

    // Randomly select data from arrays
    $thana = $thanas[array_rand($thanas)];
    $crime_type = $crime_types[array_rand($crime_types)];
    $property_type = $property_types[array_rand($property_types)];
    $status = $statuses[array_rand($statuses)];
    $judge = $judges[array_rand($judges)];
    
    // Generate random case number and other details
    $case_number = rand(1000, 9999);
    $accused_name = 'ملزم ' . rand(1, 1000);  // Some common names for demo
    $store_id = rand(1, 100);
    $date = $year . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

    // Randomly decide whether to generate decision date
    $has_decision_date = rand(0, 1) == 0;

    // If there is no decision date, do not generate court order and judge
    if ($has_decision_date) {
        // Generate decision date, court order, and judge if decision date exists
        $decision_date = $year . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
        $court_order = 'فیصلہ ' . rand(1, 100);
        $judge = $judges[array_rand($judges)];
    } else {
        // Set them to NULL if there is no decision date
        $decision_date = NULL;
        $court_order = NULL;
        $judge = NULL;
    }

    $property_image = rand(0, 1) ? 'uploads/sample.jpg' : '';

    return [
        'year' => $year,
        'thana' => $thana,
        'crime_type' => $crime_type,
        'case_number' => $case_number,
        'date' => $date,
        'property_type' => $property_type,
        'accused_name' => $accused_name,
        'store_id' => $store_id,
        'decision_date' => $decision_date,
        'court_order' => $court_order,
        'judge' => $judge,
        'status' => $status,
        'property_image' => $property_image
    ];
}

// Generate 10,000 records for different years
$years = range(2000, 2024);
$records = [];

foreach ($years as $year) {
    for ($i = 0; $i < 5; $i++) {  // Generating 500 records per year, totaling 10,000
        $data = generateRandomData($year);
        $records[] = $data;
    }
}

// Prepare and execute SQL queries for insertion
foreach ($records as $record) {
    // Prepare SQL query to insert the record into the database
    $sql = "INSERT INTO records (year, thana, crime_type, case_number, date, property_type, accused_name, store_id, decision_date, court_order, judge, status, property_image, user_id)
            VALUES ('" . $record['year'] . "', '" . $record['thana'] . "', '" . $record['crime_type'] . "', '" . $record['case_number'] . "', '" . $record['date'] . "', '" . $record['property_type'] . "', '" . $record['accused_name'] . "', '" . $record['store_id'] . "', 
            " . ($record['decision_date'] ? "'" . $record['decision_date'] . "'" : 'NULL') . ", 
            " . ($record['court_order'] ? "'" . $record['court_order'] . "'" : 'NULL') . ", 
            " . ($record['judge'] ? "'" . $record['judge'] . "'" : 'NULL') . ", 
            " . ($record['status'] ? "'" . $record['status'] . "'" : 'NULL') . ", 
            '" . $record['property_image'] . "', 1)";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Record for case number " . $record['case_number'] . " inserted successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
