<?php
include 'config.php';  // Include your database connection

// Function to delete all records from the 'records' table
function deleteAllRecords() {
    global $conn;  // Access the global database connection

    // SQL query to delete all records
    $sql = "DELETE FROM records";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "All records have been deleted successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Call the function to delete all records
deleteAllRecords();

// Close the database connection
$conn->close();
?>
