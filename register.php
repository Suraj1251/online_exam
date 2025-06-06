<?php
session_start();
include("config/db.php");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $msg = "Passwords do not match!";
    } else {
        // Check if user already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "Username already taken!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $msg = "Registration failed. Try again.";
            }
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration - Online Exam</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }

        .register-box {
            width: 330px;
            margin: 100px auto;
            padding: 25px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 0 10px #aaa;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type=text], input[type=password] {
            width: 92%;
            padding: 10px;
            margin-bottom: 10px;
        }

        input[type=submit] {
            background: #28a745;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            text-decoration: none;
            color: #007bff;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Student Registration</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm" placeholder="Confirm Password" required><br>
            <input type="submit" value="Register">
        </form>
        <p class="error"><?= $msg ?></p>
        <div class="link">Already have an account? <a href="login.php">Login here</a></div>
    </div>
</body>
</html>
