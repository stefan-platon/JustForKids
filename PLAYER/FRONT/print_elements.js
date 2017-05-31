function printTime(testTime) {
    var tElement = document.getElementsByClassName('test-time');
    if (testTime === -1) {
        tElement.innerHTML = "Timp nelimitat.";
        return false;
    }
    if (testTime === 0)
        submitForm();
    else if (testTime % 60 < 10)
        tElement.innerHTML = "Timp ramas: " + (testTime / 60) + ":0" + (testTime % 60);
    else
        tElement.innerHTML = "Timp ramas: " + (testTime / 60) + ":0" + (testTime % 60);
    testTime--;
    var timeout = setTimeout("printTime(" + testTime +")", 1000);
}
function reloadQuestion(question, answer1, answer2, answer3, answer4) {
    var qElem = document.getElementsByClassName("question");

    var questionText = question;
    var randomAnswers = [0, 0, 0, 0];
    var answersArray = [answer1, answer2, answer3, answer4];
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

    qElem.innerHTML = "<div class='q-text'>" + questionText + "</div>";
    qElem += "<div class='answers'>";
    qElem += "<input type='button' class='q-answer' name='answer' value='" + answersArray[randomAnswers[0]] + "' onclick='phpReload()'><br>";
    qElem += "<input type='button' class='q-answer' name='answer' value='" + answersArray[randomAnswers[1]] + "' onclick='phpReload()'><br>";
    qElem += "<input type='button' class='q-answer' name='answer' value='" + answersArray[randomAnswers[2]] + "' onclick='phpReload()'><br>";
    qElem += "<input type='button' class='q-answer' name='answer' value='" + answersArray[randomAnswers[3]] + "' onclick='phpReload()'></br>";
    qElem += "</div>";
}
function submitForm() {
    var fElement = document.getElementsByClassName("quiz");
    fElement.submit();
}
function phpReload() {
    var relPHP = "<?php reloadAnswers();?>";
    alert(relPHP);
    return false;
}