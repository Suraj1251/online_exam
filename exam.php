<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = rand(1000, 9999); // temporary session
}

if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = [];
    $_SESSION['current_q'] = 0;
}

$question_index = $_SESSION['current_q'];
$result = $conn->query("SELECT * FROM questions");
$questions = $result->fetch_all(MYSQLI_ASSOC);
$total = count($questions);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $selected = isset($_POST['answer']) ? $_POST['answer'] : "";
    $_SESSION['answers'][$question_index] = $selected;

    if (isset($_POST['next']) && $question_index < $total - 1) {
        $_SESSION['current_q']++;
    }
    if (isset($_POST['prev']) && $question_index > 0) {
        $_SESSION['current_q']--;
    }
    if (isset($_POST['finish'])) {
        header("Location: result.php");
        exit;
    }

    $question_index = $_SESSION['current_q'];
}

$question = $questions[$question_index];
$saved_answer = isset($_SESSION['answers'][$question_index]) ? $_SESSION['answers'][$question_index] : "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            background-color: #fff;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        h3 {
            margin-bottom: 20px;
        }

        .options label {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
            background: #f9f9f9;
            transition: 0.3s;
        }

        .options input[type="radio"] {
            margin-right: 10px;
        }

        .options label:hover {
            background-color: #f0f0f0;
        }

        button {
            padding: 10px 20px;
            margin: 10px 5px 0 0;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h3>Question <?= $question_index + 1 ?> of <?= $total ?></h3>
        <p><?= $question['question'] ?></p>
        <form method="POST" onsubmit="return validateSelection()">
            <div class="options">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label>
                        <input type="radio" name="answer" value="<?= $i ?>" <?= $saved_answer == $i ? "checked" : "" ?>>
                        <?= $question["option$i"] ?>
                    </label>
                <?php endfor; ?>
            </div>
            <div>
                <?php if ($question_index > 0): ?>
                    <button class="btn btn-primary" name="prev">Previous</button>
                <?php endif; ?>
                <?php if ($question_index < $total - 1): ?>
                    <button class="btn btn-primary" name="next">Next</button>
                <?php else: ?>
                    <button class="btn btn-danger" name="finish">Finish</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
        function validateSelection() {
            const radios = document.querySelectorAll("input[name='answer']");
            let selected = false;
            radios.forEach(radio => {
                if (radio.checked) selected = true;
            });

            if (!selected) {
                alert("Please select an option before continuing.");
                return false;
            }
            return true;
        }

       
    </script>
</body>
</html>
