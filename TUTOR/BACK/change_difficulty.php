<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 01-Jun-17
 * Time: 17:34
 */

$diff = $_POST["difficulty"];
$player_id = $_SESSION["player_id"];
include ("conectare_db.php");

$stid = oci_parse($connection, 'UPDATE player_dates SET difficulty = :diff where player_id = :player_id');
oci_bind_by_name($stid, ':diff', $diff);
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
oci_free_statement($stid);

echo "Dificultatea s-a schimbat la " . $diff;