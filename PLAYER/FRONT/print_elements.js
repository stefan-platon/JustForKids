function printTime(testTime, timeElement) {
    var tElement = document.getElementsByClassName(timeElement);
    if (testTime == 0)
        submitForm();
    else if (testTime % 60 < 10)
            tElement.innerHTML = "Timp ramas: " + (testTime / 60) + ":0" + (testTime % 60);
         else
            tElement.innerHTML = "Timp ramas: " + (testTime / 60) + ":0" + (testTime % 60);
    testTime--;
    var timeout = setTimeout("printTime(" + testTime + "," + timeElement + ")", 1000);
}
function reloadQuestion(quizElement, currentQuestion, questionsArray, answersArray) {

}
function submitForm() {
    var fElement = document.getElementsByClassName("quiz");
    fElement.submit();
}
