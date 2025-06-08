<?php
session_start();
include("config/db.php"); // Make sure this connects to student_feedback DB

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure these match your HTML form field names
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $student = $result->fetch_assoc();

            // Check password - change to === if you're not using hashed passwords
            if (password_verify($password, $student['password'])) {
                $_SESSION['user_id'] = $student['id'];
                $_SESSION['name'] = $student['name'];
                $_SESSION['email'] = $student['email'];
                $_SESSION['photo'] = $student['photo'];

                header("Location: dashboard.php");
                exit;
            } else {
                $msg = "Incorrect password.";
            }
        } else {
            $msg = "Email not found.";
        }
    } else {
        $msg = "Please enter both email and password.";
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
        .link { text-align: center; margin-top: 15px; }
        .link a { text-decoration: none; color: #007bff; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <p class="error"><?= $msg ?></p>
        <!-- <div class="link">Don't have an account? <a href="register.php">Sign up</a></div> -->
    </div>
</body>
</html>


