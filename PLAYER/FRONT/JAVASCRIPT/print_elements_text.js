//initializez vectorul pentru raspunsurile date de utilizator, numarul intrebarii curente, toate intrebarile si raspunsurile disponibile din test
var givenAnswers=[];
var cont=0;
var allQuestions=[];
var allAnswers=[];
//functie de afisare a timpului alocat pentru test
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
//functie cu rolul de a reincarca elementele intrebarii: textul si variantele de raspuns
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
//functie pentru submit fortat in cazul in care au fost parcurse toate 10 intrebarile sau se termina timpul alocat
function submitForm() {
    var fElement = document.getElementById("submit-test");
    fElement.innerHTML="<input id='givenAnswers' type='hidden' name='answers' value=''>";
    if(cont!==10)
        for(var c=cont;c<10;c++)
            givenAnswers[c]="null";
    document.getElementById("givenAnswers").value=JSON.stringify(givenAnswers);
    fElement.submit();
}
//functie de memorare locala a raspunsurilor date
function memAnswer(givenValue) {
    givenAnswers[cont]=givenValue;
    cont++;
    if(cont===10)
        submitForm();
    else
        reloadQuestion();
}
//functie de salvare locala a intrebarilor si raspunsurilor primite de la server
function saveInfo(questions, answers){
    allQuestions=questions;
    allAnswers=answers;
    reloadQuestion();
}