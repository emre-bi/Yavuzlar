<?php
include 'db.php';

session_start();

$questions = getQuestions();

if($_SESSION["role"] == "admin"){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteQuestion'])) {
        $id = $_POST['question_id'];
        deleteQuestion($id);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['editQuestion'])){
        $id = $_GET['question_id'];
        
        $question = getQuestionById($id);

        $question_text = $question["question_text"];
        $option_1 = $question["option_a"];
        $option_2 = $question["option_b"];
        $option_3 = $question["option_c"];
        $option_4 = $question["option_d"];
        $correctAnswer = $question["correct_answer"];
        $difficultyLevel = $question["difficulty_level"];
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Dashboard</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
        <div class="container" id="edit-question-container">
            <form action="" method="POST">
                <input type="hidden" name="question_id" id="question-id" value=$id>
                
                <label for="question-input">Question:</label>
                <input type="textarea" id="question-input" name="question" value="$question_text"><br><br>
        
                <label for="option1-input">Option 1:</label>
                <input type="text" id="option1-input" name="option1" value="$option_1"><br><br>
        
                <label for="option2-input">Option 2:</label>
                <input type="text" id="option2-input" name="option2" value="$option_2"><br><br>
        
                <label for="option3-input">Option 3:</label>
                <input type="text" id="option3-input" name="option3" value="$option_3"><br><br>
        
                <label for="option4-input">Option 4:</label>
                <input type="text" id="option4-input" name="option4" value="$option_4"><br><br>
        
                <label for="correct-answer-input">Correct Answer:</label>
                <input type="text" id="correct-answer-input" name="correctAnswer" value="$correctAnswer"><br><br>

                <label for="difficulty-level-input">Difficulty Level:</label>
                <input type="text" id="difficulty-level-input" name="difficulty-level" value="$difficultyLevel"><br><br>
        
                <button type="submit" name="editQuestion">Save</button>
            </form>
        </div>
        </body>
        </html>
        HTML;
        exit();
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editQuestion'])){
        $id = $_POST["question_id"];
        $question_text = $_POST["question"];
        $option_1 = $_POST["option1"];
        $option_2 = $_POST["option2"];
        $option_3 = $_POST["option3"];
        $option_4 = $_POST["option4"];
        $correctAnswer = $_POST["correctAnswer"];
        $difficultyLevel = $_POST["difficulty-level"];

        editQuestion($id, $question_text, $correctAnswer, $option_1, $option_2, $option_3, $option_4, $difficultyLevel);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    elseif($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["addQuestion"])){
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Dashboard</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
        <div class="container">
            <form action="" method="POST">                
                <label for="question-input">Question:</label>
                <input type="textarea" id="question-input" name="question" value=""><br><br>
        
                <label for="option1-input">Option 1:</label>
                <input type="text" id="option1-input" name="option1" value=""><br><br>
        
                <label for="option2-input">Option 2:</label>
                <input type="text" id="option2-input" name="option2" value=""><br><br>
        
                <label for="option3-input">Option 3:</label>
                <input type="text" id="option3-input" name="option3" value=""><br><br>
        
                <label for="option4-input">Option 4:</label>
                <input type="text" id="option4-input" name="option4" value=""><br><br>
        
                <label for="correct-answer-input">Correct Answer:</label>
                <input type="text" id="correct-answer-input" name="correctAnswer" value=""><br><br>

                <label for="difficulty-level-input">Difficulty Level:</label>
                <input type="text" id="difficulty-level-input" name="difficulty-level" value=""><br><br>
        
                <button type="submit" name="addQuestion">Add</button>
            </form>
        </div>
        </body>
        </html>
        HTML;
        exit();
    }
    elseif($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addQuestion"])){
        $question_text = $_POST["question"];
        $option_1 = $_POST["option1"];
        $option_2 = $_POST["option2"];
        $option_3 = $_POST["option3"];
        $option_4 = $_POST["option4"];
        $correctAnswer = $_POST["correctAnswer"];
        $difficultyLevel = $_POST["difficulty-level"];
        addQuestion($question_text, $option_1, $option_2, $option_3, $option_4, $correctAnswer, $difficultyLevel);
        header("Location: /app.php");
        exit();
    }
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container" id="admin-panel">
            <div class="search-container">
                <input type="text" id="search-bar" class="search-bar" placeholder="Search questions...">
            </div>
    HTML;

    foreach($questions as $question){
        echo "<div class='question-container' id='question-container'>";
        echo "<span>" . $question["question_text"] . "</span>";
        echo '<form method="GET" action="" style="display:inline;">';
        echo '<input type="hidden" name="question_id" value="' . $question["question_id"] .'">';
        echo '<button type="submit" name="editQuestion" style="display:inline;">Edit</button>';
        echo "</form>";
        echo '<form method="POST" action="" style="display:inline;">';
        echo '<input type="hidden" name="question_id" value="' . $question["question_id"] .'">';
        echo '<button type="submit" name ="deleteQuestion" style="display:inline;">Delete</button>';
        echo "</form>";
        echo "</div>";
    }

    echo <<<HTML
        <form action="" method="GET">
            <button type="submit" name="addQuestion">Add New Question</button>
        </form>
        </div>
        <script src="script.js"></script>
    </body>
    </html>
    HTML;
}
elseif($_SESSION["role"] == "non-admin"){
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['startQuiz'])){
        $user = getUserById($_SESSION["user_id"]);

        $question = getQuestion($_SESSION["user_id"]);

        if($question){
            echo <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Quiz</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <div class="container" id="started-quiz">
            HTML;        
                echo '<h3 id="score">YOUR SCORE: ' . $user["score"] . '</h3>';
                echo '<h3 id="difficulty-level">Difficulty Level: ' . $question["difficulty_level"] . '</h3>';
                echo '<h3 id="question">' . $question["question_text"] . '</h3>';
                echo "<form action='' method='POST'>";
                echo "<input type='radio' style='margin-bottom:10px;' name='answer' value={$question['option_a']} required> {$question['option_a']}<br>";
                echo "<input type='radio' style='margin-bottom:10px;' name='answer' value={$question['option_b']}> {$question['option_b']}<br>";
                echo "<input type='radio' style='margin-bottom:10px;' name='answer' value={$question['option_c']}> {$question['option_c']}<br>";
                echo "<input type='radio' style='margin-bottom:10px;' name='answer' value={$question['option_d']}> {$question['option_d']}<br>";
                echo "<input type='hidden' name='question_id' value='{$question['question_id']}'>";
                echo "<button type='submit' name='checkAnswer'>NEXT</button>";
                echo "</form>";
            
            echo <<<HTML
                </div>
            </body>
            </html>
            HTML;
            exit();
        }
        else{
            echo <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>quiz</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <div class="container" id="non-admin-panel">
                    <h1>Quiz completed! Your score is: {$user["score"]}</h1>
                    <form action="" method="GET">
                        <button type="submit" name="viewScoreboard">View Score Board</button>
                    </form>
                </div>
            </body>
            </html>
            HTML;
            exit();
        }
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['viewScoreboard'])){
        $students = getAllStudents();

        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>quiz</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container" id="non-admin-panel">
            <h2 style="text-align: center;">User Scores</h2>
            <table>
                <thead>
                    <tr>
                     <th>Username</th>
                    <th>Score</th>
                 </tr>
                </thead>
                <tbody>
        HTML;
            foreach($students as $student){
                echo "<tr><th>".$student['username']."</th><th>".$student['score']."</th></tr>";
            }        
        echo <<<HTML
                </tbody>
            </table>
                <form action="" method="GET">
                    <button type="submit" style="margin-top:20px;">Go Home Page</button>
                </form>
            </div>
        </body>
        </html>
        HTML;
        exit();
    }
    elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkAnswer"])){
        $question_id = $_POST['question_id'];
        $user_answer = $_POST['answer'];

        $question = getQuestionById($question_id);
        if($user_answer == $question["correct_answer"]){
            increaseScore($_SESSION["user_id"]);
        }

        markQuestionAsSolved($_SESSION["user_id"], $question_id);
        header("Location: /app.php?startQuiz=");
        exit();
    }

    echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>quiz</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container" id="non-admin-panel">
                <form action="" method="GET">
                    <button type="submit" name="startQuiz">Start Quiz</button>
                </form>
                <form action="" method="GET">
                    <button type="submit" name="viewScoreboard">View Score Board</button>
                </form>
            </div>
        </body>
        </html>
        HTML;
}

?>