<?php

$_SESSION["domain"]=$_POST['domain'];
switch($_POST['tip']){
    case 'Text':
        header("Location:../HTML/test_text.php");
        break;
    case 'Variante':
        header("Location:../HTML/test_variante.php");
        break;
}

?>