<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 24-May-17
 * Time: 01:29
 */
$player_id = $_SESSION["player_id"];
$username = $_SESSION["username"];


$stid = oci_parse($connection, 'SELECT difficulty FROM player_profile_view WHERE username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Dificultate:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT sum(total_score) FROM player_stats where player_id = :player_id');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Scorul total:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT to_char(min(logged_in),\'DD.MM.YYYY\') FROM player_activity where player_id = :player_id');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Jucator JfK din:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT d.DOMAIN_NAME, p.total_score from PLAYER_STATS p join DOMAINS d on p.DOMAIN_ID = d.DOMAIN_ID where p.PLAYER_ID = :player_id order by p.total_score desc');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
if ($row[0] != 0)
    echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mare:</td>' . '<td class="profil_td">' . $row[0] . ' (' . $row[1] . ')</td></tr>';
else
    echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mare:</td>' . '<td class="profil_td"> - </td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT d.DOMAIN_NAME, p.total_score from PLAYER_STATS p join DOMAINS d on p.DOMAIN_ID = d.DOMAIN_ID where p.PLAYER_ID = :player_id order by p.total_score');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
if ($row[0] != 0)
    echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mic:</td>' . '<td class="profil_td">' . $row[0] . ' (' . $row[1] . ')</td></tr>';
else
    echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mic:</td>' . '<td class="profil_td"> - </td></tr>';

oci_free_statement($stid);

$stid = oci_parse($connection, 'select sum(number_of_plays) from player_stats where PLAYER_ID = :player_id group by player_id');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select count(test_id) from tests where PLAYER_ID = :player_id and score >= 5');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste cu nota peste 5:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select count(test_id) from tests where PLAYER_ID = :player_id and score < 5');
oci_bind_by_name($stid, ':player_id', $player_id);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste cu nota sub 5:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

oci_close($connection);
