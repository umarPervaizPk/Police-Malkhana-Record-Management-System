<?php
// Ensure the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $caseNumber = $_POST['caseNumber'];
    $year = $_POST['year'];
    $thana = $_POST['thana'];
    $case = $_POST['case'];
    $crime = $_POST['crime'];

    // Handle file upload
    if (isset($_FILES['propertyImage']) && $_FILES['propertyImage']['error'] == 0) {
        $fileTmpPath = $_FILES['propertyImage']['tmp_name'];
        $fileName = $_FILES['propertyImage']['name'];
        $fileSize = $_FILES['propertyImage']['size'];
        $fileType = $_FILES['propertyImage']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions for images
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the uploaded file has a valid image extension
        if (in_array($fileExtension, $allowedExtensions)) {
            // Define the upload directory
            $uploadDir = 'uploads/';
            
            // Create a unique file name
            $newFileName = uniqid() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Image uploaded successfully, save the record in the database
                $imagePath = $destPath; // The path where the image is saved

                // Database connection (adjust the values below)
                $host = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'police_management_system'; // Update with your actual DB name

                // Create a new PDO instance
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Prepare the SQL query to insert form data into the database
                    $sql = "INSERT INTO records (case_number, year, thana, case_name, crime, property_image) 
                            VALUES (:caseNumber, :year, :thana, :case, :crime, :propertyImage)";
                    $stmt = $pdo->prepare($sql);

                    // Bind the form data to the query
                    $stmt->bindParam(':caseNumber', $caseNumber);
                    $stmt->bindParam(':year', $year);
                    $stmt->bindParam(':thana', $thana);
                    $stmt->bindParam(':case', $case);
                    $stmt->bindParam(':crime', $crime);
                    $stmt->bindParam(':propertyImage', $imagePath);

                    // Execute the query
                    $stmt->execute();

                    // Success message
                    echo "ریکارڈ کامیابی سے شامل کر لیا گیا!";
                } catch (PDOException $e) {
                    echo "Database Error: " . $e->getMessage();
                }
            } else {
                echo "تصویر اپ لوڈ کرنے میں خرابی ہوئی۔";
            }
        } else {
            echo "صرف تصویر کی فائلز کو اپ لوڈ کیا جا سکتا ہے (JPG, PNG, GIF)";
        }
    } else {
        echo "تصویر منتخب نہیں کی گئی ہے یا کوئی مسئلہ ہے۔";
    }
} else {
    echo "غلط درخواست کا طریقہ!";
}
?>
