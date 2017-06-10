<?php
session_start();
include("conectare_db.php");
$sql = 'begin :rezultat :=  user_pachet.insert_suport(:v_username,:v_cat,:v_text);END;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_username", $_SESSION["username"]);
oci_bind_by_name($stid, ":v_cat", $_POST["p_category"]);
oci_bind_by_name($stid, ":v_text", $_POST["problem"]);
oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
oci_free_statement($stid);
oci_close($connection);
header("Location: ../FRONT/HTML/logged_tutor_frame.html");
?>