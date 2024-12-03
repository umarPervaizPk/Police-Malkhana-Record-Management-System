<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Connect to the database
include 'config.php';

// Fetch users from the database
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Jameel Noori', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            margin: 50px auto;
            max-width: 1200px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #003366;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #003366;
            color: white;
        }
        .btn {
            background-color: #003366;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #00224d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Users</h2>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn">Edit</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
