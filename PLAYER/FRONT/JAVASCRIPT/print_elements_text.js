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
    answerBoxId="answer-box";
    qElem.innerHTML = "<div class='q-text'>"+questionText+"</div>" +
        "<div class='q-answer'>" +
            "<input id='answer-box' type='number' class='q-answer-box' autofocus>" +
            "<input id='answer-button' type='button' class='q-answer-button' value='Raspunde' onclick='memAnswer(document.getElementById(answerBoxId).value)'>" +
        "</div>";
}
function submitForm() {
    var fElement = document.getElementById("submit-test");
    fElement.innerHTML="<input id='givenAnswers' type='hidden' name='answers' value=''>";
    if(cont!==10)
        for(var c=cont;c<10;c++)
            givenAnswers[c]="null";
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