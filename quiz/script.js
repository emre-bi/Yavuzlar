let quizQuestions = [
    {
        id: 0,
        question: "What is the capital of France?",
        options: ["Berlin", "Madrid", "Paris", "Rome"],
        correctAnswer: "Paris",
        difficultyLevel: 2
    },
    {
        id: 1,
        question: "Which planet is known as the Red Planet?",
        options: ["Earth", "Mars", "Jupiter", "Saturn"],
        correctAnswer: "Mars",
        difficultyLevel: 2
    },
    {
        id: 2,
        question: "Who wrote 'To Kill a Mockingbird'?",
        options: ["Harper Lee", "Mark Twain", "Ernest Hemingway", "F. Scott Fitzgerald"],
        correctAnswer: "Harper Lee",
        difficultyLevel: 3
    },
    {
        id: 3,
        question: "What is the largest ocean on Earth?",
        options: ["Atlantic Ocean", "Indian Ocean", "Arctic Ocean", "Pacific Ocean"],
        correctAnswer: "Pacific Ocean",
        difficultyLevel: 4
    },
    {
        id: 4,
        question: "What is the chemical symbol for gold?",
        options: ["Au", "Ag", "Pb", "Fe"],
        correctAnswer: "Au",
        difficultyLevel: 1
    }
];

let score = 0;
let indexCounter = 0;


function divHandler(hide, display) {
    document.getElementById(hide).style.display = "none";
    document.getElementById(display).style.display = "unset";

    if(display == "question-list-container"){
        for (let i = quizQuestions.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [quizQuestions[i], quizQuestions[j]] = [quizQuestions[j], quizQuestions[i]];
        }

        showQuestion(indexCounter);
    }
    else if(display == "quiz-result-container") {
        document.getElementById("quiz-result-container-score").innerText = `Your Score is => ${score}`;
        score = 0;
        indexCounter = 0;
    }
    else if(display == "editor-container"){
        searchFunction();
        showEditPage();
    }
}

function showQuestion(index){
    document.getElementById("score").innerHTML = `Score: ${score}`;
    const questionElement = document.getElementById("question");
    const optionsElement = document.getElementById("options");
    const currentQuestion = quizQuestions[index];
    document.getElementById("difficulty-level").innerHTML = `Diffuculty: ${currentQuestion.difficultyLevel}`;
    questionElement.innerText = currentQuestion.question;
    optionsElement.innerHTML = '';

    currentQuestion.options.forEach(option => {
        const button = document.createElement("button");
        button.innerText = option;
        button.onclick = () => checkAnswer(option);
        optionsElement.appendChild(button);
    });
}

function checkAnswer(selectedOption) {
    const currentQuestion = quizQuestions[indexCounter];

    if (selectedOption === currentQuestion.correctAnswer) {
        score++;
    }

    indexCounter += 1;

    if (indexCounter < quizQuestions.length) {
        showQuestion(indexCounter);
    } else {
        divHandler("question-list-container", "quiz-result-container");
    }
}

function showEditPage() {
    const questionDiv = document.getElementById("questions-in-editor");
    questionDiv.innerHTML = "";
    
    quizQuestions.forEach(questionDict => {
        const containerDiv = document.createElement("div");
        containerDiv.className = "question-container";

        const span = document.createElement("span");
        span.innerText = questionDict.question;
        containerDiv.appendChild(span);

        const editBtn = document.createElement("button");
        editBtn.innerText = "Düzenle";
        editBtn.onclick = () => editQuestionPage(questionDict);
        containerDiv.appendChild(editBtn);

        const deleteBtn = document.createElement("button");
        deleteBtn.innerText = "Sil";
        deleteBtn.onclick = () => deleteQuestion(questionDict);
        containerDiv.appendChild(deleteBtn);

        questionDiv.appendChild(containerDiv);
    });
}

function deleteQuestion(questionDict) {
    quizQuestions = quizQuestions.filter(question => question.id != questionDict.id);
    showEditPage();
}

function editQuestionPage(questionDict) {
    document.getElementById("editor-container").style.display = "none";
    document.getElementById("edit-question-container").style.display = "unset";

    document.getElementById("question-id").value = questionDict.id;
    document.getElementById("difficulty-level-input").value = questionDict.difficultyLevel;
    document.getElementById("question-input").value = questionDict.question;
    document.getElementById("option1-input").value = questionDict.options[0];
    document.getElementById("option2-input").value = questionDict.options[1];
    document.getElementById("option3-input").value = questionDict.options[2];
    document.getElementById("option4-input").value = questionDict.options[3];
    document.getElementById("correct-answer-input").value = questionDict.correctAnswer;
}

function editQuestion() {
    let questionId = document.getElementById("question-id").value;
    let index = quizQuestions.findIndex(question => question.id == questionId);
    quizQuestions[index].question = document.getElementById("question-input").value;
    quizQuestions[index].options[0] = document.getElementById("option1-input").value;
    quizQuestions[index].options[1] = document.getElementById("option2-input").value;
    quizQuestions[index].options[2] = document.getElementById("option3-input").value;
    quizQuestions[index].options[3] = document.getElementById("option4-input").value;
    quizQuestions[index].correctAnswer = document.getElementById("correct-answer-input").value;
    quizQuestions[index].difficultyLevel = document.getElementById("difficulty-level-input").value;
    divHandler("edit-question-container", "editor-container");
}

function addQuestion() {
    let ids = [];
    for(let i = 0; i < quizQuestions.length; i++){
        ids.push(quizQuestions[i].id);
    }
    
    let maxId = Math.max(...ids);

    let id = maxId + 1;
    let question = document.getElementById("add-question-input").value;
    let option1 = document.getElementById("add-option1-input").value;
    let option2 = document.getElementById("add-option2-input").value;
    let option3 = document.getElementById("add-option3-input").value;
    let option4 = document.getElementById("add-option4-input").value;
    let correctAnswer = document.getElementById("add-correct-answer-input").value;
    let difficultyLevel = document.getElementById("difficulty-level-input").value;

    let newQuestion = {
        id: id,
        question: question,
        options: [option1, option2, option3, option4],
        correctAnswer: correctAnswer,
        difficultyLevel: difficultyLevel
    }
    quizQuestions.push(newQuestion);
    divHandler("add-question", "editor-container");
}

function searchFunction() {
    document.getElementById('search-bar').addEventListener('input', function() {
        let query = this.value.toLowerCase();
        let quizItems = document.querySelectorAll('.question-container');

        quizItems.forEach(function(item) {
            let question = item.querySelector('span').textContent.toLowerCase();
    
            if (question.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
}