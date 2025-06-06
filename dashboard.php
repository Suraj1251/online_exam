<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Example: Replace with real user info from DB if needed
$student_name = $_SESSION['username'] ?? "Student Name";
$student_role = "Student";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* Navbar */
        .navbar {
            background-color: #add8e6; /* light blue */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .logout {
            text-decoration: none;
            background-color: #ff4d4d;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout:hover {
            background-color: #e60000;
        }

        /* Student info section */
        .info {
            background-color: #e6f2ff;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        /* Center button */
        .center {
            text-align: center;
            margin-top: 100px;
        }

        .start-btn {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .start-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Welcome to Online Exam</h2>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <div class="info">
        Name: <strong><?php echo htmlspecialchars($student_name); ?></strong><br>
        Role: <strong><?php echo htmlspecialchars($student_role); ?></strong>
    </div>

    <div class="center">
        <a class="start-btn" href="exam.php">Start Exam</a>
    </div>

</body>
</html>
