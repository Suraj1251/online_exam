<?php
session_start();
include("config/db.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Incorrect password.";
        }
    } else {
        $msg = "User not found.";
    }
}
?>
<!-- Your HTML form follows below -->


<!DOCTYPE html>
<html>
<head>
    <title>Login - Online Exam</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .login-box { width: 300px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 5px; box-shadow: 0 0 10px #aaa; }
        input[type=text], input[type=password] { width: 92%; padding: 10px; margin-bottom: 10px; }
        input[type=submit] { background: #007bff; color: #fff; padding: 10px; border: none; cursor: pointer; width: 100%; }
        .error { color: red; }
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
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <p class="error"><?= $msg ?></p>
                <div class="link">Don't have an account? <a href="register.php">Sign up</a></div>
    </div>
</body>
</html>
