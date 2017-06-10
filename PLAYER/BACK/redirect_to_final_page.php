<?php

session_start();

if (!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/login_frame.html');
if($_SESSION['last_page']!="test_text.php" && $_SESSION['last_page']!="test_variante.php")
    header('Location: ../FRONT/HTML/logged_user_frame.html');

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
$username = $_SESSION['username'];

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

$domainId = $_SESSION['domain'];


//verific daca exista deja domeniul in tabela player_stats
$query = 'select count(domain_id) from player_stats where domain_id=:domainId and player_id=:playerId';
$stid = oci_parse($connection, $query);
$domainId2 = $domainId;

oci_bind_by_name($stid, ":domainId", $domainId2);
oci_bind_by_name($stid, ":playerId", $userId[0]);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $existsDomainId = $row;
}

//VERIFIC DACA EXISTA DEJA DOMENIUL IN PLAYER_STATS
if($existsDomainId[0]!=0){
    //iau din baza de date scorul maxim, scorul total si numarul de teste pe care le-a facut
    $query = 'select highest_score, total_score, number_of_plays from player_stats where domain_id=:domainId and player_id=:userId';
    $stid = oci_parse($connection, $query);
    $domain = $_SESSION['domain'];

    oci_bind_by_name($stid, ':userId', $userId[0]);
    oci_bind_by_name($stid, ":domainId", $domainId);
    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
    if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
        $playerStats = $row;
    }
    $update='update player_stats set highest_score=:highestScore, total_score=:totalScore, number_of_plays=:numberOfPlays, last_played=:lastPlayed where player_id=:userId and domain_id=:domainId';
    if($playerStats[0]>$_SESSION['score'])
        $highestScore=$playerStats[0];
    else
        $highestScore=$_SESSION['score'];
    $totalScore=$playerStats[1]+$_SESSION['score'];
    $numberOfPlays=$playerStats[2]+1;
    $lastPlayed=date("d-M-y");
}else{
    $update='insert into player_stats values(:userId, :domainId, :highestScore, :totalScore, :numberOfPlays, :lastPlayed)';
    $highestScore=$_SESSION['score'];
    $totalScore=$_SESSION['score'];
    $numberOfPlays=1;
    $lastPlayed=date("d-M-y");
}

//iau din baza de date numarul de teste care au fost facute pentru update
$query = 'select max(test_id) from tests';
$stid = oci_parse($connection, $query);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $nrTests = $row;
}
$nrTests[0]++;

//inserez in baza de date testul pe care tocmai l-a terminat
$insert='insert into tests values(:testId, :userId, :score, :domainId)';
$stid = oci_parse($connection, $insert);

oci_bind_by_name($stid, ':testId', $nrTests[0]);
oci_bind_by_name($stid, ':userId', $userId[0]);
oci_bind_by_name($stid, ':score', $_SESSION['score']);
oci_bind_by_name($stid, ':domainId', $domainId2);

$_SESSION['userAnswers']=json_decode($_POST['answers']);

if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}else{
    //inserez in baza de date detaliile despre ultimul test facut de player
    $stid = oci_parse($connection, $update);
    oci_bind_by_name($stid, ':userId', $userId[0]);
    oci_bind_by_name($stid, ':domainId', $domainId);
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

$_SESSION['last_page']="redirect_to_final_page.php";
?>
<html>
<head></head>
<body>
<script src="../FRONT/JAVASCRIPT/redirect.js">submitForm()</script>
<?php header("Location: final_page.php"); ?>
</body>
</html>
