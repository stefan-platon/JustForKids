var givenAnswers=[];
var cont=0;
var allQuestions=[];
var allAnswers=[];
var randomAnswers = [0, 0, 0, 0];
function printTime(testTime) {
    var tElement = document.getElementById('text-time');
    if (testTime === -1) {
        tElement.innerHTML = "Timp nelimitat.";
        return false;
    }
    if (testTime === 0)
        submitForm();
    else if (testTime % 60 < 10)
        tElement.innerHTML = "Timp ramas: " + (Math.floor(testTime / 60)) + ":0" + (testTime % 60);
    else
        tElement.innerHTML = "Timp ramas: " + (Math.floor(testTime / 60)) + ":" + (testTime % 60);
    testTime--;
    var timeout = setTimeout("printTime(" + testTime + ")", 1000);
}
function reloadQuestion() {
    var qElem = document.getElementById("question");

    var questionText = allQuestions[cont][1];
    randomAnswers = [0, 0, 0, 0];
    var i = 1;
    randomAnswers[0] = Math.floor(Math.random() * 4);
    var sw = 0;
    console.log(randomAnswers);
    while (sw === 0 && i < 4) {
        randomAnswers[i] = Math.floor(Math.random() * 4);
        for (var j = i - 1; j >= 0 && sw === 0; j--)
            if (randomAnswers[i] === randomAnswers[j])
                sw = 1;
        if (sw === 1) {
            randomAnswers[i] = Math.floor(Math.random() * 4);
            sw = 0;
        } else
            i++;
    }
    console.log(randomAnswers);
    qElem.innerHTML = "<div class='q-text'>" + questionText + "</div>" +
        "<div class='answers'>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[0]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[0]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[1]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[1]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[2]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[2]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[3]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[3]])'></br>" +
        "</div>";
}
function submitForm() {
    var fElement = document.getElementById("submit-test");
    fElement.innerHTML="<input id='givenAnswers' type='hidden' name='answers' value=''>";
    document.getElementById("givenAnswers").value=JSON.stringify(givenAnswers);
    fElement.submit();
}
function memAnswer(givenValue) {
    givenAnswers[cont]=givenValue;
    cont++;
    if(cont===10)
        submitForm();
    else
        reloadQuestion();
}
function saveInfo(questions, answers){
    allQuestions=questions;
    allAnswers=answers;
    reloadQuestion();
}