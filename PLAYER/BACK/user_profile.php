<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 24-May-17
 * Time: 01:28
 */
$stid = oci_parse($connection, 'SELECT last_name, first_name, email FROM player where username = :username');
$username = $_SESSION["username"];
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Nume</td>' . '<td class="profil_td">' . $row[1] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Prenume</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">E-mail</td>' . '<td class="profil_td">' . $row[2] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT to_char(pd.birthday,\'DD.MM.YYYY\'), pd.difficulty FROM player_dates pd join player p on pd.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Ziua de nastere</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Dificultate</td>' . '<td class="profil_td">' . $row[1] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT to_char(pa.logged_in,\'DD.MM.YYYY\') FROM player_activity pa join player p on pa.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Ultima logare</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);
oci_close($connection);