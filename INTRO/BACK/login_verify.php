<?php
include ("conectare_db.php");
$sql = 'begin :rezultat := user_pachet.login(:v_username,:v_password); end;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_username", $_POST["username"]);
oci_bind_by_name($stid, ":v_password", $_POST["password"]);
oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
oci_free_statement($stid);
oci_close($connection);
if($rezultat == 'p')
{
    header('Location: \logged_user_frame.html');
}
else if ($rezultat == 't')
{
    header('Location: \logged_tutor_frame.html');
}
else
{
    echo "<script type='text/javascript'>";
    echo "alert('failed!')";
    echo "</script>";
}
?>