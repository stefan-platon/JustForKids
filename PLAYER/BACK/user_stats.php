<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 24-May-17
 * Time: 01:29
 */
$username = $_SESSION["username"];
$stid = oci_parse($connection, 'SELECT sum(t.score) FROM tests t join player p on t.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Scorul total:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT to_char(min(pa.logged_in),\'DD.MM.YYYY\') FROM player_activity pa join player p on pa.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Prima logare:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select domain, x from (select d.domain_name as domain, sum(t.score) as x from domains d join tests t on d.domain_id = t.domain_id join player p on t.player_id = p.player_id
            where p.username = :username group by d.domain_name order by 2 desc) where rownum=1');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mare:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select domain, x from (select d.domain_name as domain, sum(t.score) as x from domains d join tests t on d.domain_id = t.domain_id join player p on t.player_id = p.player_id
            where p.username = :username group by d.domain_name order by 2) where rownum=1');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Domeniul cu scorul cel mai mic:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'SELECT round(sum(t.score)/count(t.score)*10, 2) FROM tests t join player p on t.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Procentajul de raspunsuri corecte:</td>' . '<td class="profil_td">' . $row[0] . '%</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select count(t.test_id) from tests t join player p on t.player_id = p.player_id where p.username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select count(t.test_id) from tests t join player p on t.player_id = p.player_id where p.username = :username and t.score >= 5');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste cu nota peste 5:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);

$stid = oci_parse($connection, 'select count(t.test_id) from tests t join player p on t.player_id = p.player_id where p.username = :username and t.score < 5');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
echo '<tr class="profil_tr">' . '<td class="profil_td">Numarul de teste cu nota sub 5:</td>' . '<td class="profil_td">' . $row[0] . '</td></tr>';
oci_free_statement($stid);


oci_close($connection);
