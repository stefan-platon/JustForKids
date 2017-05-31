<?php

session_start();
$answers=json_decode($_POST['answers']);
for($i=0;$i<10;$i++){
    echo "La intrebarea:<br>";
    echo $_SESSION['test'][0][$i][1].'<br>';
    echo "Ati raspuns: ".$answers[$i].'<br>';
    echo "Raspunsul corect era: ".$_SESSION['test'][1][$i][3].'<br>';
}

?>