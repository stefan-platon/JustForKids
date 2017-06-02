//initializez vectorul pentru raspunsurile date de utilizator, numarul intrebarii curente, toate intrebarile si raspunsurile disponibile din test
var givenAnswers=[];
var cont=0;
var allQuestions=[];
var allAnswers=[];
//vector pentru randomizarea ordinii in care apar variantele de raspuns
//pentru a evita ca raspunsul corect sa se afle in acelasi loc de fiecare data
var randomAnswers = [0, 0, 0, 0];
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
    randomAnswers = [0, 0, 0, 0];
    var i = 1;
    randomAnswers[0] = Math.floor(Math.random() * 4);
    var sw = 0;
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
    qElem.innerHTML = "<div class='q-text'>" + questionText + "</div>" +
        "<div class='answers'>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[0]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[0]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[1]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[1]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[2]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[2]])'><br>" +
            "<input type='button' class='q-answer' name='answer' value='" + allAnswers[cont][randomAnswers[3]] + "' onclick='memAnswer(allAnswers[cont][randomAnswers[3]])'></br>" +
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