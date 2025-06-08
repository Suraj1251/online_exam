<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['user_id']) || !isset($_SESSION['answers'])) {
    header("Location: login.php");
    exit;
}

$answers = $_SESSION['answers'];
$result = $conn->query("SELECT * FROM questions");
$questions = $result->fetch_all(MYSQLI_ASSOC);

$score = 0;
$total = count($questions);
$negative_mark = 0;
$student_name = $_SESSION['name'] ?? "Student Name";
// $student_role = "Student";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Result</title>
   <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .header {
        background-color: yellow;
        color: black;
        text-align: center;
        padding: 20px;
        font-size: 56px;
        font-weight: bold;
    }

    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th {
        background-color: #007BFF; /* Bootstrap blue */
        color: white;
        font-size: 16px;
        padding: 12px;
    }

    td {
        border: 1px solid #ccc;
        padding: 12px;
        text-align: left;
    }

    .correct {
        background-color: #d4edda; /* green */
    }

    .wrong {
        background-color: #f8d7da; /* red */
    }

    .score {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 40px;

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

                .info {
            background-color: #e6f2ff;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }


</style>

</head>
<body>

        <div class="navbar">
        <h2>Welcome to Online Exam</h2>
        <a class="logout" href="dashboard.php">Home</a>
    </div>

    <div class="header">Your Result</div>

        <div class="info">
        Name: <strong><?php echo htmlspecialchars($student_name); ?></strong><br>
        <!-- Role: <strong><?php echo htmlspecialchars($student_role); ?></strong> -->
    </div>


    <table>
        <tr>
            <th>Q.No</th>
            <th>Question</th>
            <th>Your Answer</th>
            <th>Correct Answer</th>
            <th>Status</th>
        </tr>

        <?php
        foreach ($questions as $index => $q) {
            $correct = explode(",", $q['correct_answers']);
            $user_answer = isset($answers[$index]) ? explode(",", $answers[$index]) : [];

            sort($correct);
            sort($user_answer);

            $is_correct = ($correct == $user_answer);
            $row_class = $is_correct ? "correct" : "wrong";

            echo "<tr class='$row_class'>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>" . $q['question'] . "</td>";

            echo "<td>";
            foreach ($user_answer as $ans) {
                echo $q["option$ans"] . "<br>";
            }
            echo "</td>";

            echo "<td>";
            foreach ($correct as $ans) {
                echo $q["option$ans"] . "<br>";
            }
            echo "</td>";

            echo "<td>" . ($is_correct ? "Correct" : "Wrong") . "</td>";
            echo "</tr>";

            if ($is_correct) {
                $score++;
            } else {
                $score -= $negative_mark;
            }
        }
        ?>

    </table>

    <div class="score">Final Score: <?php echo max(0, $score); ?> / <?php echo $total; ?></div>

</body>
</html>

<?php
// Clear session
unset($_SESSION['answers']);
unset($_SESSION['current_q']);
?>
