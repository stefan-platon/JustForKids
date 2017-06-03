<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 28-May-17
 * Time: 21:45
 */
session_start();
$t_username = $_SESSION["t_username"];
$tutor_id = $_SESSION["tutor_id"];

$players = array();
$players_id = array();

$stid = oci_parse($connection, 'select LAST_NAME, FIRST_NAME, PLAYER_ID from player where TUTOR_ID = :tutor_id');
oci_bind_by_name($stid, ':tutor_id', $tutor_id);
oci_execute($stid);
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    array_push($players, $row[1] . ' ' . $row[0]);
    array_push($players_id, $row[2]);
}
oci_free_statement($stid);


echo '<div class="container_tutor_statistics">
    <form method="post" action="tutor_user_stats.html">
        <div class="list-players">';
$_SESSION["tplayer_id"] = $players_id;
for ($i = 0; $i < count($players); $i++) {
    echo '<div class="player">';
    echo '<input type="submit" id= "'.$i.'" value="'.$players[$i].'" name="name" class="player_button" style="">';
    echo '</div>';
}
echo '<div class="filler"></div>';
echo '</div>
    </form>
</div>';
