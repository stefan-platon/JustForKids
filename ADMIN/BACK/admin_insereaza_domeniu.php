<?php
include ("conectare_db.php");
$sql = 'begin :rezultat :=  admin_pachet.insert_domeniu(:v_nume,:v_tip, :v_img); END;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_nume", $_POST["nume"]);
oci_bind_by_name($stid, ":v_tip", $_POST["tip"]);
oci_bind_by_name($stid, ":v_img", $_POST["img"]);
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
