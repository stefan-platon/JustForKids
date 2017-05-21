<?php
include("conectare_db.php");

$query = "SELECT p.player_id, p.first_name, p.last_name, p.username, p.email from PLAYER p join TUTOR t on p.tutor_id=t.tutor_id
          where
          (p.first_name like :p_name or p.last_name like :p_name) and p.username like :p_username and p.email like :p_email and
          (t.first_name like :t_name or t.last_name like :t_name) and t.username like :t_username and t.email like :t_email and 
          p.logged like :logged";

$stid = oci_parse($connection, $query);

$p_name_sec = '%' . $_POST["player_name"] . '%';
$p_username = '%' . $_POST["player_username"] . '%';
$p_email = '%' . $_POST["player_email"] . '%';
$t_name_sec = '%' . $_POST["tutor_name"] . '%';
$t_username = '%' . $_POST["tutor_username"] . '%';
$t_email = '%' . $_POST["tutor_email"] . '%';
$t_logged = '%' . $_POST["logged"] . '%';

oci_bind_by_name($stid, ":p_name", $p_name_sec);
oci_bind_by_name($stid, ":p_username", $p_username);
oci_bind_by_name($stid, ":p_email", $p_email);
oci_bind_by_name($stid, ":t_name", $t_name_sec);
oci_bind_by_name($stid, ":t_username", $t_username);
oci_bind_by_name($stid, ":t_email", $t_email);
oci_bind_by_name($stid, ":logged", $t_logged);

oci_execute($stid);

if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    $flag = true;
else
    $flag = false;
$flag_primar = $flag;
echo "<div class = 'clasa_2'>";
if ($flag_primar == false)
    $result = "No result with given parameters was found.";
else
{
    $result = '<table border="1">' . '<tr>' . '<td>Player ID</td>' . '<td>First Name</td>' . '<td>Last Name</td>' . '<td>Username</td>' . '<td>Email</td>' . '</tr>';
}
while ($flag != false) {
    if ($row['PLAYER_ID'] != null)
        $result = $result . "<tr><td>" . $row['PLAYER_ID'] . "</td><td>" . $row['FIRST_NAME'] . "</td><td>" . $row['SECOND_NAME'] . "</td><td>" . $row['USERNAME'] . "</td><td>" . $row['EMAIL'] . "</td></tr>";
    if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
        $flag = true;
    else
        $flag=false;
}
if ($flag_primar != false) {
    $result = $result . "</table>";
    oci_free_statement($stid);
} else
    $flag = false;
echo $result;
echo "</div>";
oci_close($connection);
?>
