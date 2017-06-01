<?php

session_start();
include("conectare_db.php");
$answers = json_decode($_POST['answers']);
$_SESSION['score'] = 0;
for ($i = 0; $i < 10; $i++)
    if ($_SESSION['test'][1][$i][3] == $answers[$i])
        $_SESSION['score']++;

$query = 'select player_id from player where username=:username';
$stid = oci_parse($connection, $query);
/*$username = $_SESSION['username'];*/

/*HARDCODAT*/$username = 'Abaza.Carla';

oci_bind_by_name($stid, ":username", $username);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $userId = $row;
}
$insert='insert into tests values(:testId, :userId, :score)';
$stid = oci_parse($connection, $insert);

oci_bind_by_name($stid, ':testId', $_SESSION['testId']);
oci_bind_by_name($stid, ':userId', $userId[0]);
oci_bind_by_name($stid, ':score', $_SESSION['score']);

$_SESSION['userAnswers']=json_decode($_POST['answers']);

if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}else
    header("Location:final_page.php");

oci_free_statement($stid);

oci_close($connection);

?>