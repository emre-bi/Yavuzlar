<?php

function getUser($username){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $db = null;

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function getUserById($user_id){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $db = null;

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function getAllStudents(){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM users WHERE isAdmin = 0";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $db = null;

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function addQuestion($question_text, $option_1, $option_2, $option_3, $option_4, $correctAnswer, $difficultyLevel){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "INSERT INTO questions (question_text, correct_answer, option_a, option_b, option_c, option_d, difficulty_level) 
    VALUES (:question_text, :correct_answer, :option_1, :option_2, :option_3, :option_4, :difficulty_level)";

    $stmt = $db->prepare($query);


    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':correct_answer', $correctAnswer);
    $stmt->bindParam(':option_1', $option_1);
    $stmt->bindParam(':option_2', $option_2);
    $stmt->bindParam(':option_3', $option_3);
    $stmt->bindParam(':option_4', $option_4);
    $stmt->bindParam(':difficulty_level', $difficultyLevel);
    $stmt->execute();
    $db = null;    
}

function getQuestions(){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM questions";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $db = null;

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $questions;
}


function getQuestionById($id){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM questions WHERE question_id = :question_id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':question_id', $id);
    $stmt->execute();
    $db = null;

    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    return $question;
}

function getQuestion($user_id){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT solved_questions FROM users WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $solvedQuestions = isset($result['solved_questions']) ? explode(',', $result['solved_questions']) : [];

    if (!empty($solvedQuestions)) {
        $placeholders = implode(',', array_fill(0, count($solvedQuestions), '?'));
        $query = "SELECT * FROM questions WHERE question_id NOT IN ($placeholders) LIMIT 1";
        
        $stmt = $db->prepare($query);
        $stmt->execute($solvedQuestions);
    } else {
        $query = "SELECT * FROM questions LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    
    $question = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $db = null;
    
    return $question;
}

function deleteQuestion($id){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "DELETE FROM questions WHERE question_id = :id";
    $stmt = $db->prepare($query);   
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $db = null;
}


function editQuestion($id, $question_text, $correctAnswer, $option_a, $option_b, $option_c, $option_d, $difficultyLevel){
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "UPDATE questions SET question_text = :question_text, correct_answer = :correct_answer, option_a = :option_a, option_b = :option_b, option_c = :option_c, option_d = :option_d, difficulty_level = :difficulty_level WHERE question_id = :id";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
    $stmt->bindParam(':correct_answer', $correctAnswer, PDO::PARAM_STR);
    $stmt->bindParam(':option_a', $option_a, PDO::PARAM_STR);
    $stmt->bindParam(':option_b', $option_b, PDO::PARAM_STR);
    $stmt->bindParam(':option_c', $option_c, PDO::PARAM_STR);
    $stmt->bindParam(':option_d', $option_d, PDO::PARAM_STR);
    $stmt->bindParam(':difficulty_level', $difficultyLevel, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
    $db = null;
}

function increaseScore($user_id){
    $user = getUserById($user_id);
    if ($user && isset($user["score"])) {
        $new_score = $user["score"] + 1 ;
        $db = new PDO('sqlite:quiz.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "UPDATE users SET score = :score WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':score', $new_score, PDO::PARAM_INT);
        $stmt->execute();
        $db = null;
    }
}


function markQuestionAsSolved($user_id, $question_id) {
    $db = new PDO('sqlite:quiz.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT solved_questions FROM users WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $solvedQuestions = $result['solved_questions'];
    $solvedQuestionsArray = explode(',', $solvedQuestions);

    if (!in_array($question_id, $solvedQuestionsArray)) {
        $solvedQuestionsArray[] = $question_id;
        $newSolvedQuestions = implode(',', $solvedQuestionsArray);

        $query = "UPDATE users SET solved_questions = :solved_questions WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':solved_questions', $newSolvedQuestions, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    $db = null;
}

?>