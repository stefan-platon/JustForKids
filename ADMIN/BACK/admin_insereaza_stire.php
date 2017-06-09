<?php
include ("conectare_db.php");

$sql = 'begin :rezultat :=  admin_pachet.insert_stire(:v_titlu,:v_continut); END;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_titlu", $_POST["titlu"]);
oci_bind_by_name($stid, ":v_continut", $_POST["content"]);
oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}else
{
    echo "Stire inserata cu succes.";
}
oci_free_statement($stid);
oci_close($connection);
?>
