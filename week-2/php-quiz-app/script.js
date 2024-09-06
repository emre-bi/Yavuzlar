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

searchFunction();