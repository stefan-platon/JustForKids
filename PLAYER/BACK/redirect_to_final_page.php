<?php

session_start();
/*if ($_SESSION['online'] != true)
    header("Location:../../INTRO/FRONT/HTML/login_content.html");*/
include("conectare_db.php");
$answers = json_decode($_POST['answers']);
$_SESSION['score'] = 0;

//calculez scorul
for ($i = 0; $i < 10; $i++)
    if ($_SESSION['test'][1][$i][3] == $answers[$i])
        $_SESSION['score']++;

//iau din baza de date id-ul userului
$query = 'select player_id from player where username=:username';
$stid = oci_parse($connection, $query);
/*$username = $_SESSION['username'];*/

/*HARDCODAT*/$username = 'Abaza.Casandra';

oci_bind_by_name($stid, ":username", $username);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $userId = $row;
}

//iau din baza de date id-ul domeniului
$query = 'select domain_id from domains where domain_name=:domain';
$stid = oci_parse($connection, $query);
$domain = $_SESSION['domain'];

oci_bind_by_name($stid, ":domain", $domain);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $domainId = $row;
}

//verific daca exista deja domeniul in tabela player_stats
$query = 'select domain_id from player_stats where domain_id=:domainId';
$stid = oci_parse($connection, $query);
$domainId2 = $domainId[0];

oci_bind_by_name($stid, ":domainId", $domainId2);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $existsDomainId = $row;
}

//iau din baza de date scorul maxim, scorul total si numarul de teste pe care le-a facut
$query = 'select highest_score, average_score, number_of_plays from player_stats where domain_id=:domainId and player_id=:userId';
$stid = oci_parse($connection, $query);
$domain = $_SESSION['domain'];

oci_bind_by_name($stid, ':userId', $userId[0]);
oci_bind_by_name($stid, ":domainId", $domainId[0]);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $playerStats = $row;
}

//inserez in baza de date testul pe care tocmai l-a terminat
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
}else{
    //inserez in baza de date detaliile despre ultimul test facut de player
    if($existsDomainId[0]==null)
            $update='insert into player_stats values(:userId, :domainId, :highestScore, :totalScore, :numberOfPlays, :lastPlayed)';
        else
            $update='update player_stats set highest_score=:highestScore, average_score=:totalScore, number_of_plays=:numberOfPlays, last_played=:lastPlayed where player_id=:userId and domain_id=:domainId';
    $stid = oci_parse($connection, $update);
    $highestScore=$playerStats[0];
    $totalScore=$playerStats[1]+$_SESSION['score'];
    $numberOfPlays=$playerStats[2]+1;
    $lastPlayed=date("d-M-y");
    oci_bind_by_name($stid, ':userId', $userId[0]);
    oci_bind_by_name($stid, ':domainId', $domainId[0]);
    oci_bind_by_name($stid, ':highestScore', $highestScore);
    oci_bind_by_name($stid, ':totalScore', $totalScore);
    oci_bind_by_name($stid, ':numberOfPlays', $numberOfPlays);
    oci_bind_by_name($stid, ':lastPlayed', $lastPlayed);
    //daca inserarea de face cu succes redirectez la pagina finala
    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }else
        header("Location:final_page.php");
}


oci_free_statement($stid);

oci_close($connection);

?>