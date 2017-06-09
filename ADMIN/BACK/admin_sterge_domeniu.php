<?php
include ("conectare_db.php");
$sql = 'begin :rezultat :=  admin_pachet.sterge_domeniu(:v_id); END;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_id", $_POST["d_id"]);
oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
oci_free_statement($stid);
oci_close($connection);
echo $rezultat;
?>