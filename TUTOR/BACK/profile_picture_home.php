<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 10-Jun-17
 * Time: 00:33
 */

$username = $_SESSION["username"];
$stid = oci_parse($connection, 'SELECT LINK FROM tutor WHERE username = :username');
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);
$link = $row[0];
oci_free_statement($stid);


    echo '<img src="../'. $link . '" style="max-width:75%; max-height:75%;" alt="alt2">';
