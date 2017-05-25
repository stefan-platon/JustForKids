<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 24-May-17
 * Time: 01:28
 */
$stid = oci_parse($connection, 'SELECT last_name, first_name, email, birthday, difficulty FROM player_profile_view WHERE username = :username');
$username = $_SESSION["username"];
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Nume</td>' . '<td class="profil_td">' . $row[1] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Prenume</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">E-mail</td>' . '<td class="profil_td">' . $row[2] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Ziua de nastere</td>' . '<td class="profil_td">' . $row[3] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Dificultate</td>' . '<td class="profil_td">' . $row[4] . '</td></tr>';
oci_free_statement($stid);

oci_close($connection);
