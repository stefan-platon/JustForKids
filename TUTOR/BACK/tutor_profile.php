<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 27-May-17
 * Time: 13:11
 */
$stid = oci_parse($connection, 'SELECT last_name, first_name, email FROM tutor WHERE username = :username');
$username = $_SESSION["username"];
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Nume</td>' . '<td class="profil_td">' . $row[1] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Prenume</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">E-mail</td>' . '<td class="profil_td">' . $row[2] . '</td></tr>';
oci_free_statement($stid);

oci_close($connection);