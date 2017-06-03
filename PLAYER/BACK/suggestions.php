<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 03-Jun-17
 * Time: 11:15
 */
include ("../../BACK/conectare_db.php");

$player_id = $_SESSION["player_id"];

$stid = oci_parse($connection, 'SELECT d.DOMAIN_NAME, d.SUBJECT_TYPE, p.total_score, p.number_of_plays from PLAYER_STATS p join DOMAINS d on p.DOMAIN_ID = d.DOMAIN_ID where p.PLAYER_ID = :player_id order by p.total_score desc');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
$total_score = $row[2];
$plays = $row[3];
$domain = $row[0];
$subject_type = $row[1];
oci_free_statement($stid);

$suggested_domains = array();
if ($plays != 0)
    if ($total_score / $plays >= 5) {
        $stid = oci_parse($connection, 'SELECT DOMAIN_NAME FROM DOMAINS WHERE SUBJECT_TYPE=:subject_type');
        oci_bind_by_name($stid, ':subject_type', $subject_type);
        oci_execute($stid);
        while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
            array_push($suggested_domains, $row[0]);
        }
        $info = "Domenii din aceeasi categorie (" . $subject_type . ") cu domeniul la care ati avut cele mai bune rezultate (" . $domain . ")";
        echo "<div>Sugestii de domenii: <img src='../../../IMG/OTHER/question_mark.png' alt='Info' height='20' width='20' title='" . $info . "'></div>";
        foreach ($suggested_domains as $suggested_domain)
            echo "<b>" . $suggested_domain . "</b><br>";
        oci_free_statement($stid);
    }
