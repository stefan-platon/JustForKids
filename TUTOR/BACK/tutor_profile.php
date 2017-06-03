<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 27-May-17
 * Time: 13:11
 */


$stid = oci_parse($connection, 'SELECT last_name, first_name, email FROM tutor WHERE username = :username');
$t_username = $_SESSION["t_username"];
oci_bind_by_name($stid, ':username', $t_username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Nume</td>' . '<td class="profil_td">' . $row[1] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">Prenume</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
echo '<tr class="profil_tr">' . '<td class="profil_td">E-mail</td>' . '<td class="profil_td">' . $row[2] . '</td></tr>';
oci_free_statement($stid);

$_SESSION["user"] = $t_username;
$_SESSION["pname"] = $row[1];
$_SESSION["surname"] = $row[0];
$_SESSION["email"] = $row[2];

oci_close($connection);