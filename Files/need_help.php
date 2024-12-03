<?php
include 'config.php'; // Include database connection

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert the contact message into the database (optional)
    $sql = "INSERT INTO contact_us (user_id, name, email, message, date) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $_SESSION['user_id'], $name, $email, $message);
    
    if ($stmt->execute()) {
        $success_message = "آپ کا پیغام کامیابی سے بھیج دیا گیا ہے۔ ہم جلد ہی آپ سے رابطہ کریں گے۔";
    } else {
        $error_message = "پیغام بھیجنے میں مسئلہ آیا ہے۔ براہ کرم دوبارہ کوشش کریں۔";
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رابطہ کریں</title>
    
    <style>
        @font-face {
            font-family: 'Jameel Noori';
            src: url('fonts/JameelNooriNastaleeq.ttf') format('truetype');
        }

        /* General page styles */
        body {
            font-family: 'Jameel Noori', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            direction: rtl;
        }

        /* Centered container for the contact form */
        .contact-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 650px;
        }

        /* Title style */
        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Input group style */
        .input-group {
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .input-group input, .input-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus, .input-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .input-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        /* Submit button style */
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        /* Error and success message style */
        .message {
            color: #ff0000;
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
        }

        .success-message {
            color: #28a745;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .contact-container {
                padding: 30px;
            }

            h1 {
                font-size: 24px;
            }

            .submit-btn {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h1>رابطہ کریں</h1>
        
        <!-- Success/Error Message -->
        <?php if (isset($success_message)) { ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php } elseif (isset($error_message)) { ?>
            <div class="message"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="input-group">
                <label for="name">آپ کا نام</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-group">
                <label for="email">آپ کا ای میل</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="message">پیغام</label>
                <textarea name="message" required></textarea>
            </div>

            <button type="submit" class="submit-btn">پیغام بھیجیں</button>
        </form>
    </div>
</body>
</html>
