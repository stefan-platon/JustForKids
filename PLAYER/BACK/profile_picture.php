<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 03-Jun-17
 * Time: 13:59
 */

include ("../../BACK/conectare_db.php");

$username = $_SESSION["username"];
$stid = oci_parse($connection, 'SELECT LINK FROM player WHERE username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
$link = $row[0];
oci_free_statement($stid);

if (strcmp($link,'k') == 0)
    echo '<img src="../../../IMG/OTHER/user_icon.jpg" style="max-width:100%; max-height:100%;" alt="alt">';
else
    echo '<img src="../'. $link . '" style="max-width:100%; max-height:100%;" alt="alt">';

