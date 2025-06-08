<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "student_feedback";  // changed from online_exam_db to student_feedback

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
