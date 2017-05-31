<?php
session_start();
/*if ($_SESSION['online'] != true)
    header("Location:../../INTRO/FRONT/HTML/login_content.html");*/
/*$_SESSION["domain"] = $_POST['domain'];*/
include('conectare_db.php');
$_SESSION['domain']='Istorie';

$query = 'select pd.difficulty from player p join player_dates pd on p.player_id=pd.player_id where p.username=:username';
$stid = oci_parse($connection, $query);
/*$username = $_SESSION['username'];*/

/*HARDCODAT*/$username = 'Abaza.Carla';
/*HARDCODAT*/$_POST['tip'] = 'Variante';

oci_bind_by_name($stid, ":username", $username);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $user = $row;
}
oci_free_statement($stid);

oci_close($connection);

$_SESSION['difficulty'] = $user[0];

switch ($_SESSION['difficulty']) {
    case 1:
        $_SESSION['max_time'] = -1;
        break;
    case 2:
        $_SESSION['max_time'] = 5*60;
        break;
    case 3:
        $_SESSION['max_time'] = 3*60;
        break;
}

switch ($_POST['tip']) {
    case 'Text':
        header("Location:test_text.php");
        break;
    case 'Variante':
        header("Location:test_variante.php");
        break;
}

?>